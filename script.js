// ===============================
// DASHBOARD FUNCTIONALITY
// ===============================

// Global variables
let filteredAIData = [];

// ========== TAMBAHKAN SETELAH GLOBAL VARIABLES ==========

// Helper function untuk search dengan AI spesifik
async function searchAI(keyword) {
    console.log("Searching AI for:", keyword);
    const all = await getAllAI();
    
    const filtered = all.filter(ai => {
        const searchTerm = keyword.toLowerCase();
        const aiName = (ai.name || '').toLowerCase();
        const aiDesc = (ai.short_desc || '').toLowerCase();
        const aiCategory = (ai.category || '').toLowerCase();
        
        return aiName.includes(searchTerm) ||
               aiDesc.includes(searchTerm) ||
               aiCategory.includes(searchTerm);
    });
    
    console.log(`Found ${filtered.length} results for "${keyword}"`);
    return filtered;
}

// ========== PERBAIKI FILTER BY CATEGORY ==========
async function filterByCategory(category) {
    console.log("Filtering by category:", category);
    const all = await getAllAI();
    
    if (category === 'all') {
        filteredAIData = [...all];
        renderAIGrid(all, 'allAIGrid');
        
        // Update title
        const title = document.querySelector('#allAIGrid').closest('section').querySelector('h2');
        if (title) {
            title.innerHTML = `<i class="fas fa-th-large"></i> Semua AI (${all.length})`;
        }
        
    } else if (category === 'latest') {
        const latest = all.filter(ai => ai.is_latest);
        filteredAIData = latest;
        renderAIGrid(latest, 'allAIGrid');
        
        // Update title
        const title = document.querySelector('#allAIGrid').closest('section').querySelector('h2');
        if (title) {
            title.innerHTML = `<i class="fas fa-bolt"></i> AI Terbaru (${latest.length})`;
        }
        
    } else {
        // Filter berdasarkan kategori dengan logic yang lebih baik
        const filtered = all.filter(ai => {
            const aiCategory = (ai.category || '').toLowerCase();
            
            if (category === 'text') {
                return aiCategory.includes('text') || 
                       aiCategory.includes('chat') || 
                       ai.name.toLowerCase().includes('gpt') ||
                       ai.name.toLowerCase().includes('claude') ||
                       ai.name.toLowerCase().includes('gemini') ||
                       ai.name.toLowerCase().includes('bard');
            }
            
            if (category === 'image') {
                return aiCategory.includes('image') || 
                       aiCategory.includes('gambar') ||
                       ai.name.toLowerCase().includes('dall') ||
                       ai.name.toLowerCase().includes('midjourney');
            }
            
            if (category === 'video') {
                return aiCategory.includes('video') || 
                       ai.name.toLowerCase().includes('runway') ||
                       ai.name.toLowerCase().includes('pika');
            }
            
            if (category === 'audio') {
                return aiCategory.includes('audio') || 
                       aiCategory.includes('suara') ||
                       aiCategory.includes('music') ||
                       ai.name.toLowerCase().includes('elevenlabs') ||
                       ai.name.toLowerCase().includes('suno');
            }
            
            return aiCategory === category;
        });
        
        filteredAIData = filtered;
        renderAIGrid(filtered, 'allAIGrid');
        
        // Update title dengan nama kategori yang benar
        const categoryNames = {
            'text': 'Text AI',
            'image': 'Image AI', 
            'video': 'Video AI',
            'audio': 'Audio AI'
        };
        
        const title = document.querySelector('#allAIGrid').closest('section').querySelector('h2');
        if (title) {
            const categoryName = categoryNames[category] || category;
            title.innerHTML = `<i class="fas fa-${getCategoryIcon(category)}"></i> ${categoryName} (${filtered.length})`;
        }
    }
    
    // Show notification
    showCategoryNotification(category, filteredAIData.length);
}

// Helper untuk icon kategori
function getCategoryIcon(category) {
    const icons = {
        'all': 'th-large',
        'text': 'file-alt',
        'image': 'image',
        'video': 'video',
        'audio': 'volume-up',
        'latest': 'bolt'
    };
    return icons[category] || 'filter';
}

// Show notification untuk kategori
function showCategoryNotification(category, count) {
    const categoryNames = {
        'all': 'Semua AI',
        'text': 'Text AI',
        'image': 'Image AI',
        'video': 'Video AI',
        'audio': 'Audio AI',
        'latest': 'AI Terbaru'
    };
    
    const name = categoryNames[category] || category;
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'category-notification';
    notification.innerHTML = `
        <i class="fas fa-${getCategoryIcon(category)}"></i>
        <span>Menampilkan ${count} ${name}</span>
    `;
    
    // Style
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: var(--primary-color, #4361ee);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: slideInRight 0.3s ease;
        font-family: 'Poppins', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
    
    // Add animation styles jika belum ada
    if (!document.querySelector('#notification-animations')) {
        const style = document.createElement('style');
        style.id = 'notification-animations';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// ========== PERBAIKI SETUP EVENT LISTENERS (Search part) ==========
// Ganti bagian search di setupEventListeners() dengan ini:
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    let searchTimer;
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(async () => {
            const keyword = e.target.value.trim();
            console.log("Searching for:", keyword);
            
            if (keyword.length > 0) {
                const results = await searchAI(keyword);
                filteredAIData = results;
                renderAIGrid(results, 'allAIGrid');
                
                // Update title
                const title = document.querySelector('#allAIGrid').closest('section').querySelector('h2');
                if (title) {
                    title.innerHTML = `<i class="fas fa-search"></i> Hasil pencarian: "${keyword}" (${results.length})`;
                }
                
            } else {
                // Kembali ke semua AI
                const all = await getAllAI();
                filteredAIData = [...all];
                renderAIGrid(all, 'allAIGrid');
                
                // Update title
                const title = document.querySelector('#allAIGrid').closest('section').querySelector('h2');
                if (title) {
                    title.innerHTML = `<i class="fas fa-th-large"></i> Semua AI (${all.length})`;
                }
            }
        }, 300);
    });
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', async function() {
    console.log("Dashboard loaded");
    
    // Check authentication
    checkAuth();
    
    // Load user data
    loadUserData();
    
    // Setup event listeners
    setupEventListeners();
    
    // Initialize theme
    initTheme();
    
    // Load AI data
    await loadAIData();
    
    // Load latest AI
    await loadLatestAI();
});

// Load user data
function loadUserData() {
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    const userAvatar = document.getElementById('userAvatar');
    
    if (currentUser) {
        if (userName) {
            userName.textContent = currentUser.displayName || 'User Demo';
        }
        if (userEmail) {
            userEmail.textContent = currentUser.email || 'demo@aibijak.com';
        }
        
        if (userAvatar) {
            if (currentUser.photoURL) {
                userAvatar.innerHTML = `<img src="${currentUser.photoURL}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`;
            } else {
                const initial = currentUser.displayName?.charAt(0).toUpperCase() || 'U';
                userAvatar.textContent = initial;
                userAvatar.style.background = 'var(--gradient)';
                userAvatar.style.color = 'white';
                userAvatar.style.display = 'flex';
                userAvatar.style.alignItems = 'center';
                userAvatar.style.justifyContent = 'center';
                userAvatar.style.fontWeight = 'bold';
            }
        }
    } else {
        console.warn("No current user found");
    }
}

// Load all AI data
async function loadAIData() {
    try {
        console.log("Loading AI data...");
        const aiGrid = document.getElementById('allAIGrid');
        if (aiGrid) {
            aiGrid.innerHTML = '<div class="loading"><div class="spinner"></div><p>Memuat semua AI...</p></div>';
        }
        
        const data = await getAllAI();
        console.log("AI data loaded:", data.length, "items");
        filteredAIData = [...data];
        
        // Update stats
        const totalAI = document.getElementById('totalAI');
        const pdfCount = document.getElementById('pdfCount');
        if (totalAI) totalAI.textContent = `${data.length}+`;
        if (pdfCount) pdfCount.textContent = `${data.length}+`;
        
        // Render AI grid
        if (aiGrid) {
            renderAIGrid(data, 'allAIGrid');
        }
    } catch (error) {
        console.error('Error loading AI data:', error);
        const aiGrid = document.getElementById('allAIGrid');
        if (aiGrid) {
            aiGrid.innerHTML = '<div class="empty-state"><i class="fas fa-robot"></i><h3>Gagal memuat data</h3><button onclick="loadAIData()">Coba Lagi</button></div>';
        }
    }
}

// Load latest AI
async function loadLatestAI() {
    try {
        console.log("Loading latest AI...");
        const latestGrid = document.getElementById('latestAIGrid');
        if (latestGrid) {
            latestGrid.innerHTML = '<div class="loading"><div class="spinner"></div><p>Memuat AI terbaru...</p></div>';
        }
        
        const latestAI = await getLatestAI();
        console.log("Latest AI loaded:", latestAI.length, "items");
        
        // Update stats
        const latestCount = document.getElementById('latestAI');
        if (latestCount) latestCount.textContent = latestAI.length;
        
        // Render latest AI grid
        if (latestGrid) {
            renderAIGrid(latestAI, 'latestAIGrid');
        }
    } catch (error) {
        console.error('Error loading latest AI:', error);
    }
}

// Render AI grid
function renderAIGrid(aiList, containerId) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error("Container not found:", containerId);
        return;
    }
    
    if (!aiList || aiList.length === 0) {
        container.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><h3>Tidak ada AI ditemukan</h3><p>Coba kata kunci lain</p></div>';
        return;
    }
    
    container.innerHTML = '';
    
    aiList.forEach(ai => {
        const aiCard = createAICard(ai);
        container.appendChild(aiCard);
    });
    
    console.log("Rendered", aiList.length, "AI cards in", containerId);
}

// Create AI card element
function createAICard(ai) {
    const card = document.createElement('div');
    card.className = 'ai-card';
    card.dataset.id = ai.id;
    
    // Badge for latest AI
    const badge = ai.is_latest ? '<span class="ai-badge"><i class="fas fa-bolt"></i> Terbaru</span>' : '';
    
    // Escape single quotes untuk onclick
    const safeId = ai.id.replace(/'/g, "\\'");
    const safeName = ai.name.replace(/'/g, "\\'");
    
    card.innerHTML = `
        <div class="ai-header">
            <img src="${ai.logo_url}" alt="${ai.name}" class="ai-logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
            <div>
                <h3 class="ai-title">${ai.name}</h3>
                <div class="ai-category">${ai.category || 'General AI'}</div>
                ${badge}
            </div>
        </div>
        
        <p class="ai-description">${ai.short_desc || 'Deskripsi tidak tersedia'}</p>
        
        <div class="ai-footer">
            <button class="btn btn-primary" onclick="openAIDetail('${safeId}')">
                <i class="fas fa-info-circle"></i> Detail
            </button>
            <button class="btn btn-secondary" onclick="tryAI('${ai.try_link}')">
                <i class="fas fa-external-link-alt"></i> Coba
            </button>
        </div>
    `;
    
    // Tambah click event untuk seluruh card
    card.addEventListener('click', function(e) {
        if (!e.target.closest('button')) {
            openAIDetail(ai.id);
        }
    });
    
    return card;
}

// Open AI detail modal
async function openAIDetail(aiId) {
    console.log("Opening AI detail:", aiId);
    
    // Get all AI data
    const allAI = await getAllAI();
    const ai = allAI.find(a => a.id === aiId);
    
    if (!ai) {
        alert("AI tidak ditemukan");
        return;
    }
    
    const modal = document.getElementById('aiModal');
    const modalBody = document.getElementById('modalBody');
    
    if (!modal || !modalBody) {
        console.error("Modal elements not found");
        return;
    }
    
    modalBody.innerHTML = `
        <div class="modal-header">
            <img src="${ai.logo_url}" alt="${ai.name}" class="modal-logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
            <div class="modal-title">
                <h2>${ai.name}</h2>
                <div class="modal-category">${ai.category || 'General AI'} ${ai.is_latest ? '• <i class="fas fa-bolt"></i> Terbaru' : ''}</div>
            </div>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-info-circle"></i> Deskripsi Singkat</h3>
            <p>${ai.short_desc || 'Deskripsi tidak tersedia'}</p>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-graduation-cap"></i> Penjelasan untuk Pelajar</h3>
            <p>${ai.detailed_desc || `${ai.name} adalah alat kecerdasan buatan yang membantu dalam berbagai tugas. Cocok untuk pelajar SMP/SMA/SMK karena mudah digunakan dengan antarmuka yang ramah pengguna.`}</p>
        </div>
        
        <div class="modal-section">
            <h3><i class="fas fa-star"></i> Keunggulan</h3>
            <p>${ai.advantages || '• Mudah digunakan oleh pemula<br>• Hasil akurat dan cepat<br>• Gratis untuk kebutuhan pendidikan<br>• Mendukung bahasa Indonesia'}</p>
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-primary" onclick="tryAI('${ai.try_link}')">
                <i class="fas fa-external-link-alt"></i> Coba AI Sekarang
            </button>
            <button class="btn btn-secondary" onclick="downloadPDF('${ai.id}', '${ai.name}')">
                <i class="fas fa-download"></i> Download Modul PDF
            </button>
            <button class="btn btn-secondary" onclick="closeModal()">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    `;
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

// Close modal
function closeModal() {
    const modal = document.getElementById('aiModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
}

// Try AI (open external link)
function tryAI(url) {
    console.log("Trying AI:", url);
    if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
        window.open(url, '_blank', 'noopener,noreferrer');
    } else {
        alert('Link tidak tersedia untuk AI ini.');
    }
}

// Setup event listeners
function setupEventListeners() {
    console.log("Setting up event listeners");
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimer;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(async () => {
                const keyword = e.target.value.trim();
                console.log("Searching for:", keyword);
                if (keyword.length > 0) {
                    const results = await searchAI(keyword);
                    filteredAIData = results;
                    renderAIGrid(results, 'allAIGrid');
                } else {
                    const all = await getAllAI();
                    filteredAIData = [...all];
                    renderAIGrid(all, 'allAIGrid');
                }
            }, 300);
        });
    }
    
    // Category filter
    const categoryBtns = document.querySelectorAll('.category-btn');
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            console.log("Filter by category:", category);
            filterByCategory(category);
        });
    });
    
    // Modal close
    const modalClose = document.getElementById('modalClose');
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    
    const aiModal = document.getElementById('aiModal');
    if (aiModal) {
        aiModal.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    }
    
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log("Theme toggle clicked");
            toggleTheme();
        });
    }
    
    // User dropdown
    const userProfile = document.getElementById('userProfile');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userProfile && userDropdown) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log("User profile clicked");
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Logout button
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log("Logout clicked");
            logout();
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (userDropdown && userProfile && !userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.style.display = 'none';
        }
    });
    
    // Escape key closes modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            if (userDropdown) {
                userDropdown.style.display = 'none';
            }
        }
    });
    
    console.log("Event listeners setup complete");
}

// Filter by category
async function filterByCategory(category) {
    console.log("Filtering by category:", category);
    const all = await getAllAI();
    
    if (category === 'all') {
        filteredAIData = [...all];
        renderAIGrid(all, 'allAIGrid');
    } else if (category === 'latest') {
        const latest = all.filter(ai => ai.is_latest);
        filteredAIData = latest;
        renderAIGrid(latest, 'allAIGrid');
    } else {
        const filtered = all.filter(ai => ai.category === category);
        filteredAIData = filtered;
        renderAIGrid(filtered, 'allAIGrid');
    }
}

// Theme functions
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    console.log("Initializing theme:", savedTheme);
    
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
    
    console.log("Toggling theme from", currentTheme, "to", newTheme);
    
    if (newTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const icon = document.querySelector('#themeToggle i');
    if (icon) {
        if (theme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    }
}

// Export for HTML onclick
window.openAIDetail = openAIDetail;
window.tryAI = tryAI;
window.closeModal = closeModal;
window.downloadPDF = downloadPDF;
window.filterByCategory = filterByCategory;
window.toggleTheme = toggleTheme;
window.logout = logout;
window.loadAIData = loadAIData;

// Tambahkan ini di bagian paling bawah
window.searchAI = searchAI;

