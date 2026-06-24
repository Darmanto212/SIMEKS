// Database Pengetahuan Sederhana (Keyword Matching)
const knowledgeBase = [
    {
        keywords: ["halo", "hai", "selamat", "pagi", "siang", "sore", "malam"],
        answer: "Halo! Selamat datang di SIMEKS SMAN 2 Sukatani. Ada yang bisa saya bantu?"
    },
    {
        keywords: ["daftar", "gabung", "registrasi", "cara"],
        answer: "Untuk mendaftar ekskul, silakan Login terlebih dahulu, lalu pilih menu 'Daftar Ekskul' di Dashboard Siswa."
    },
    {
        keywords: ["jadwal", "latihan", "kapan", "jam"],
        answer: "Jadwal latihan setiap ekskul berbeda. Anda bisa melihat detailnya di halaman 'Jadwal' atau di Dashboard setelah login."
    },
    {
        keywords: ["lokasi", "alamat", "tempat"],
        answer: "Sekretariat OSIS dan Ekskul berada di Gedung B Lantai 1, SMAN 2 Sukatani."
    },
    {
        keywords: ["paskibra", "pramuka", "futsal", "basket", "rohis"],
        answer: "Itu adalah salah satu ekskul unggulan kami! Silakan cek menu 'Daftar Ekskul' untuk melihat profil lengkapnya."
    },
    {
        keywords: ["syarat", "ketentuan"],
        answer: "Syarat umum: Siswa aktif SMAN 2 Sukatani dan berkomitmen mengikuti latihan. Syarat khusus bisa dilihat di detail masing-masing ekskul."
    }
];

// Fungsi Mengirim Pesan
function sendMessage() {
    const inputField = document.getElementById("chat-input");
    const messageContainer = document.getElementById("chat-messages");
    const userText = inputField.value.trim().toLowerCase();

    if (userText === "") return;

    // 1. Tampilkan Pesan User
    const userDiv = document.createElement("div");
    userDiv.className = "text-end mb-2";
    userDiv.innerHTML = `<span class="bg-light text-dark p-2 rounded d-inline-block border">${inputField.value}</span>`;
    messageContainer.appendChild(userDiv);

    // Bersihkan input
    inputField.value = "";

    // Auto scroll ke bawah
    messageContainer.scrollTop = messageContainer.scrollHeight;

    // 2. Cari Jawaban (Logic AI Sederhana)
    setTimeout(() => {
        let botResponse = "Maaf, saya belum mengerti pertanyaan itu. Silakan hubungi Admin OSIS atau datang ke Sekretariat.";

        // Cek setiap item di knowledgeBase
        for (let item of knowledgeBase) {
            // Cek apakah ada keyword yang cocok
            if (item.keywords.some(keyword => userText.includes(keyword))) {
                botResponse = item.answer;
                break;
            }
        }

        // 3. Tampilkan Pesan Bot
        const botDiv = document.createElement("div");
        botDiv.className = "text-start mb-2";
        botDiv.innerHTML = `<span class="bg-maroon text-white p-2 rounded d-inline-block">${botResponse}</span>`;
        messageContainer.appendChild(botDiv);

        // Auto scroll ke bawah lagi
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }, 500); // Delay sedikit biar terlihat "mikir"
}

// Kirim pesan dengan tombol Enter
document.getElementById("chat-input").addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        sendMessage();
    }
});