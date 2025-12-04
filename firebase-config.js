// FULL MOCK MODE - TANPA FIREBASE
// Untuk PKM Presentasi Saja

console.log("🔥 MOCK MODE AKTIF - Semua fitur jalan tanpa Firebase!");

// Global variables
let currentUser = {
    uid: 'mock-user-123',
    displayName: 'Bro PKM',
    email: 'bro@aibijak.com',
    photoURL: 'https://ui-avatars.com/api/?name=Bro+PKM&background=6366f1&color=fff'
};

let allAIData = [];

// Sample AI Data (50+ AI untuk demo)
const sampleAIData = [
    {
        id: "1",
        name: "ChatGPT",
        logo_url: "https://cdn-icons-png.flaticon.com/512/6134/6134346.png",
        short_desc: "AI untuk obrolan dan bantuan PR. Bisa bantu ngerjain tugas sekolah!",
        category: "text",
        is_latest: false,
        try_link: "https://chat.openai.com",
        detailed_desc: "ChatGPT adalah AI dari OpenAI yang bisa membantu pelajar dalam: 1. Menjawab pertanyaan pelajaran 2. Menulis esai dan laporan 3. Menerjemahkan bahasa 4. Menjelaskan konsep sulit. Cocok untuk SMP/SMA!",
        advantages: "• Gratis versi dasar<br>• Mudah digunakan<br>• Bisa berbahasa Indonesia<br>• Cocok untuk semua mata pelajaran"
    },
    {
        id: "2",
        name: "Gemini",
        logo_url: "https://cdn-icons-png.flaticon.com/512/8205/8205983.png",
        short_desc: "AI cerdas dari Google, bisa teks dan gambar sekaligus.",
        category: "text",
        is_latest: true,
        try_link: "https://gemini.google.com",
        detailed_desc: "Gemini AI dari Google bisa bantu: 1. Analisis gambar (upload foto soal) 2. Coding sederhana 3. Riset tugas sekolah 4. Brainstorming ide. Integrasi dengan Google Docs!",
        advantages: "• Integrasi Google<br>• Multi-modal (teks+gambar)<br>• Akurat untuk riset<br>• Gratis pakai akun Google"
    },
    {
        id: "3",
        name: "Midjourney",
        logo_url: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        short_desc: "Buat gambar keren dari deskripsi teks. Untuk tugas seni!",
        category: "image",
        is_latest: false,
        try_link: "https://midjourney.com",
        detailed_desc: "Midjourney bisa bantu pelajar: 1. Buat ilustrasi untuk presentasi 2. Desain poster tugas 3. Visualisasi konsep pelajaran 4. Gambar kreatif untuk seni budaya.",
        advantages: "• Hasil gambar realistis<br>• Banyak style pilihan<br>• Cocok untuk tugas seni<br>• Bisa eksperimen kreatif"
    },
    {
        id: "4",
        name: "Claude AI",
        logo_url: "https://cdn-icons-png.flaticon.com/512/8205/8205983.png",
        short_desc: "AI dengan fokus etika, aman untuk pelajar.",
        category: "text",
        is_latest: true,
        try_link: "https://claude.ai",
        detailed_desc: "Claude AI dari Anthropic: 1. Aman dan etis untuk pelajar 2. Bisa baca file PDF/Word 3. Konteks panjang untuk esai 4. Jawaban detail dan mendalam.",
        advantages: "• Aman untuk anak sekolah<br>• Bisa upload dokumen<br>• Jawaban detail<br>• Fokus edukasi"
    },
    {
        id: "5",
        name: "DALL-E 3",
        logo_url: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        short_desc: "AI gambar dari OpenAI, hasilnya detail banget!",
        category: "image",
        is_latest: true,
        try_link: "https://openai.com/dall-e-3",
        detailed_desc: "DALL-E 3 spesialis gambar: 1. Hasil gambar high-quality 2. Paham deskripsi detail 3. Cocok untuk tugas desain 4. Integrasi dengan ChatGPT.",
        advantages: "• Kualitas gambar tinggi<br>• Paham konteks kompleks<br>• Cocok untuk desain<br>• Hasil profesional"
    },
    {
        id: "6",
        name: "Stable Diffusion",
        logo_url: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        short_desc: "AI gambar open source, bisa di komputer sendiri.",
        category: "image",
        is_latest: false,
        try_link: "https://stability.ai",
        detailed_desc: "Stable Diffusion: 1. Bisa dijalankan lokal (offline) 2. Customizable tinggi 3. Cocok untuk eksperimen 4. Gratis dan open source.",
        advantages: "• Open source gratis<br>• Customizable<br>• Bisa offline<br>• Cocok untuk eksplorasi"
    },
    {
        id: "7",
        name: "Suno AI",
        logo_url: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        short_desc: "Buat musik dari teks. Untuk tugas seni musik!",
        category: "audio",
        is_latest: true,
        try_link: "https://suno.ai",
        detailed_desc: "Suno AI bisa: 1. Generate musik dari deskripsi 2. Buat jingle untuk presentasi 3. Eksperimen dengan genre musik 4. Bantu tugas seni musik.",
        advantages: "• Buat musik custom<br>• Banyak genre<br>• Cocok untuk seni musik<br>• Hasil kreatif"
    },
    {
        id: "8",
        name: "Runway ML",
        logo_url: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
        short_desc: "AI untuk edit video dan efek khusus.",
        category: "video",
        is_latest: true,
        try_link: "https://runwayml.com",
        detailed_desc: "Runway ML tools: 1. Edit video dengan AI 2. Tambah efek khusus 3. Hapus background 4. Generate video dari teks.",
        advantages: "• Edit video mudah<br>• Efek keren<br>• Cocok untuk multimedia<br>• Tools lengkap"
    },
    {
        id: "9",
        name: "Notion AI",
        logo_url: "https://cdn-icons-png.flaticon.com/512/6134/6134346.png",
        short_desc: "AI untuk organisasi belajar dan catatan.",
        category: "text",
        is_latest: false,
        try_link: "https://notion.so",
        detailed_desc: "Notion AI bantu: 1. Atur jadwal belajar 2. Buat catatan pelajaran 3. Ringkas materi 4. Rencanakan project sekolah.",
        advantages: "• Organisasi belajar<br>• Catatan terstruktur<br>• Kolaborasi kelompok<br>• Gratis untuk pelajar"
    },
    {
        id: "10",
        name: "QuillBot",
        logo_url: "https://cdn-icons-png.flaticon.com/512/6134/6134346.png",
        short_desc: "Parafrase dan perbaiki tulisan bahasa Inggris.",
        category: "text",
        is_latest: false,
        try_link: "https://quillbot.com",
        detailed_desc: "QuillBot tools: 1. Parafrase kalimat 2. Cek grammar 3. Summarize teks 4. Improve writing skill.",
        advantages: "• Tingkatkan writing<br>• Cek grammar<br>• Cocok untuk bahasa Inggris<br>• Gratis versi dasar"
    }
];

// ===============================
// AUTHENTICATION FUNCTIONS (MOCK)
// ===============================
function loginWithGoogle() {
    console.log("🔐 Login dengan Google (MOCK)");
    
    // Show loading
    const loginBtn = document.querySelector('.google-login-btn');
    if (loginBtn) {
        const originalHTML = loginBtn.innerHTML;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses login...';
        loginBtn.disabled = true;
        
        // Simulate login process
        setTimeout(() => {
            // Set user
            currentUser = {
                uid: 'pkm-user-' + Date.now(),
                displayName: 'Pelajar PKM',
                email: 'pelajar@aibijak.com',
                photoURL: 'https://ui-avatars.com/api/?name=Pelajar+PKM&background=8b5cf6&color=fff'
            };
            
            // Success message
            alert("🎉 Login berhasil! Selamat datang di AI Bijak.");
            
            // Redirect to dashboard
            window.location.href = "dashboard.html";
            
            // Restore button (if still on login page)
            setTimeout(() => {
                loginBtn.innerHTML = originalHTML;
                loginBtn.disabled = false;
            }, 500);
        }, 1500);
    } else {
        // Fallback
        currentUser = {
            displayName: 'Demo User',
            email: 'demo@aibijak.com'
        };
        setTimeout(() => window.location.href = "dashboard.html", 500);
    }
}

function logout() {
    console.log("🚪 Logout (MOCK)");
    currentUser = null;
    
    // Show loading
    document.body.style.opacity = '0.7';
    
    setTimeout(() => {
        window.location.href = "login.html";
    }, 500);
}

function checkAuth() {
    console.log("🔒 Checking auth status...");
    
    const path = window.location.pathname;
    const isLoginPage = path.includes("login.html");
    const isIndexPage = path.endsWith("index.html") || path.endsWith("/");
    
    // Untuk demo PKM, selalu anggap user sudah login jika di dashboard
    if (currentUser) {
        if (isLoginPage || isIndexPage) {
            setTimeout(() => {
                window.location.href = "dashboard.html";
            }, 1000);
        }
    } else {
        if (!isLoginPage && !isIndexPage) {
            setTimeout(() => {
                window.location.href = "login.html";
            }, 500);
        }
    }
}

// ===============================
// AI DATA FUNCTIONS (MOCK)
// ===============================
async function getAllAI() {
    console.log("🤖 Mengambil data AI...");
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 300));
    
    allAIData = [...sampleAIData];
    return allAIData;
}

async function getLatestAI() {
    const all = await getAllAI();
    return all.filter(ai => ai.is_latest);
}

async function searchAI(keyword) {
    console.log("🔍 Searching AI:", keyword);
    
    await new Promise(resolve => setTimeout(resolve, 200));
    
    const all = await getAllAI();
    if (!keyword) return all;
    
    return all.filter(ai => 
        ai.name.toLowerCase().includes(keyword.toLowerCase()) ||
        ai.category.toLowerCase().includes(keyword.toLowerCase()) ||
        ai.short_desc.toLowerCase().includes(keyword.toLowerCase())
    );
}

// ===============================
// THEME FUNCTIONS
// ===============================
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    console.log("🎨 Initial theme:", savedTheme);
    
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    updateThemeIcon(savedTheme);
}

function toggleTheme() {
    const currentTheme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    console.log("🔄 Toggle theme:", currentTheme, "→", newTheme);
    
    if (newTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
    
    // Show theme change notification
    showNotification(`Mode ${newTheme === 'dark' ? 'Gelap' : 'Terang'} diaktifkan`);
}

function updateThemeIcon(theme) {
    const icons = document.querySelectorAll('#themeToggle i, .theme-toggle i');
    icons.forEach(icon => {
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    });
}

// ===============================
// PDF GENERATOR (MOCK)
// ===============================
async function downloadPDF(aiId, aiName) {
    console.log("📥 Download PDF untuk:", aiName);
    
    try {
        // Get AI data
        const allAI = await getAllAI();
        const ai = allAI.find(a => a.id === aiId);
        
        if (!ai) {
            showNotification("Data AI tidak ditemukan", "error");
            return;
        }
        
        // Show loading on button
        const event = window.event;
        if (event && event.target) {
            const originalHTML = event.target.innerHTML;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat PDF...';
            event.target.disabled = true;
            
            // Simulate PDF generation
            setTimeout(() => {
                // Create a mock PDF download
                const pdfContent = `
                    MODUL PEMBELAJARAN AI BIJAK
                    ============================
                    
                    Nama AI: ${ai.name}
                    Kategori: ${ai.category}
                    
                    📚 Deskripsi:
                    ${ai.short_desc}
                    
                    🎓 Penjelasan untuk Pelajar:
                    ${ai.detailed_desc}
                    
                    ⭐ Keunggulan:
                    ${ai.advantages.replace(/<br>/g, '\n')}
                    
                    🔗 Link untuk Mencoba:
                    ${ai.try_link}
                    
                    📋 Cara Menggunakan:
                    1. Buka link di atas
                    2. Daftar akun gratis (jika perlu)
                    3. Mulai gunakan untuk tugas sekolah
                    4. Eksplor fitur-fitur yang ada
                    
                    💡 Tips untuk Pelajar:
                    • Gunakan untuk bantu memahami konsep sulit
                    • Jadikan alat bantu, bukan pengganti belajar
                    • Selalu cross-check informasi
                    • Gunakan secara bertanggung jawab
                    
                    © 2025 AI Bijak - Platform Edukasi AI untuk Pelajar Indonesia
                `;
                
                // Create download
                const blob = new Blob([pdfContent], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Modul_${aiName.replace(/\s+/g, '_')}.txt`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                // Restore button
                event.target.innerHTML = originalHTML;
                event.target.disabled = false;
                
                // Show success message
                showNotification(`Modul "${aiName}" berhasil diunduh!`);
                
            }, 1500);
        }
    } catch (error) {
        console.error("PDF error:", error);
        showNotification("Gagal membuat PDF", "error");
    }
}

// ===============================
// NOTIFICATION SYSTEM
// ===============================
function showNotification(message, type = "success") {
    // Remove existing notifications
    const existing = document.querySelectorAll('.notification');
    existing.forEach(n => n.remove());
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 99999;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
        font-family: 'Poppins', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS animations
if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// ===============================
// EXPORT FUNCTIONS FOR HTML
// ===============================
window.loginWithGoogle = loginWithGoogle;
window.logout = logout;
window.toggleTheme = toggleTheme;
window.checkAuth = checkAuth;
window.getAllAI = getAllAI;
window.getLatestAI = getLatestAI;
window.searchAI = searchAI;
window.downloadPDF = downloadPDF;
window.showNotification = showNotification;

// Initialize on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log("🚀 AI Bijak Mock Mode Loaded!");
        initTheme();
        checkAuth();
    });
} else {
    console.log("🚀 AI Bijak Mock Mode Loaded!");
    initTheme();
    checkAuth();
}

// ===============================
// USER DROPDOWN & PROFILE FUNCTIONS
// ===============================

function setupUserDropdown() {
    const userProfile = document.getElementById('userProfile');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userProfile && userDropdown) {
        console.log("👤 Setting up user dropdown...");
        
        // Toggle dropdown ketika klik profil
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log("Profil diklik!");
            
            if (userDropdown.style.display === 'block') {
                userDropdown.style.display = 'none';
            } else {
                userDropdown.style.display = 'block';
                
                // Position dropdown
                const rect = userProfile.getBoundingClientRect();
                userDropdown.style.position = 'fixed';
                userDropdown.style.top = (rect.bottom + 5) + 'px';
                userDropdown.style.right = (window.innerWidth - rect.right) + 'px';
            }
        });
        
        // Close dropdown ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.style.display = 'none';
            }
        });
        
        // Close dropdown dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                userDropdown.style.display = 'none';
            }
        });
    } else {
        console.warn("Element user dropdown tidak ditemukan");
    }
}

// Update user info di dashboard
function updateUserInfo() {
    console.log("🔄 Updating user info...");
    
    if (currentUser) {
        const userName = document.getElementById('userName');
        const userEmail = document.getElementById('userEmail');
        const userAvatar = document.getElementById('userAvatar');
        
        if (userName) userName.textContent = currentUser.displayName || 'Bro PKM';
        if (userEmail) userEmail.textContent = currentUser.email || 'bro@aibijak.com';
        
        if (userAvatar) {
            // Set avatar initial atau gambar
            if (currentUser.photoURL) {
                userAvatar.innerHTML = `<img src="${currentUser.photoURL}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`;
            } else {
                const initial = (currentUser.displayName || 'BP').charAt(0).toUpperCase();
                userAvatar.textContent = initial;
                userAvatar.style.background = 'var(--gradient)';
                userAvatar.style.color = 'white';
                userAvatar.style.display = 'flex';
                userAvatar.style.alignItems = 'center';
                userAvatar.style.justifyContent = 'center';
                userAvatar.style.fontWeight = 'bold';
                userAvatar.style.fontSize = '18px';
            }
        }
        
        // Setup dropdown setelah user info terload
        setTimeout(setupUserDropdown, 100);
    }
}

// Initialize user functions
function initUser() {
    console.log("👤 Initializing user...");
    updateUserInfo();
    setupUserDropdown();
}

// ===============================
// ENHANCED LOGIN WITH GOOGLE (MOCK)
// ===============================
function enhancedLoginWithGoogle() {
    console.log("🔐 Enhanced login dengan Google...");
    
    // Cari semua tombol login
    const loginBtns = document.querySelectorAll('.google-login-btn, .btn-primary, button[onclick*="login"], button:contains("Login")');
    
    loginBtns.forEach(btn => {
        if (btn.textContent.includes('Login') || btn.textContent.includes('Masuk')) {
            const originalHTML = btn.innerHTML;
            const originalOnClick = btn.onclick;
            
            // Update button state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
            btn.style.opacity = '0.8';
            
            // Reset setelah 2 detik
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                btn.style.opacity = '1';
                
                // Panggil login function
                loginWithGoogle();
            }, 1500);
        }
    });
    
    // Fallback
    if (loginBtns.length === 0) {
        loginWithGoogle();
    }
}

// ===============================
// UPDATE LOGIN FUNCTION
// ===============================
const originalLoginWithGoogle = window.loginWithGoogle;
window.loginWithGoogle = function() {
    console.log("🎯 Login dengan Google dipanggil");
    
    // Update user data untuk demo
    currentUser = {
        uid: 'pkm-demo-' + Date.now(),
        displayName: 'Bro PKM',
        email: 'bro@aibijak.com',
        photoURL: 'https://ui-avatars.com/api/?name=Bro+PKM&background=6366f1&color=fff&size=128'
    };
    
    // Show success animation
    const loginPage = document.querySelector('.login-page');
    if (loginPage) {
        loginPage.style.opacity = '0.9';
        loginPage.style.transition = 'opacity 0.5s';
    }
    
    // Simulate login process
    setTimeout(() => {
        showNotification("🎉 Login berhasil! Selamat datang Bro PKM!", "success");
        
        // Redirect ke dashboard
        setTimeout(() => {
            window.location.href = "dashboard.html";
        }, 1000);
    }, 1000);
};

// ===============================
// ENHANCED LOGOUT
// ===============================
function enhancedLogout() {
    console.log("🚪 Enhanced logout...");
    
    // Show confirmation
    if (confirm("Yakin ingin logout dari AI Bijak?")) {
        // Show loading
        document.body.style.opacity = '0.7';
        document.body.style.transition = 'opacity 0.3s';
        
        // Show logout message
        showNotification("👋 Sampai jumpa, Bro PKM!", "info");
        
        // Logout setelah delay
        setTimeout(() => {
            currentUser = null;
            localStorage.removeItem('theme'); // Optional: clear theme
            window.location.href = "login.html";
        }, 800);
    }
}

// Update global logout function
window.logout = enhancedLogout;

// ===============================
// AUTO INIT ON PAGE LOAD
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 AI Bijak Enhanced Loaded!");
    
    // Initialize theme
    initTheme();
    
    // Check auth status
    checkAuth();
    
    // Initialize user jika di dashboard
    if (window.location.pathname.includes('dashboard.html')) {
        initUser();
    }
    
    // Replace login buttons dengan enhanced version
    const loginButtons = document.querySelectorAll('button[onclick*="loginWithGoogle"]');
    loginButtons.forEach(btn => {
        btn.onclick = enhancedLoginWithGoogle;
    });
});

// Export new functions
window.setupUserDropdown = setupUserDropdown;
window.updateUserInfo = updateUserInfo;
window.initUser = initUser;
window.enhancedLoginWithGoogle = enhancedLoginWithGoogle;