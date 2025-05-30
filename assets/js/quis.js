const startButton = document.getElementById("start-button");
const quizContainer = document.getElementById("quiz-container");
const questionText = document.getElementById("question-text");
const answersContainer = document.getElementById("answers-container");
const backButton = document.getElementById("back-button");
const nextButton = document.getElementById("next-button");
const submitButton = document.getElementById("submit-button");
const backButtonToHome = document.getElementById("back-button-beranda");

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

document.addEventListener("DOMContentLoaded", function () {
  if (startButton) {
    if (!isLoggedIn()) {
      Swal.fire({
        icon: "warning",
        title: "Oops!",
        text: "Anda harus login terlebih dahulu!",
      }).then(() => {
        window.location.href = "/assets/html/loginregis.html";
      });
      return;
    }

    if (backButtonToHome) {
      backButtonToHome.addEventListener("click", function () {
        window.location.href = "../../index.html";
      });
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

if (startButton) {
  startButton.addEventListener("click", async () => {
    if (!isLoggedIn()) {
      Swal.fire({
        icon: "warning",
        title: "Oops!",
        text: "Anda harus login terlebih dahulu!",
      }).then(() => {
        window.location.href = "/assets/html/loginregis.html";
      });
      return;
    }

    const instructionContainer = document.getElementById(
      "instruction-container"
    );
    if (instructionContainer) {
      instructionContainer.style.display = "none";
    }

    try {
      await loadQuestions();
      startButton.parentElement.style.display = "none";
      quizContainer.style.display = "block";
      renderQuestion();
    } catch (error) {
      console.error("Error starting quiz:", error);
      Swal.fire({
        icon: "error",
        title: "Gagal Memulai Kuis",
        text: "Gagal memuat soal kuis. Silakan coba lagi.",
      });
    }
  });
}

async function loadQuestions() {
  try {
    const user = getCurrentUser();
    const token = user?.access_token;

    const response = await fetch("http://127.0.0.1:8000/api/questions", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    const data = await response.json();
    questions = data;

    window.answerScores = {};
    questions.forEach((q) => {
      q.answers.forEach((a) => {
        window.answerScores[a.answer_id] = {
          visual: a.skor_visual || 0,
          auditory: a.skor_auditory || 0,
          kinestetik: a.skor_kinestetik || 0,
        };
      });
    });

    renderQuestion();
  } catch (error) {
    console.error("Gagal memuat soal:", error);
    Swal.fire({
      icon: "error",
      title: "Gagal Memuat Soal",
      text: "Silakan coba lagi.",
    });
  }
}

function renderQuestion() {
  const question = questions[currentQuestionIndex];
  questionText.textContent = `${currentQuestionIndex + 1}. ${
    question.question_text
  }`;
  answersContainer.innerHTML = "";

  const row = document.createElement("div");
  row.style.display = "flex";
  row.style.flexDirection = "column"; // Menumpuk ke bawah
  row.style.gap = "15px"; // Jarak antar pilihan

  question.answers.forEach((answer, index) => {
    const optionLabel = String.fromCharCode(65 + index); // A, B, C ...
    const label = document.createElement("label");

    label.style.display = "flex";
    label.style.alignItems = "center";
    label.style.gap = "10px";
    label.style.padding = "10px";
    label.style.border = "1px solid #ccc";
    label.style.borderRadius = "8px";
    label.style.cursor = "pointer";
    label.style.backgroundColor = "#f9f9f9";

    label.innerHTML = `
      <input type="radio" name="answer" value="${answer.answer_id}" ${
      answersSelected[question.question_id] === answer.answer_id
        ? "checked"
        : ""
    }>
      <strong>${optionLabel}.</strong> ${answer.answer_type}
    `;

    row.appendChild(label);
  });

  answersContainer.appendChild(row);

  backButton.style.display = currentQuestionIndex > 0 ? "inline-block" : "none";
  nextButton.style.display =
    currentQuestionIndex < questions.length - 1 ? "inline-block" : "none";
  submitButton.style.display =
    currentQuestionIndex === questions.length - 1 ? "inline-block" : "none";
}
function saveAnswer() {
  const selected = document.querySelector('input[name="answer"]:checked');
  if (selected) {
    const questionId = questions[currentQuestionIndex].question_id;
    answersSelected[questionId] = parseInt(selected.value);
  }
}

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

submitButton.addEventListener("click", async () => {
  saveAnswer();

  if (Object.keys(answersSelected).length !== questions.length) {
    Swal.fire({
      icon: "warning",
      title: "Harap Lengkapi!",
      text: "Harap jawab semua soal sebelum submit.",
    });
    return;
  }

  // Tampilkan alert proses
  Swal.fire({
    title: "Mengirim jawaban...",
    text: "Mohon tunggu sebentar",
    icon: "info",
    allowOutsideClick: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  const user = getCurrentUser();
  const token = user?.access_token;
  const userId = user?.id || user?.user_id;

  const userAnswers = Object.entries(answersSelected).map(
    ([question_id, answer_id]) => ({
      user_id: userId,
      question_id: parseInt(question_id),
      answer_id: parseInt(answer_id),
    })
  );

  const [response] = await Promise.all([
    fetch("http://127.0.0.1:8000/api/user-answers", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(userAnswers),
    }),
    new Promise((resolve) => setTimeout(resolve, 2000)), // delay minimal 2 detik
  ]);

  const result = await response.json();
  Swal.close(); // Tutup alert loading saat sudah mendapat hasil
  quizContainer.style.display = "none";
  const resultContainer = document.getElementById("result-container");
  resultContainer.style.display = "block";
  const skor = result.skor;
  const total = skor.total_visual + skor.total_auditory + skor.total_kinestetik;

  const visualPercent = ((skor.total_visual / total) * 100).toFixed(1);
  const auditoryPercent = ((skor.total_auditory / total) * 100).toFixed(1);
  const kinestetikPercent = ((skor.total_kinestetik / total) * 100).toFixed(1);

  resultContainer.innerHTML = `
  <div class="result-card">
    <h2>Hasil Gaya Belajar Kamu</h2>
    <p><strong>Tipe Gaya Belajar:</strong> ${result.result}</p>
    <p><strong>Skor:</strong></p>
    <ul>
      <li>Visual: ${visualPercent}%</li>
      <li>Auditory: ${auditoryPercent}%</li>
      <li>Kinestetik:  ${kinestetikPercent}%</li>
    </ul>
    <p><strong>Rekomendasi:</strong><br>${result.rekomendasi}</p>
    <br>
    <button id="back-to-home" style="padding: 10px 15px; margin-top: 10px;">Kembali ke Beranda</button>
  </div>
  `;

  document.getElementById("back-to-home").addEventListener("click", () => {
    window.location.href = "../../index.html";
  });
});
