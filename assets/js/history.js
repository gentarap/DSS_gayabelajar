function getCurrentUser() {
    try {
      const user = localStorage.getItem("user");
      return user ? JSON.parse(user) : null;
    } catch (e) {
      console.error('Error parsing user data:', e);
      return null;
    }
  }

  const user = getCurrentUser();
  const token = user?.access_token;
  const userId = user?.id || user?.user_id;

  function loadHistory() {
    const historyContainer = document.getElementById("history-container");
    
    if (!userId || !token) {
      historyContainer.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">🔒</div>
          <h3>Akses Terbatas</h3>
          <p>Silakan login terlebih dahulu untuk melihat riwayat kuis Anda.</p>
        </div>
      `;
      return;
    }

    // Show loading state
    historyContainer.innerHTML = `
      <div class="loading">
        <div class="spinner"></div>
        <p>Memuat riwayat kuis...</p>
      </div>
    `;

    // Fetch data from API
    fetch(`http://127.0.0.1:8000/api/results/history/${userId}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      if (data.length === 0) {
        historyContainer.innerHTML = `
          <div class="empty-state">
            <div class="empty-icon">📝</div>
            <h3>Belum Ada Riwayat</h3>
            <p>Anda belum pernah mengikuti kuis. Mulai kuis pertama Anda sekarang!</p>
          </div>
        `;
        return;
      }

      historyContainer.innerHTML = '<div class="history-grid"></div>';
      const grid = historyContainer.querySelector('.history-grid');
      
      data.forEach((result, index) => {
        const card = document.createElement("div");
        card.classList.add("history-card");
        
        const date = new Date(result.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });

        card.innerHTML = `
          <div class="card-header">
            <div class="card-number">${index + 1}</div>
            <div class="card-date">${formattedDate}</div>
          </div>
          <div class="learning-style">
            Gaya Belajar: ${result.style.gaya_belajar}
          </div>
          <button class="detail-button" onclick="showDetail(${result.persentase.visual}, ${result.persentase.auditory}, ${result.persentase.kinestetik}, '${result.style.gaya_belajar}')">
            Lihat Detail Skor
          </button>
        `;
        
        grid.appendChild(card);
      });
    })
    .catch((error) => {
      console.error('Error fetching history:', error);
      historyContainer.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">⚠️</div>
          <h3>Terjadi Kesalahan</h3>
          <p>Gagal memuat riwayat kuis. Silakan coba lagi nanti.</p>
          <button class="detail-button" onclick="loadHistory()" style="margin-top: 1rem; max-width: 200px;">
            Coba Lagi
          </button>
        </div>
      `;
    });
  }

  function showDetail(visual, auditory, kinestetik, dominantStyle) {
    const modal = document.getElementById("modal");
    const detailSkor = document.getElementById("detail-skor");
    
    // Learning styles data
    const styles = {
      'Visual': {
        'gaya_belajar': 'Visual',
        'deskripsi': 'Pelajar visual lebih mudah memahami informasi melalui gambar, diagram, dan representasi visual lainnya.',
        'rekomendasi': 'Gunakan mind mapping, flashcards, diagram, video edukatif, dan catatan berwarna. Buat ringkasan dengan bullet points dan gambar ilustrasi.',
        'icon': '👁️'
      },
      'Auditory': {
        'gaya_belajar': 'Auditory',
        'deskripsi': 'Pelajar auditori lebih mudah memahami informasi melalui mendengar dan berbicara.',
        'rekomendasi': 'Dengarkan podcast, rekam materi pelajaran, diskusi kelompok, baca keras-keras, dan gunakan musik untuk membantu konsensentrasi.',
        'icon': '🎧'
      },
      'Kinestetik': {
        'gaya_belajar': 'Kinestetik',
        'deskripsi': 'Pelajar kinestetik lebih mudah belajar melalui gerakan, sentuhan, dan praktik langsung.',
        'rekomendasi': 'Lakukan eksperimen, praktik langsung, belajar sambil berjalan, gunakan manipulatif, dan ambil banyak istirahat untuk bergerak.',
        'icon': '🤸'
      }
    };

    // Determine which score is highest if dominantStyle is not provided
    if (!dominantStyle) {
      const scores = { Visual: visual, Auditory: auditory, Kinestetik: kinestetik };
      dominantStyle = Object.keys(scores).reduce((a, b) => scores[a] > scores[b] ? a : b);
    }

    const currentStyle = styles[dominantStyle];
    
    detailSkor.innerHTML = `
      <div class="score-grid">
        <div class="score-item ${visual >= auditory && visual >= kinestetik ? 'highest-score' : ''}">
          <span class="score-label">👁️ Visual</span>
          <span class="score-value">${visual}%</span>
        </div>
        <div class="score-item ${auditory >= visual && auditory >= kinestetik ? 'highest-score' : ''}">
          <span class="score-label">🎧 Auditory</span>
          <span class="score-value">${auditory}%</span>
        </div>
        <div class="score-item ${kinestetik >= visual && kinestetik >= auditory ? 'highest-score' : ''}">
          <span class="score-label">🤸 Kinestetik</span>
          <span class="score-value">${kinestetik}%</span>
        </div>
      </div>
      
      <div class="learning-style-detail">
        <div class="style-title">
          <span class="style-icon">${currentStyle.icon}</span>
          Gaya Belajar: ${currentStyle.gaya_belajar}
        </div>
        
        <div class="style-description">
          ${currentStyle.deskripsi}
        </div>
        
        <div class="recommendations">
          <h4>💡 Rekomendasi Belajar</h4>
          <p>${currentStyle.rekomendasi}</p>
        </div>
      </div>
    `;
    
    modal.classList.add("show");
  }

  function closeModal() {
    const modal = document.getElementById("modal");
    modal.classList.remove("show");
  }

  // Event listeners
  document.getElementById("close-modal").onclick = closeModal;
  
  document.getElementById("modal").onclick = function(e) {
    if (e.target === this) {
      closeModal();
    }
  };

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeModal();
    }
  });

  // Load history on page load
  loadHistory();