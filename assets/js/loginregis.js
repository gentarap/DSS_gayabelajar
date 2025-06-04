// ==================== FORM TOGGLE HANDLER ====================
var panelOne = $(".form-panel.two").height(),
  panelTwo = $(".form-panel.two")[0].scrollHeight;

$(".form-panel.two")
  .not(".form-panel.two.active")
  .on("click", function (e) {
    e.preventDefault();

    $(".form-toggle").addClass("visible");
    $(".form-panel.one").addClass("hidden");
    $(".form-panel.two").addClass("active");
    $(".form").animate(
      {
        height: $(".form-panel.two")[0].scrollHeight,
      },
      200
    );
  });

$(".form-toggle").on("click", function (e) {
  e.preventDefault();
  $(this).removeClass("visible");
  $(".form-panel.one").removeClass("hidden");
  $(".form-panel.two").removeClass("active");
  $(".form").animate(
    {
      height: $(".form-panel.one")[0].scrollHeight,
    },
    200
  );
});

$("#showRegister").on("click", function (e) {
  e.preventDefault();
  $(".form-toggle").addClass("visible");
  $(".form-panel.one").addClass("hidden");
  $(".form-panel.two").addClass("active");
  $(".form").animate(
    {
      height: $(".form-panel.two")[0].scrollHeight,
    },
    200
  );
});

$("#showLogin").on("click", function (e) {
  e.preventDefault();
  $(".form-toggle").removeClass("visible");
  $(".form-panel.one").removeClass("hidden");
  $(".form-panel.two").removeClass("active");
  $(".form").animate(
    {
      height: $(".form-panel.two").height(),
    },
    200
  );
});

$(document).ready(function () {
  console.log("JS terhubung");
  console.log("Register Button: ", document.getElementById("registerButton"));

  // ==================== LOGIN HANDLER ====================
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        console.log("Login form submitted");
        const response = await makeSessionRequest(
          "http://127.0.0.1:8000/api/login",
          {
            method: "POST",
            body: JSON.stringify({ email, password }),
            credentials: "include",
          }
        );

        if (!response.ok) {
          const data = await response.json();

          if (response.status === 401) {
            throw new Error("Email atau password salah.");
          }

          throw new Error(data.message || "Login gagal");
        }

        const data = await response.json();
        console.log("Login response:", data);

        if (data.access_token && data.user) {
          localStorage.setItem(
            "user",
            JSON.stringify({
              id: data.user.user_id,
              username: data.user.username,
              access_token: data.access_token,
            })
          );
          localStorage.setItem("loggedIn", "true");

          Swal.fire({
            icon: "success",
            title: "Login Berhasil",
            text: "Selamat datang, " + data.user.username,
            showConfirmButton: false,
            timer: 2000,
          }).then(() => {
            window.location.href = "/index.html";
          });
        } else {
          throw new Error("Data login tidak lengkap.");
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Login Gagal",
          text: error.message || "Terjadi kesalahan saat login.",
        });
        console.error("Login error:", error);
      }
    });
  }

  // ==================== REGISTER HANDLER ====================
  console.log("Mengecek form register:", $("#registerForm").length);
  document
    .getElementById("registerButton")
    .addEventListener("click", async function (e) {
      e.preventDefault();
      console.log("Tombol REGISTER ditekan");

      const usernameInput = document.getElementById("username");
      const nameInput = document.getElementById("name");
      const passwordInput = document.getElementById("registerpassword");
      const confirmPasswordInput = document.getElementById("cpassword");
      const emailInput = document.getElementById("registeremail");

      if (
        !usernameInput ||
        !nameInput ||
        !passwordInput ||
        !confirmPasswordInput ||
        !emailInput
      ) {
        console.error("Salah satu elemen input tidak ditemukan!");
        return;
      }

      const username = usernameInput.value.trim();
      const name = nameInput.value.trim();
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;
      const email = emailInput.value.trim();

      // Validasi semua input harus diisi
      if (!username || !name || !password || !confirmPassword || !email) {
        Swal.fire({
          icon: "warning",
          title: "Pendaftaran Gagal",
          text: "Semua field harus diisi.",
        });
        return;
      }

      // Validasi password dan konfirmasi harus sama
      if (password !== confirmPassword) {
        Swal.fire({
          icon: "warning",
          title: "Password Tidak Cocok",
          text: "Password dan konfirmasi password tidak sama.",
        });
        return;
      }

      const payload = {
        username: username,
        name: name,
        password: password,
        email: email,
      };

      try {
        const response = await fetch("http://127.0.0.1:8000/api/register", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(payload),
        });

        const data = await response.json();
        console.log("Register response:", data);

        if (response.ok) {
          Swal.fire({
            icon: "success",
            title: "Registrasi Berhasil",
            text: "Silakan login menggunakan akun Anda.",
            timer: 2000,
            showConfirmButton: false,
          }).then(() => {
            // Kembali ke form login
            $(".form-toggle").removeClass("visible");
            $(".form-panel.one").removeClass("hidden").show();
            $(".form-panel.two").removeClass("active").hide();
            $(".form").css("height", $(".form-panel.one")[0].scrollHeight);
          });

          // Reset form
          usernameInput.value = "";
          nameInput.value = "";
          passwordInput.value = "";
          confirmPasswordInput.value = "";
          emailInput.value = "";
        } else {
          Swal.fire({
            icon: "error",
            title: "Registrasi Gagal",
            text: data.message || "Terjadi kesalahan saat registrasi.",
          });
        }
      } catch (error) {
        console.error("Error saat register:", error);
        Swal.fire({
          icon: "error",
          title: "Gagal Terhubung",
          text: "Tidak dapat menghubungi server. Pastikan backend berjalan.",
        });
      }
    });
});
