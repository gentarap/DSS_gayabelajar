// ==================== LOGIN & REGISTER FUNCTIONALITY ====================
$(document).ready(function () {
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
        height: $(".form-panel.two").height(),
      },
      200
    );
  });
});

console.log("JS terhubung");


// ==================== LOGIN HANDLER ====================
document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", 
    async function (e) {
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
        alert("Login berhasil!");
        console.log("Login response:", data);

        // Simpan info user ke localStorage untuk kemudahan akses
        if (data.access_token) {
          localStorage.setItem("user", JSON.stringify(data.access_token));
        }

        // Redirect ke dashboard
        window.location.href = "/quis.html";
      } catch (error) {
        alert("Login gagal: " + error.message);
        console.error("Login error:", error);
      }
    });
  }
});

// ==================== REGISTER HANDLER ====================
$(document).ready(function () {
  $(document).on("submit", "#registerForm", async function (e) {
    e.preventDefault();
    console.log("Register form submitted");

    const username = $("#username").val();
    const name = $("#Name").val();
    const email = $("#registeremail").val();
    const password = $("#registerpassword").val();
    const cpassword = $("#cpassword").val();

    if (password !== cpassword) {
      alert("Password dan Confirm Password tidak sama.");
      return;
    }

    const payload = {
      username,
      name,
      email,
      password,
    };

    try {
      const response = await makeSessionRequest(
        "http://127.0.0.1:8000/api/register",
        {
          method: "POST",
          body: JSON.stringify(payload),
        }
      );

      const data = await response.json();

      if (response.ok) {
        alert("Registrasi berhasil!");
        $("#registerForm")[0].reset();
        $(".form-toggle").click();
      } else {
        alert("Registrasi gagal: " + (data.message || JSON.stringify(data)));
      }
    } catch (error) {
      alert("Terjadi error: " + error.message);
      console.error("Register error:", error);
    }
  });
});
