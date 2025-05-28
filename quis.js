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

const answerScores = {
  1: { visual: 1, auditory: 0, kinestetik: 0 },
  2: { visual: 0, auditory: 1, kinestetik: 0 },
  3: { visual: 0, auditory: 1, kinestetik: 0 },
  4: { visual: 1, auditory: 0, kinestetik: 0 },
  // Tambahkan skor lainnya sesuai kebutuhan
};

// MULAI KUIS
startButton.addEventListener("click", async () => {
  await loadQuestions();
  startButton.parentElement.style.display = "none";
  quizContainer.style.display = "block";
  renderQuestion();
});

async function loadQuestions() {
  const response = await fetch("http://127.0.0.1:8000/api/questions");
  questions = await response.json();
  credentials: "include"
}

function renderQuestion() {
  const question = questions[currentQuestionIndex];
  questionText.textContent = `${currentQuestionIndex + 1}. ${question.question_text}`;
  answersContainer.innerHTML = "";

  question.answers.forEach((answer) => {
    const label = document.createElement("label");
    label.innerHTML = `
      <input type="radio" name="answer" value="${answer.answer_id}" ${
      answersSelected[question.question_id] == answer.answer_id ? "checked" : ""
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

function saveAnswer() {
  const selected = document.querySelector('input[name="answer"]:checked');
  if (selected) {
    const questionId = questions[currentQuestionIndex].question_id;
    answersSelected[questionId] = parseInt(selected.value);
  }
}

// TOMBOL NEXT
nextButton.addEventListener("click", () => {
  saveAnswer();
  if (currentQuestionIndex < questions.length - 1) {
    currentQuestionIndex++;
    renderQuestion();
  }
});

// TOMBOL BACK
backButton.addEventListener("click", () => {
  saveAnswer();
  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    renderQuestion();
  }
});

// TOMBOL SUBMIT
submitButton.addEventListener("click", async () => {
  saveAnswer();
  const userId = 1; // Ganti sesuai sistem login-mu

  const userAnswers = Object.entries(answersSelected).map(
    ([question_id, answer_id]) => {
      const skor = answerScores[answer_id] || {
        visual: 0,
        auditory: 0,
        kinestetik: 0,
      };
      return {
        user_id: userId,
        question_id: parseInt(question_id),
        answer_id: parseInt(answer_id),
        skor_visual: skor.visual,
        skor_auditory: skor.auditory,
        skor_kinestetik: skor.kinestetik,
      };
    }
  );

  try {
    const saveRes = await fetch("http://127.0.0.1:8000/api/user-answers", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(userAnswers),
      credentials: "include",
    });

    if (!saveRes.ok) {
      const err = await saveRes.json();
      console.error("Gagal menyimpan:", err);
      alert("Gagal menyimpan jawaban: " + (err.message || ""));
      return;
    }

    const resultRes = await fetch("http://127.0.0.1:8000/api/latest-result", {
      credentials: "include",
    });

    if (!resultRes.ok) {
      const err = await resultRes.json();
      console.error("Gagal mengambil hasil:", err);
      alert("Gagal mengambil hasil: " + (err.message || ""));
      return;
    }

    const result = await resultRes.json();
    alert(`Hasil Gaya Belajar Kamu: ${result.result}`);

    // Redirect atau tampilkan hasil
    // window.location.href = `/hasil.html?result=${result.result}`;
  } catch (error) {
    console.error("Unexpected error:", error);
    alert("Terjadi kesalahan jaringan atau server.");
  }
});


// // ==================== QUIZ FUNCTIONALITY ====================
// const startButton = document.getElementById("start-button");
// const quizContainer = document.getElementById("quiz-container");
// const questionText = document.getElementById("question-text");
// const answersContainer = document.getElementById("answers-container");
// const backButton = document.getElementById("back-button");
// const nextButton = document.getElementById("next-button");
// const submitButton = document.getElementById("submit-button");

// let questions = [];
// let currentQuestionIndex = 0;
// const answersSelected = {};
// let currentUser = null;

// const answerScores = {
//   1: { visual: 1, auditory: 0, kinestetik: 0 },
//   2: { visual: 0, auditory: 1, kinestetik: 0 },
//   3: { visual: 0, auditory: 1, kinestetik: 0 },
//   4: { visual: 1, auditory: 0, kinestetik: 0 },
//   // Tambahkan skor lainnya sesuai kebutuhan
// };

// // Check login status saat halaman dimuat
// document.addEventListener("DOMContentLoaded", async function() {
//   if (startButton) {
//     const loginStatus = await isLoggedIn();
//     if (!loginStatus) {
//       alert("Anda harus login terlebih dahulu!");
//       window.location.href = "/loginregis.html";
//       return;
//     }
    
//     // Ambil info user yang sedang login
//     currentUser = await getCurrentUser();
//     if (currentUser) {
//       console.log("Current user:", currentUser);
//       // Tampilkan nama user jika ada element untuk itu
//       const userNameElement = document.getElementById("user-name");
//       if (userNameElement) {
//         userNameElement.textContent = currentUser.name || currentUser.email;
//       }
//     }
//   }
// });

// // MULAI KUIS
// if (startButton) {
//   startButton.addEventListener("click", async () => {
//     const loginStatus = await isLoggedIn();
//     if (!loginStatus) {
//       alert("Anda harus login terlebih dahulu!");
//       window.location.href = "/loginregis.html";
//       return;
//     }

//     try {
//       await loadQuestions();
//       startButton.parentElement.style.display = "none";
//       quizContainer.style.display = "block";
//       renderQuestion();
//     } catch (error) {
//       console.error("Error starting quiz:", error);
//       alert("Gagal memuat soal quiz. Silakan coba lagi.");
//     }
//   });
// }

// async function loadQuestions() {
//   try {
//     const response = await makeSessionRequest("http://127.0.0.1:8000/api/questions");
    
//     if (!response.ok) {
//       if (response.status === 401) {
//         alert("Sesi login Anda telah berakhir. Silakan login kembali.");
//         window.location.href = "/loginregis.html";
//         return;
//       }
//       throw new Error(`HTTP error! status: ${response.status}`);
//     }
    
//     questions = await response.json();
//   } catch (error) {
//     console.error("Error loading questions:", error);
//     throw error;
//   }
// }

// function renderQuestion() {
//   if (!questions || questions.length === 0) {
//     alert("Tidak ada soal yang tersedia.");
//     return;
//   }

//   const question = questions[currentQuestionIndex];
//   questionText.textContent = `${currentQuestionIndex + 1}. ${question.question_text}`;
//   answersContainer.innerHTML = "";

//   question.answers.forEach((answer) => {
//     const label = document.createElement("label");
//     label.innerHTML = `
//       <input type="radio" name="answer" value="${answer.answer_id}" ${
//       answersSelected[question.question_id] == answer.answer_id ? "checked" : ""
//     }>
//       ${answer.answer_type}
//     `;
//     answersContainer.appendChild(label);
//   });

//   // Show/hide buttons
//   if (backButton) {
//     backButton.style.display = currentQuestionIndex > 0 ? "inline-block" : "none";
//   }
//   if (nextButton) {
//     nextButton.style.display =
//       currentQuestionIndex < questions.length - 1 ? "inline-block" : "none";
//   }
//   if (submitButton) {
//     submitButton.style.display =
//       currentQuestionIndex === questions.length - 1 ? "inline-block" : "none";
//   }
// }

// function saveAnswer() {
//   const selected = document.querySelector('input[name="answer"]:checked');
//   if (selected) {
//     const questionId = questions[currentQuestionIndex].question_id;
//     answersSelected[questionId] = parseInt(selected.value);
//   }
// }

// // TOMBOL NEXT
// if (nextButton) {
//   nextButton.addEventListener("click", () => {
//     saveAnswer();
//     if (currentQuestionIndex < questions.length - 1) {
//       currentQuestionIndex++;
//       renderQuestion();
//     }
//   });
// }

// // TOMBOL BACK
// if (backButton) {
//   backButton.addEventListener("click", () => {
//     saveAnswer();
//     if (currentQuestionIndex > 0) {
//       currentQuestionIndex--;
//       renderQuestion();
//     }
//   });
// }

// // TOMBOL SUBMIT
// if (submitButton) {
//   submitButton.addEventListener("click", async () => {
//     // Check login status sebelum submit
//     const loginStatus = await isLoggedIn();
//     if (!loginStatus) {
//       alert("Sesi login Anda telah berakhir. Silakan login kembali.");
//       window.location.href = "/loginregis.html";
//       return;
//     }

//     saveAnswer();

//     // Pastikan kita punya info user
//     if (!currentUser) {
//       currentUser = await getCurrentUser();
//     }

//     if (!currentUser || !currentUser.id) {
//       alert("Tidak dapat mengidentifikasi user. Silakan login kembali.");
//       window.location.href = "/loginregis.html";
//       return;
//     }

//     const userAnswers = Object.entries(answersSelected).map(
//       ([question_id, answer_id]) => {
//         const skor = answerScores[answer_id] || {
//           visual: 0,
//           auditory: 0,
//           kinestetik: 0,
//         };
//         return {
//           user_id: currentUser.id,
//           question_id: parseInt(question_id),
//           answer_id: parseInt(answer_id),
//           skor_visual: skor.visual,
//           skor_auditory: skor.auditory,
//           skor_kinestetik: skor.kinestetik,
//         };
//       }
//     );

//     try {
//       // Submit jawaban
//       const saveRes = await makeSessionRequest(
//         "http://127.0.0.1:8000/api/user-answers",
//         {
//           method: "POST",
//           body: JSON.stringify(userAnswers),
//         }
//       );

//       if (!saveRes.ok) {
//         if (saveRes.status === 401) {
//           alert("Sesi login Anda telah berakhir. Silakan login kembali.");
//           window.location.href = "/loginregis.html";
//           return;
//         }
//         const err = await saveRes.json();
//         console.error("Gagal menyimpan:", err);
//         alert("Gagal menyimpan jawaban: " + (err.message || "Server error"));
//         return;
//       }

//       const saveResult = await saveRes.json();
//       console.log("Save result:", saveResult);

//       // Ambil hasil
//       const resultRes = await makeSessionRequest(
//         "http://127.0.0.1:8000/api/latest-result"
//       );

//       if (!resultRes.ok) {
//         if (resultRes.status === 401) {
//           alert("Sesi login Anda telah berakhir. Silakan login kembali.");
//           window.location.href = "/loginregis.html";
//           return;
//         }
//         const err = await resultRes.json();
//         console.error("Gagal mengambil hasil:", err);
//         alert("Gagal mengambil hasil: " + (err.message || "Server error"));
//         return;
//       }

//       const result = await resultRes.json();
//       alert(`Hasil Gaya Belajar Kamu: ${result.result}`);

//       // Optional: Redirect ke halaman hasil
//       // window.location.href = `/hasil.html?result=${result.result}`;
//     } catch (error) {
//       console.error("Unexpected error:", error);
//       alert("Terjadi kesalahan jaringan atau server. Silakan coba lagi.");
//     }
//   });
// }