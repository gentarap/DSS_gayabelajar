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

// Login handler
document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Hindari reload halaman

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  fetch("http://127.0.0.1:8000/api/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ email, password }),
  })
    .then((response) => {
      if (!response.ok) {
        return response.json().then((data) => {
          throw new Error(data.message || "Login gagal");
        });
      }
      return response.json();
    })
    .then((data) => {
      alert("Login berhasil!");
      console.log("Token / response:", data);

      // Simpan token jika diberikan
      if (data.token) {
        localStorage.setItem("token", data.token);
      }

      // Redirect jika perlu
      window.location.href = "/index.html";
    })
    .catch((error) => {
      alert("Login gagal: " + error.message);
      console.error("Error:", error);
    });
});

$(document).ready(function () {
  // Pastikan form register bisa ditangkap walau belum aktif di awal
  $(document).on("submit", "#registerForm", async function (e) {
    e.preventDefault();
    console.log("Register form submitted"); // Debug log

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
      const response = await fetch("http://127.0.0.1:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (response.ok) {
        alert("Registrasi berhasil!");
        // Bisa redirect ke login atau bersihkan form
        $("#registerForm")[0].reset();
        $(".form-toggle").click(); // Kembali ke login panel
      } else {
        alert("Registrasi gagal: " + (data.message || JSON.stringify(data)));
      }
    } catch (error) {
      alert("Terjadi error: " + error.message);
    }
  });
});

// // ==================== LOGIN & REGISTER FUNCTIONALITY ====================
// $(document).ready(function () {
//   var panelOne = $(".form-panel.two").height(),
//     panelTwo = $(".form-panel.two")[0].scrollHeight;

//   $(".form-panel.two")
//     .not(".form-panel.two.active")
//     .on("click", function (e) {
//       e.preventDefault();

//       $(".form-toggle").addClass("visible");
//       $(".form-panel.one").addClass("hidden");
//       $(".form-panel.two").addClass("active");
//       $(".form").animate(
//         {
//           height: $(".form-panel.two")[0].scrollHeight,
//         },
//         200
//       );
//     });

//   $(".form-toggle").on("click", function (e) {
//     e.preventDefault();
//     $(this).removeClass("visible");
//     $(".form-panel.one").removeClass("hidden");
//     $(".form-panel.two").removeClass("active");
//     $(".form").animate(
//       {
//         height: $(".form-panel.two").height(),
//       },
//       200
//     );
//   });
// });

// console.log("JS terhubung");

// // ==================== UTILITY FUNCTIONS ====================
// // Fungsi untuk mendapatkan CSRF token
// async function getCSRFToken() {
//   try {
//     const response = await fetch("http://127.0.0.1:8000/sanctum/csrf-cookie", {
//       credentials: "include",
//     });
//     return response.ok;
//   } catch (error) {
//     console.error("Error getting CSRF token:", error);
//     return false;
//   }
// }

// // Fungsi untuk membuat request dengan session
// async function makeSessionRequest(url, options = {}) {
//   // Pastikan CSRF token tersedia
//   await getCSRFToken();

//   const defaultOptions = {
//     credentials: "include", // PENTING: untuk session cookies
//     headers: {
//       "Content-Type": "application/json",
//       Accept: "application/json",
//     },
//   };

//   // Merge dengan options yang diberikan
//   const finalOptions = {
//     ...defaultOptions,
//     ...options,
//     headers: {
//       ...defaultOptions.headers,
//       ...options.headers,
//     },
//   };

//   return fetch(url, finalOptions);
// }

// // Fungsi untuk check apakah user sudah login (via session)
// async function isLoggedIn() {
//   try {
//     const response = await makeSessionRequest("http://127.0.0.1:8000/api/user");
//     return response.ok;
//   } catch (error) {
//     console.error("Error checking login status:", error);
//     return false;
//   }
// }

// // Fungsi untuk mendapatkan info user yang sedang login
// async function getCurrentUser() {
//   try {
//     const response = await makeSessionRequest("http://127.0.0.1:8000/api/user");
//     if (response.ok) {
//       return await response.json();
//     }
//     return null;
//   } catch (error) {
//     console.error("Error getting current user:", error);
//     return null;
//   }
// }

// // Fungsi untuk logout
// async function logout() {
//   try {
//     await makeSessionRequest("http://127.0.0.1:8000/api/logout", {
//       method: "POST",
//     });
//   } catch (error) {
//     console.error("Error during logout:", error);
//   } finally {
//     // Clear any local storage
//     localStorage.clear();
//     window.location.href = "/login.html"; // Sesuaikan dengan halaman login Anda
//   }
// }

// // ==================== LOGIN HANDLER ====================
// document.addEventListener("DOMContentLoaded", function () {
//   const loginForm = document.getElementById("loginForm");
//   if (loginForm) {
//     loginForm.addEventListener("submit", async function (e) {
//       e.preventDefault();

//       const email = document.getElementById("email").value;
//       const password = document.getElementById("password").value;

//       try {
//         const response = await makeSessionRequest(
//           "http://127.0.0.1:8000/api/login",
//           {
//             method: "POST",
//             body: JSON.stringify({ email, password }),
//             credentials: "include",
//           }
//         );

//         if (!response.ok) {
//           const data = await response.json();
//           throw new Error(data.message || "Login gagal");
//         }

//         const data = await response.json();
//         alert("Login berhasil!");
//         console.log("Login response:", data);

//         // Simpan info user ke localStorage untuk kemudahan akses
//         if (data.user) {
//           localStorage.setItem("user", JSON.stringify(data.user));
//         }

//         // Redirect ke dashboard
//         window.location.href = "/index.html";
//       } catch (error) {
//         alert("Login gagal: " + error.message);
//         console.error("Login error:", error);
//       }
//     });
//   }
// });

// // ==================== REGISTER HANDLER ====================
// $(document).ready(function () {
//   $(document).on("submit", "#registerForm", async function (e) {
//     e.preventDefault();
//     console.log("Register form submitted");

//     const username = $("#username").val();
//     const name = $("#Name").val();
//     const email = $("#registeremail").val();
//     const password = $("#registerpassword").val();
//     const cpassword = $("#cpassword").val();

//     if (password !== cpassword) {
//       alert("Password dan Confirm Password tidak sama.");
//       return;
//     }

//     const payload = {
//       username,
//       name,
//       email,
//       password,
//     };

//     try {
//       const response = await makeSessionRequest(
//         "http://127.0.0.1:8000/api/register",
//         {
//           method: "POST",
//           body: JSON.stringify(payload),
//         }
//       );

//       const data = await response.json();

//       if (response.ok) {
//         alert("Registrasi berhasil!");
//         $("#registerForm")[0].reset();
//         $(".form-toggle").click();
//       } else {
//         alert("Registrasi gagal: " + (data.message || JSON.stringify(data)));
//       }
//     } catch (error) {
//       alert("Terjadi error: " + error.message);
//       console.error("Register error:", error);
//     }
//   });
// });
