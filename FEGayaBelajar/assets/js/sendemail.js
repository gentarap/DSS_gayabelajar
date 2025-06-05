document
.getElementById("forgotPasswordForm")
.addEventListener("submit", async function (e) {
  e.preventDefault();

  const email = document.getElementById("forgotEmail").value;

  if (!email) {
    Swal.fire({
      icon: "warning",
      title: "Email Wajib Diisi",
      text: "Silakan masukkan email Anda terlebih dahulu.",
    });
    return;
  }

  try {
    const response = await fetch("http://127.0.0.1:8000/api/password/email", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email }),
    });

    const data = await response.json();

    if (response.ok) {
      Swal.fire({
        icon: "success",
        title: "Link Terkirim",
        text: "Periksa email Anda untuk link reset password.",
      }).then(() => {
        window.location.href = "/index.html";
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: data.message || "Gagal mengirim link reset.",
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Kesalahan",
      text: "Terjadi kesalahan jaringan atau server.",
    });
    console.error("Error saat kirim reset email:", error);
  }
});