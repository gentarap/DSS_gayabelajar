// ==================== QUIZ FUNCTIONALITY ====================
const startButton = document.getElementById("start-button");
const quizContainer = document.getElementById("quiz-container");
const questionText = document.getElementById("question-text");
const answersContainer = document.getElementById("answers-container");
const backButton = document.getElementById("back-button");
const nextButton = document.getElementById("next-button");
const submitButton = document.getElementById("submit-button");

let questions = [];
let currentQuestionIndex = 0;
const answersSelected = {};
let currentUser = null;

function isLoggedIn() {
  const user = localStorage.getItem("user");
  return !!user;
}

function getCurrentUser() {
  const user = localStorage.getItem("user");
  return user ? JSON.parse(user) : null;
}

// Check login status saat halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
  if (startButton) {
    if (!isLoggedIn()) {
      alert("Anda harus login terlebih dahulu!");
      window.location.href = "/loginregis.html";
      return;
    }

    currentUser = getCurrentUser();
    if (currentUser) {
      const userNameElement = document.getElementById("user-name");
      if (userNameElement) {
        userNameElement.textContent = currentUser.name || currentUser.email;
      }
    }
  }
});

// MULAI KUIS
if (startButton) {
  startButton.addEventListener("click", async () => {
    if (!isLoggedIn()) {
      alert("Anda harus login terlebih dahulu!");
      window.location.href = "/loginregis.html";
      return;
    }

    try {
      await loadQuestions();
      startButton.parentElement.style.display = "none";
      quizContainer.style.display = "block";
      renderQuestion();
    } catch (error) {
      console.error("Error starting quiz:", error);
      alert("Gagal memuat soal quiz. Silakan coba lagi.");
    }
  });
}

// Ambil user dari localStorage
function getCurrentUser() {
  try {
    const userData = localStorage.getItem("user");
    return userData ? JSON.parse(userData) : null;
  } catch (e) {
    return null;
  }
}

// Cek apakah user login
function isLoggedIn() {
  const user = getCurrentUser();
  return !!user;
}

// Load pertanyaan dari API
async function loadQuestions() {
  try {
    const user = getCurrentUser();
    const token = user?.access_token;

    if (!token) {
      throw new Error("Token tidak ditemukan. Silakan login.");
    }

    const response = await fetch("http://127.0.0.1:8000/api/questions", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();
    questions = data;

    // Buat mapping skor dari data yang diterima
    window.answerScores = {};
    questions.forEach((question) => {
      question.answers.forEach((answer) => {
        window.answerScores[answer.answer_id] = {
          visual: answer.skor_visual || 0,
          auditory: answer.skor_auditory || 0,
          kinestetik: answer.skor_kinestetik || 0,
        };
      });
    });

    renderQuestion();
  } catch (error) {
    console.error("Gagal memuat soal:", error);
    alert("Gagal memuat soal. Silakan coba lagi.");
  }
}

// Render satu soal
function renderQuestion() {
  if (!questions || questions.length === 0) {
    alert("Tidak ada soal yang tersedia.");
    return;
  }

  const question = questions[currentQuestionIndex];
  questionText.textContent = `${currentQuestionIndex + 1}. ${
    question.question_text
  }`;
  answersContainer.innerHTML = "";

  question.answers.forEach((answer) => {
    const label = document.createElement("label");
    label.innerHTML = `
      <input type="radio" name="answer" value="${answer.answer_id}" ${
      answersSelected[question.question_id] === answer.answer_id
        ? "checked"
        : ""
    }>
      ${answer.answer_type}
    `;
    answersContainer.appendChild(label);
  });

  backButton.style.display = currentQuestionIndex > 0 ? "inline-block" : "none";
  nextButton.style.display =
    currentQuestionIndex < questions.length - 1 ? "inline-block" : "none";
  submitButton.style.display =
    currentQuestionIndex === questions.length - 1 ? "inline-block" : "none";
}

// Simpan jawaban
function saveAnswer() {
  const selected = document.querySelector('input[name="answer"]:checked');
  if (selected) {
    const questionId = questions[currentQuestionIndex].question_id;
    answersSelected[questionId] = parseInt(selected.value);
  }
}

// Navigasi tombol
nextButton.addEventListener("click", () => {
  saveAnswer();
  if (currentQuestionIndex < questions.length - 1) {
    currentQuestionIndex++;
    renderQuestion();
  }
});

backButton.addEventListener("click", () => {
  saveAnswer();
  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    renderQuestion();
  }
});

// // YANG PERLU DIUBAH: Bagian submit button
// submitButton.addEventListener("click", async () => {
//   if (!isLoggedIn()) {
//     alert("Sesi login telah habis. Silakan login kembali.");
//     window.location.href = "/loginregis.html";
//     return;
//   }

//   // Simpan jawaban terbaru dulu
//   saveAnswer();

//   if (Object.keys(answersSelected).length !== questions.length) {
//     alert("Harap jawab semua soal sebelum submit.");
//     return;
//   }

//   const user = getCurrentUser();
//   if (!user?.id) {
//     alert("User tidak ditemukan. Silakan login ulang.");
//     window.location.href = "/loginregis.html";
//     return;
//   }

//   // const userAnswers = Object.entries(answersSelected).map(
//   //   ([question_id, answer_id]) => ({
//   //     user_id: user.id,
//   //     question_id: parseInt(question_id),
//   //     answer_id: parseInt(answer_id),
//   //     skor_visual: skor.visual,
//   //     skor_auditory: skor.auditory,
//   //     skor_kinestetik: skor.kinestetik,

//   //   })
//   // );

//   const userAnswers = Object.entries(answersSelected).map(
//     ([question_id, answer_id]) => {
//       const skor = window.answerScores[answer_id] || {
//         visual: 0,
//         auditory: 0,
//         kinestetik: 0,
//       };
//       return {
//         user_id: user.id,
//         question_id: parseInt(question_id),
//         answer_id: parseInt(answer_id),
//         skor_visual: skor.visual,
//         skor_auditory: skor.auditory,
//         skor_kinestetik: skor.kinestetik,
//       };
//     }
//   );

//   if (!userAnswers || userAnswers.length === 0) {
//     alert("Jawaban tidak ditemukan. Silakan ulangi kuis.");
//     return;
//   }

//   console.log("User Answers: ", userAnswers);
//   console.log(
//     "Payload yang dikirim ke server:",
//     JSON.stringify(userAnswers, null, 2)
//   );

//   try {
//     const token = user?.access_token; // PERBAIKAN: Gunakan user yang sudah ada

//     const saveRes = await fetch("http://127.0.0.1:8000/api/user-answers", {
//       method: "POST",
//       headers: {
//         "Content-Type": "application/json",
//         Authorization: `Bearer ${token}`,
//       },
//       body: JSON.stringify(userAnswers),
//     });

//     if (!saveRes.ok) {
//       const err = await saveRes.json();
//       console.error("Gagal menyimpan:", err);
//       alert("Gagal menyimpan jawaban: " + (err.message || "Server error"));
//       return;
//     }

//     // PERBAIKAN: Tambahkan Authorization header untuk latest-result
//     const resultRes = await fetch("http://127.0.0.1:8000/api/results", {
//       method: "GET",
//       headers: {
//         "Content-Type": "application/json",
//         Authorization: `Bearer ${token}`, // TAMBAHKAN INI
//       },
//     });

//     if (!resultRes.ok) {
//       const err = await resultRes.json();
//       console.error("Gagal mengambil hasil:", err);
//       alert("Gagal mengambil hasil: " + (err.message || "Server error"));
//       return;
//     }

//     const result = await resultRes.json();
//     alert(`Hasil Gaya Belajar Kamu: ${result.result}`);
//   } catch (error) {
//     console.error("Terjadi kesalahan:", error);
//     alert("Terjadi kesalahan saat submit. Silakan coba lagi.");
//   }
// });


submitButton.addEventListener("click", async () => {
  if (!isLoggedIn()) {
    alert("Sesi login telah habis. Silakan login kembali.");
    window.location.href = "/loginregis.html";
    return;
  }

  saveAnswer();

  if (Object.keys(answersSelected).length !== questions.length) {
    alert("Harap jawab semua soal sebelum submit.");
    return;
  }

  const user = getCurrentUser();
  if (!user?.id) {
    alert("User tidak ditemukan. Silakan login ulang.");
    window.location.href = "/loginregis.html";
    return;
  }

  // 🔍 Hitung skor berdasarkan answer_id yang dipilih
  const userAnswers = Object.entries(answersSelected).map(([question_id, answer_id]) => {
    const skor = window.answerScores[answer_id]; // ⚠️ Ambil dari answer_id

    if (!skor) {
      console.warn(`Skor tidak ditemukan untuk answer_id: ${answer_id}`);
    }

    return {
      user_id: user.id,
      question_id: parseInt(question_id),
      answer_id: parseInt(answer_id),
      skor_visual: skor?.visual || 0,
      skor_auditory: skor?.auditory || 0,
      skor_kinestetik: skor?.kinestetik || 0,
    };
  });

  if (!userAnswers || userAnswers.length === 0) {
    alert("Jawaban tidak ditemukan. Silakan ulangi kuis.");
    return;
  }

  console.log("User Answers: ", userAnswers);
  console.log(
    "Payload yang dikirim ke server:",
    JSON.stringify(userAnswers, null, 2)
  );

  try {
    const token = user?.access_token;

    const saveRes = await fetch("http://127.0.0.1:8000/api/user-answers", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(userAnswers),
    });

    if (!saveRes.ok) {
      const errText = await saveRes.text();
      console.error("Gagal menyimpan:", errText);
      alert("Gagal menyimpan jawaban: " + errText);
      return;
    }

    const resultRes = await fetch("http://127.0.0.1:8000/api/results", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    if (!resultRes.ok) {
      const errText = await resultRes.text();
      console.error("Gagal mengambil hasil:", errText);
      alert("Gagal mengambil hasil: " + errText);
      return;
    }

    const result = await resultRes.json();
    alert(`Hasil Gaya Belajar Kamu: ${result.result}`);
  } catch (error) {
    console.error("Terjadi kesalahan saat submit:", error);
    alert("Terjadi kesalahan saat submit. Silakan coba lagi.");
  }
});