// // ==================== FORM TOGGLE HANDLER ====================
// var panelOne = $(".form-panel.two").height(),
//   panelTwo = $(".form-panel.two")[0].scrollHeight;

// $(".form-panel.two")
//   .not(".form-panel.two.active")
//   .on("click", function (e) {
//     e.preventDefault();

//     $(".form-toggle").addClass("visible");
//     $(".form-panel.one").addClass("hidden");
//     $(".form-panel.two").addClass("active");
//     $(".form").animate(
//       {
//         height: $(".form-panel.two")[0].scrollHeight,
//       },
//       200
//     );
//   });

// $(".form-toggle").on("click", function (e) {
//   e.preventDefault();
//   $(this).removeClass("visible");
//   $(".form-panel.one").removeClass("hidden");
//   $(".form-panel.two").removeClass("active");
//   $(".form").animate(
//     {
//       height: $(".form-panel.two").height(),
//     },
//     200
//   );
// });

$(document).ready(function () {
  console.log("JS terhubung");
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
          text: error.message,
        });
        console.error("Login error:", error);
      }
    });
  }

  // ==================== REGISTER HANDLER ====================
  console.log("Mengecek form register:", $("#registerForm").length);
  $(document).on("submit", "#registerForm", async function (e) {
    console.log(">> Event submit terdeteksi");
    e.preventDefault();

    const username = $("#username").val();
    const name = $("#name").val();
    const email = $("#registeremail").val();
    const password = $("#registerpassword").val();
    const cpassword = $("#cpassword").val();

    const payload = {
      username,
      name,
      email,
      password,
    };

    console.log("Data JSON yang dikirim:", JSON.stringify(payload));

    if (password !== cpassword) {
      Swal.fire({
        icon: "warning",
        title: "Password Tidak Sama",
        text: "Password dan konfirmasi harus cocok.",
      });
      return;
    }

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
        });

        $("#registerForm")[0].reset();
        $(".form-toggle").click(); // Kembali ke login
      } else {
        Swal.fire({
          icon: "error",
          title: "Registrasi Gagal",
          text: data.message || JSON.stringify(data),
        });
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Terjadi Error",
        text: error.message,
      });
      console.error("Register error:", error);
    }
  });
});
