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

    if (!password || !password_confirmation) {
      Swal.fire({
        icon: "warning",
        title: "Peringatan",
        text: "Semua field harus diisi!",
      });
      return;
    }

    if (password !== password_confirmation) {
      Swal.fire({
        icon: "warning",
        title: "Password Tidak Cocok",
        text: "Password dan konfirmasi harus sama.",
      });
      return;
    }

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

      if (!response.ok) {
        Swal.fire({
          icon: "success",
          title: "Password Diperbarui",
          text: "Password berhasil diperbarui. Silakan login kembali.",
        }).then(() => {
          window.location.href = "/assets/html/loginregis.html";
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Gagal Reset Password",
          text: data.message || "Terjadi kesalahan saat reset password.",
        });
      }
    } catch (error) {
      console.error("Reset error:", error);
      Swal.fire({
        icon: "error",
        title: "Kesalahan Server",
        text: "Terjadi masalah saat menghubungi server.",
      });
    }
  });
