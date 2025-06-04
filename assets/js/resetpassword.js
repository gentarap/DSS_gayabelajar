document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  document.getElementById("resetToken").value = urlParams.get("token");
  document.getElementById("resetEmail").value = urlParams.get("email");
});

document
  .getElementById("resetPasswordForm")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    const token = document.getElementById("resetToken").value;
    const email = document.getElementById("resetEmail").value;
    const password = document.getElementById("newPassword").value;
    const password_confirmation =
      document.getElementById("confirmPassword").value;

    try {
      const response = await fetch("http://127.0.0.1:8000/api/password/reset", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          token,
          email,
          password,
          password_confirmation,
        }),
      });

      const data = await response.json();

      if (response.ok) {
        Swal.fire({
          icon: "error",
          title: "Gagal Reset Password",
          text: "Terjadi kesalahan saat reset password.",
        });
      } else {
        Swal.fire({
          icon: "success",
          title: "Password Berhasil Diubah",
          text: "Password berhasil diperbarui. Silakan login kembali.",
        }).then(() => {
          window.location.href = "/assets/html/loginregis.html"; // Arahkan kembali ke login
        });
      }
    } catch (error) {
      console.error("Reset error:", error);
      Swal.fire({
        icon: "error",
        title: "Kesalahan",
        text: "Terjadi masalah saat menghubungi server.",
      });
    }
  });
