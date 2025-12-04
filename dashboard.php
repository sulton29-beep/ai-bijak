<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$userName = $_SESSION['username'] ?? 'User';
$userEmail = $_SESSION['email'] ?? 'user@example.com';
$userRole = $_SESSION['role'] ?? 'siswa';
$noHp = $_SESSION['no_hp'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Bijak - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Include jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --accent: #00ff88;
            --neon-blue: #00d9ff;
            --neon-pink: #ff00ff;
            --bg-color: #0a0a1a;
            --text-color: #ffffff;
            --card-bg: rgba(20, 20, 40, 0.7);
            --border-color: rgba(100, 126, 234, 0.3);
            --glow: 0 0 20px rgba(0, 255, 136, 0.5);
        }

        .light-theme {
            --bg-color: #f0f2ff;
            --text-color: #1a1a2e;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: rgba(100, 126, 234, 0.2);
            --glow: 0 0 20px rgba(100, 126, 234, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Futuristic Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .grid-lines {
            position: absolute;
            top: 0;
            left: 0;
            width: 200%;
            height: 200%;
            background: 
                linear-gradient(90deg, transparent 95%, rgba(0, 217, 255, 0.1) 100%),
                linear-gradient(0deg, transparent 95%, rgba(0, 217, 255, 0.1) 100%);
            background-size: 50px 50px;
            animation: moveGrid 20s linear infinite;
            transform: rotate(15deg);
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .floating-element {
            position: absolute;
            background: radial-gradient(circle, var(--neon-blue) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(20px);
            opacity: 0.3;
            animation: float 15s infinite ease-in-out;
        }

        /* Navbar Futuristic */
        .navbar {
            background: rgba(10, 10, 26, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 255, 136, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.1);
        }

        .light-theme .navbar {
            background: rgba(240, 242, 255, 0.9);
            border-bottom: 1px solid rgba(100, 126, 234, 0.2);
            box-shadow: 0 0 30px rgba(100, 126, 234, 0.1);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-color);
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .nav-logo i {
            color: var(--accent);
            font-size: 2rem;
            filter: drop-shadow(0 0 10px var(--accent));
        }

        .gradient-text {
            background: linear-gradient(45deg, var(--accent), var(--neon-blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 900;
            text-shadow: 0 0 15px rgba(0, 255, 136, 0.3);
        }

        /* Search Bar */
        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 2rem;
            position: relative;
        }

        .search-bar {
            width: 100%;
            padding: 0.8rem 1.5rem 0.8rem 3rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 30px;
            color: var(--text-color);
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .search-bar:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--accent);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
        }

        .search-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 1.2rem;
        }

        .nav-center {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-btn {
            background: transparent;
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--text-color);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .nav-btn:hover {
            background: rgba(0, 255, 136, 0.1);
            border-color: var(--accent);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.3);
            transform: translateY(-2px);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.3);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.3rem;
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .theme-toggle:hover {
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.5);
            transform: rotate(180deg) scale(1.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            transition: all 0.3s;
            background: rgba(20, 20, 40, 0.5);
            border: 1px solid rgba(0, 217, 255, 0.2);
        }

        .light-theme .user-profile {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(100, 126, 234, 0.2);
        }

        .user-profile:hover {
            background: rgba(0, 217, 255, 0.1);
            border-color: var(--neon-blue);
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.3);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--accent), var(--neon-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
        }

        /* Hero Section */
        .hero {
            background: rgba(10, 10, 26, 0.5);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 30px;
            padding: 3rem 2rem;
            margin: 2rem auto;
            max-width: 1400px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.1);
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            line-height: 1.1;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero h1 .user-name {
            background: linear-gradient(45deg, var(--accent), var(--neon-blue), var(--neon-pink));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
        }

        .hero p {
            font-size: 1.1rem;
            max-width: 800px;
            margin-bottom: 2rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 1rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--glow);
            border-color: var(--accent);
        }

        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent);
            filter: drop-shadow(0 0 10px var(--accent));
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(45deg, var(--accent), var(--neon-blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* AI Grid */
        .ai-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            padding: 0 1rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .ai-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
            position: relative;
        }

        .ai-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--glow);
            border-color: var(--accent);
        }

        .ai-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .ai-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 0 20px;
        }

        .ai-card-body {
            padding: 1.5rem;
        }

        .ai-card-footer {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 0.8rem;
            border-top: 1px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            padding: 1rem;
        }

        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.2);
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.3);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.3rem;
            color: #ff4444;
            z-index: 10;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(255, 0, 0, 0.2);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
            transform: rotate(90deg);
        }

        /* User Dropdown */
        .user-dropdown {
            position: absolute;
            top: 70px;
            right: 20px;
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            min-width: 220px;
            overflow: hidden;
            z-index: 1001;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
            display: none;
            animation: dropdownAppear 0.3s ease-out;
        }

        .dropdown-item {
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            color: var(--text-color);
            transition: all 0.3s;
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-item:hover {
            background: rgba(0, 255, 136, 0.1);
            padding-left: 1.5rem;
        }

        /* Profile Page */
        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: var(--glow);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, var(--accent), var(--neon-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            font-weight: 700;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.5);
        }

        .profile-info h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            background: rgba(0, 255, 136, 0.9);
            color: #0a0a1a;
            font-weight: 600;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease-out;
            font-family: 'Poppins', sans-serif;
        }

        /* Animations */
        @keyframes moveGrid {
            0% { transform: translate(0, 0) rotate(15deg); }
            100% { transform: translate(-50px, -50px) rotate(15deg); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @keyframes dropdownAppear {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .search-container {
                max-width: 300px;
                margin: 0 1rem;
            }
        }

        @media (max-width: 992px) {
            .navbar {
                flex-wrap: wrap;
                gap: 1rem;
            }
            .search-container {
                order: 3;
                flex: 100%;
                max-width: 100%;
                margin: 0.5rem 0 0 0;
            }
            .nav-center {
                overflow-x: auto;
                padding-bottom: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .ai-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .nav-center {
                display: none;
            }
            .mobile-menu-btn {
                display: block !important;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--text-color);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.3rem;
            align-items: center;
            justify-content: center;
        }

        .mobile-filter-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--card-bg);
            border-top: 1px solid var(--border-color);
            padding: 1rem;
            display: none;
            flex-wrap: wrap;
            gap: 0.5rem;
            z-index: 999;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <!-- Futuristic Background -->
    <div class="bg-animation">
        <div class="grid-lines"></div>
        <div class="floating-elements">
            <div class="floating-element" style="width: 300px; height: 300px; top: 10%; left: 10%; animation-delay: 0s;"></div>
            <div class="floating-element" style="width: 200px; height: 200px; top: 60%; right: 15%; background: radial-gradient(circle, var(--neon-pink) 0%, transparent 70%); animation-delay: 5s;"></div>
            <div class="floating-element" style="width: 150px; height: 150px; bottom: 20%; left: 20%; background: radial-gradient(circle, var(--accent) 0%, transparent 70%); animation-delay: 10s;"></div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-logo" onclick="window.location.href='dashboard.php'">
            <i class="fas fa-robot"></i>
            <span>AI <span class="gradient-text">BIJAK</span></span>
        </div>
        
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-bar" id="searchBar" placeholder="Cari AI tools, modul, atau kategori..." onkeyup="searchAI()">
        </div>
        
        <div class="nav-center">
            <button class="nav-btn" onclick="filterAI('all')">
                <i class="fas fa-th-large"></i> Semua
            </button>
            <button class="nav-btn" onclick="filterAI('text')">
                <i class="fas fa-font"></i> Text AI
            </button>
            <button class="nav-btn" onclick="filterAI('image')">
                <i class="fas fa-image"></i> Image AI
            </button>
            <button class="nav-btn" onclick="filterAI('audio')">
                <i class="fas fa-music"></i> Audio AI
            </button>
            <button class="nav-btn" onclick="filterAI('video')">
                <i class="fas fa-video"></i> Video AI
            </button>
            <button class="nav-btn" onclick="filterAI('code')">
                <i class="fas fa-code"></i> Code AI
            </button>
        </div>
        
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="fas fa-filter"></i>
        </button>
        
        <div class="nav-right">
            <button class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
                <i class="fas fa-moon"></i>
            </button>
            
            <div class="user-profile" id="userProfile">
                <div class="user-avatar" id="userAvatar"><?php echo strtoupper(substr($userName, 0, 1)); ?></div>
                <div style="text-align: right;">
                    <div style="font-weight: 700; font-size: 0.9rem;"><?php echo htmlspecialchars($userName); ?></div>
                    <div style="font-size: 0.7rem; opacity: 0.7; font-weight: 300;"><?php echo $userRole == 'guru' ? '🎓 Guru' : '👨‍🎓 Siswa'; ?></div>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </nav>

    <!-- Mobile Filter Menu -->
    <div class="mobile-filter-menu" id="mobileFilterMenu">
        <button class="nav-btn" onclick="filterAI('all')">
            <i class="fas fa-th-large"></i> Semua
        </button>
        <button class="nav-btn" onclick="filterAI('text')">
            <i class="fas fa-font"></i> Text
        </button>
        <button class="nav-btn" onclick="filterAI('image')">
            <i class="fas fa-image"></i> Image
        </button>
        <button class="nav-btn" onclick="filterAI('audio')">
            <i class="fas fa-music"></i> Audio
        </button>
        <button class="nav-btn" onclick="filterAI('video')">
            <i class="fas fa-video"></i> Video
        </button>
        <button class="nav-btn" onclick="filterAI('code')">
            <i class="fas fa-code"></i> Code
        </button>
    </div>

    <!-- User Dropdown -->
    <div class="user-dropdown" id="userDropdown">
        <div class="dropdown-item" onclick="showProfile()">
            <i class="fas fa-user-circle"></i>
            <span>Profile Saya</span>
        </div>
        <div class="dropdown-item" onclick="toggleTheme()">
            <i class="fas fa-palette"></i>
            <span>Ganti Tema</span>
        </div>
        <div class="dropdown-item" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal-overlay" id="profileModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeProfile()">
                <i class="fas fa-times"></i>
            </button>
            <div style="padding: 2rem;">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($userName, 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($userName); ?></h2>
                        <p style="color: var(--text-color); opacity: 0.8;">
                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($userEmail); ?><br>
                            <i class="fas fa-user-tag"></i> <?php echo $userRole == 'guru' ? '🎓 Guru/Pengajar' : '👨‍🎓 Siswa/Pelajar'; ?><br>
                            <i class="fas fa-calendar"></i> Bergabung: <?php echo date('d F Y'); ?>
                        </p>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--text-color); margin-bottom: 1rem; font-family: 'Orbitron', sans-serif;">
                        <i class="fas fa-cog"></i> Pengaturan Akun
                    </h3>
                    <div style="background: rgba(255,255,255,0.05); border-radius: 15px; padding: 1.5rem;">
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-color); opacity: 0.8;">
                                <i class="fas fa-user"></i> Nama Lengkap
                            </label>
                            <input type="text" value="<?php echo htmlspecialchars($userName); ?>" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.1); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-color);">
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-color); opacity: 0.8;">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" value="<?php echo htmlspecialchars($userEmail); ?>" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.1); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-color);">
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-color); opacity: 0.8;">
                                <i class="fas fa-key"></i> Ganti Password
                            </label>
                            <input type="password" placeholder="Password baru" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.1); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-color); margin-bottom: 0.5rem;">
                            <input type="password" placeholder="Konfirmasi password" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.1); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-color);">
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <h3 style="color: var(--text-color); margin-bottom: 1rem; font-family: 'Orbitron', sans-serif;">
                        <i class="fas fa-chart-line"></i> Statistik Aktivitas
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                        <div style="background: rgba(0,255,136,0.1); border: 1px solid rgba(0,255,136,0.3); border-radius: 10px; padding: 1rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--accent);">15+</div>
                            <div style="font-size: 0.9rem; color: var(--text-color);">AI Tools Diakses</div>
                        </div>
                        <div style="background: rgba(0,217,255,0.1); border: 1px solid rgba(0,217,255,0.3); border-radius: 10px; padding: 1rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--neon-blue);">8</div>
                            <div style="font-size: 0.9rem; color: var(--text-color);">PDF Diunduh</div>
                        </div>
                        <div style="background: rgba(255,0,255,0.1); border: 1px solid rgba(255,0,255,0.3); border-radius: 10px; padding: 1rem; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 800; color: var(--neon-pink);">30+</div>
                            <div style="font-size: 0.9rem; color: var(--text-color);">Sesi Belajar</div>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button onclick="updateProfile()" style="flex: 1; background: var(--accent); color: white; border: none; padding: 1rem; border-radius: 10px; cursor: pointer; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button onclick="closeProfile()" style="flex: 1; background: transparent; color: var(--text-color); border: 1px solid var(--border-color); padding: 1rem; border-radius: 10px; cursor: pointer; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1rem;">
        <div class="hero">
            <h1>Selamat Datang, <span class="user-name"><?php echo htmlspecialchars($userName); ?>!</span></h1>
            <p>
                Jelajahi kecerdasan buatan terdepan untuk pembelajaran. Temukan 15+ alat AI yang mengubah cara belajar menjadi pengalaman luar biasa.
            </p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-microchip"></i>
                    <div class="stat-number" id="totalAI">15+</div>
                    <div style="font-size: 1rem; font-weight: 600;">AI Tools</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-file-pdf"></i>
                    <div class="stat-number">15+</div>
                    <div style="font-size: 1rem; font-weight: 600;">PDF Modul</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-number">50+</div>
                    <div style="font-size: 1rem; font-weight: 600;">Pengguna</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-graduation-cap"></i>
                    <div class="stat-number"><?php echo $userRole == 'guru' ? 'Guru' : 'Siswa'; ?></div>
                    <div style="font-size: 1rem; font-weight: 600;">Role Anda</div>
                </div>
            </div>
        </div>

        <!-- AI Grid -->
        <h2 style="font-size: 2rem; margin: 3rem 0 1.5rem; color: var(--text-color); font-family: 'Orbitron', sans-serif; text-align: center;">
            <i class="fas fa-robot" style="color: var(--accent); margin-right: 0.8rem;"></i> 
            TEMUKAN AI TOOLS
        </h2>
        
        <div class="ai-grid" id="aiGrid">
            <!-- AI cards will be loaded here -->
        </div>
    </div>

    <!-- AI Detail Modal -->
    <div class="modal-overlay" id="aiModal">
        <div class="modal-content">
            <button class="modal-close" id="modalClose">
                <i class="fas fa-times"></i>
            </button>
            <div id="modalBody" style="padding: 2rem;">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Data AI lengkap dengan 15+ tools termasuk Audio AI
        const aiData = [
            {
                id: 1,
                name: "ChatGPT",
                category: "text",
                description: "AI chatbot untuk percakapan dan tanya jawab cerdas",
                fullDescription: "ChatGPT adalah model bahasa AI revolusioner dari OpenAI yang mengubah cara kita berinteraksi dengan teknologi. Dengan kemampuan memahami konteks dan memberikan respons alami, alat ini menjadi asisten digital yang sempurna untuk pelajar.",
                sejarah: "ChatGPT pertama kali dirilis oleh OpenAI pada November 2022. Dikembangkan berdasarkan arsitektur GPT (Generative Pre-trained Transformer) yang telah melalui beberapa iterasi sejak GPT-1 tahun 2018. ChatGPT-4 dirilis pada Maret 2023 dengan kemampuan multimodal.",
                tahunPembuatan: "2022",
                founder: "OpenAI (Sam Altman, Ilya Sutskever)",
                keunggulanTeknis: [
                    "Architecture: Transformer-based",
                    "Parameters: 175 billion (GPT-3.5)",
                    "Training Data: 570GB text",
                    "Languages: 50+ termasuk Indonesia",
                    "Context Window: 4096 tokens"
                ],
                useCases: [
                    "Pembelajaran bahasa",
                    "Bantuan mengerjakan tugas",
                    "Brainstorming ide",
                    "Penulisan kreatif",
                    "Analisis teks"
                ],
                advantages: [
                    "Gratis untuk versi dasar (GPT-3.5)",
                    "Mendukung 50+ bahasa termasuk Indonesia",
                    "Dapat membantu mengerjakan tugas sekolah",
                    "Respons akurat dengan konteks panjang",
                    "Integrasi dengan DALL-E untuk gambar"
                ],
                howToUse: "1. Kunjungi chat.openai.com\n2. Buat akun gratis\n3. Tanyakan apa saja di chatbox\n4. Gunakan untuk belajar dan eksplorasi",
                website: "https://chat.openai.com",
                icon: "fas fa-comment-dots",
                color: "#10b981"
            },
            {
                id: 2,
                name: "Claude AI",
                category: "text",
                description: "AI assistant dengan fokus keamanan dan etika tinggi",
                fullDescription: "Claude AI dikembangkan oleh Anthropic dengan prinsip Constitutional AI yang memprioritaskan keselamatan. Cocok untuk pendidikan karena outputnya selalu aman, edukatif, dan bermanfaat untuk perkembangan pelajar.",
                sejarah: "Claude pertama kali diperkenalkan oleh Anthropic pada Maret 2023. Perusahaan didirikan oleh mantan peneliti OpenAI dengan fokus pada keamanan AI. Claude 2 dirilis pada Juli 2023 dengan peningkatan signifikan.",
                tahunPembuatan: "2023",
                founder: "Anthropic (Dario Amodei, Daniela Amodei)",
                keunggulanTeknis: [
                    "Constitutional AI architecture",
                    "Context: 200K tokens",
                    "Safety-focused training",
                    "Document understanding",
                    "Harmless outputs"
                ],
                useCases: [
                    "Analisis dokumen panjang",
                    "Penelitian akademik",
                    "Penulisan etis",
                    "Tanya jawab kompleks",
                    "Summarisasi"
                ],
                advantages: [
                    "Keamanan dan etika terjamin",
                    "Konteks hingga 200K token",
                    "Pemahaman dokumen lengkap",
                    "Gratis untuk penggunaan dasar",
                    "Output ramah pelajar"
                ],
                howToUse: "1. Daftar di claude.ai\n2. Upload dokumen (PDF, Word)\n3. Ajukan pertanyaan kompleks\n4. Dapatkan analisis mendalam",
                website: "https://claude.ai",
                icon: "fas fa-shield-alt",
                color: "#8b5cf6"
            },
            {
                id: 3,
                name: "DALL-E 3",
                category: "image",
                description: "Generator gambar AI dari deskripsi teks",
                fullDescription: "DALL-E 3 adalah teknologi terdepan dalam generasi gambar AI. Ubah imajinasi menjadi visual menakjubkan untuk presentasi, materi belajar, atau ekspresi kreatif. Kualitas gambar setara seniman profesional.",
                sejarah: "DALL-E pertama kali diumumkan OpenAI pada Januari 2021. Nama berasal dari gabungan Salvador Dalí dan robot WALL-E. DALL-E 2 dirilis April 2022, dan DALL-E 3 dirilis Oktober 2023 dengan integrasi ChatGPT.",
                tahunPembuatan: "2021 (versi pertama)",
                founder: "OpenAI",
                keunggulanTeknis: [
                    "Diffusion model architecture",
                    "1024x1024 resolution",
                    "Prompt understanding",
                    "Style consistency",
                    "Safe image generation"
                ],
                useCases: [
                    "Materi pembelajaran visual",
                    "Presentasi kreatif",
                    "Ilustrasi buku",
                    "Desain poster",
                    "Mockup produk"
                ],
                advantages: [
                    "Kualitas gambar HD/4K",
                    "Pemahaman prompt detail",
                    "Gaya artistik beragam",
                    "Integrasi dengan ChatGPT",
                    "Cocok untuk desain edukasi"
                ],
                howToUse: "1. Akses melalui ChatGPT Plus\n2. Deskripsikan gambar yang diinginkan\n3. Sesuaikan style dan aspek rasio\n4. Download hasil dalam berbagai format",
                website: "https://openai.com/dall-e-3",
                icon: "fas fa-palette",
                color: "#f59e0b"
            },
            {
                id: 4,
                name: "Midjourney",
                category: "image",
                description: "AI art generator dengan kualitas seni tinggi",
                fullDescription: "Midjourney mengkhususkan diri dalam seni digital dan ilustrasi. Dengan model yang terlatih pada jutaan karya seni, ia dapat menghasilkan gambar dengan estetika yang mengagumkan untuk proyek kreatif.",
                sejarah: "Midjourney diluncurkan pada Juli 2022 oleh Midjourney, Inc. yang didirikan oleh David Holz. Awalnya hanya tersedia melalui Discord, kini telah berkembang menjadi platform independen dengan komunitas artistik besar.",
                tahunPembuatan: "2022",
                founder: "David Holz (Midjourney, Inc.)",
                keunggulanTeknis: [
                    "Art-focused training data",
                    "V5.2 model (latest)",
                    "Advanced stylize parameters",
                    "Upscale hingga 4K",
                    "Community-driven development"
                ],
                useCases: [
                    "Seni digital",
                    "Konsep karakter game",
                    "Ilustrasi buku",
                    "Wallpaper artistik",
                    "NFT creation"
                ],
                advantages: [
                    "Kualitas seni terbaik",
                    "Style konsisten",
                    "Komunitas artistik besar",
                    "Parameter tuning lengkap",
                    "Upscale hingga 4K"
                ],
                howToUse: "1. Join Discord Midjourney\n2. Gunakan perintah /imagine\n3. Tulis prompt artistik\n4. Pilih dan upscale hasil terbaik",
                website: "https://midjourney.com",
                icon: "fas fa-paint-brush",
                color: "#ec4899"
            },
            {
                id: 5,
                name: "Google Gemini",
                category: "text",
                description: "AI multimodal canggih dari Google",
                fullDescription: "Gemini adalah model AI terbaru Google yang dapat memahami teks, gambar, audio, dan video secara bersamaan. Dirancang untuk membantu dalam penelitian, analisis data, dan pembelajaran interaktif.",
                sejarah: "Gemini diumumkan Google pada Desember 2023 sebagai penerus Bard. Dikembangkan oleh Google DeepMind dengan tiga versi: Nano, Pro, dan Ultra. Merupakan jawaban Google terhadap ChatGPT dan Claude.",
                tahunPembuatan: "2023",
                founder: "Google DeepMind",
                keunggulanTeknis: [
                    "Native multimodal architecture",
                    "1.6 trillion parameters (Ultra)",
                    "Real-time processing",
                    "Google Search integration",
                    "Code generation"
                ],
                useCases: [
                    "Penelitian multimodal",
                    "Analisis data kompleks",
                    "Pemrograman asistif",
                    "Belajar interaktif",
                    "Content creation"
                ],
                advantages: [
                    "Multimodal (teks+gambar+audio)",
                    "Integrasi dengan Google Workspace",
                    "Gratis dengan akun Google",
                    "Pemrosesan dokumen real-time",
                    "Koding dan analisis data"
                ],
                howToUse: "1. Kunjungi gemini.google.com\n2. Login dengan akun Google\n3. Upload file atau tanyakan langsung\n4. Dapatkan analisis multimodal",
                website: "https://gemini.google.com",
                icon: "fab fa-google",
                color: "#4285f4"
            },
            // AUDIO AI
            {
                id: 6,
                name: "Suno AI",
                category: "audio",
                description: "Generator musik AI dari teks ke lagu lengkap",
                fullDescription: "Suno AI mengubah deskripsi menjadi musik lengkap dengan vokal, instrumen, dan aransemen profesional. Cocok untuk pelajaran seni musik, pembuatan konten edukasi, atau eksplorasi kreatif.",
                sejarah: "Suno AI diluncurkan pada tahun 2023 oleh Suno, Inc. Startup ini fokus pada generasi musik AI yang dapat menghasilkan lagu original dari deskripsi teks sederhana.",
                tahunPembuatan: "2023",
                founder: "Suno, Inc.",
                keunggulanTeknis: [
                    "Text-to-song generation",
                    "Full production quality",
                    "Vocal synthesis",
                    "Multiple genres",
                    "Custom duration"
                ],
                useCases: [
                    "Pembelajaran musik",
                    "Soundtrack edukasi",
                    "Konten multimedia",
                    "Ekspresi kreatif",
                    "Proyek sekolah"
                ],
                advantages: [
                    "Buat musik dari teks",
                    "Berbagai genre musik",
                    "Vokal AI natural",
                    "Custom durasi dan tempo",
                    "Ekspor format MP3/WAV"
                ],
                howToUse: "1. Daftar di suno.com\n2. Tulis deskripsi lagu\n3. Pilih genre dan mood\n4. Generate dan download musik",
                website: "https://suno.com",
                icon: "fas fa-music",
                color: "#3b82f6"
            },
            {
                id: 7,
                name: "ElevenLabs",
                category: "audio",
                description: "Text-to-speech AI dengan suara natural",
                fullDescription: "ElevenLabs menghasilkan suara AI yang hampir tidak bisa dibedakan dari manusia. Mendukung banyak bahasa dan emosi, cocok untuk narasi, podcast, atau konten audio edukasi.",
                sejarah: "ElevenLabs didirikan pada 2022 oleh mantan peneliti Google dan membuka akses publik pada 2023. Fokus pada synthesizers suara yang sangat natural.",
                tahunPembuatan: "2022",
                founder: "ElevenLabs (ex-Google researchers)",
                keunggulanTeknis: [
                    "Neural voice synthesis",
                    "Emotional speech",
                    "30+ language models",
                    "Voice cloning",
                    "Low latency"
                ],
                useCases: [
                    "Narasi buku audio",
                    "Podcast edukasi",
                    "Presentasi dengan suara",
                    "Konten aksesibilitas",
                    "Bahasa asing"
                ],
                advantages: [
                    "Suara natural manusia",
                    "30+ bahasa support",
                    "Emotional voice cloning",
                    "Long-form audio",
                    "Commercial use allowed"
                ],
                howToUse: "1. Daftar di elevenlabs.io\n2. Pilih voice model\n3. Input text untuk dikonversi\n4. Download audio MP3",
                website: "https://elevenlabs.io",
                icon: "fas fa-volume-up",
                color: "#8b5cf6"
            },
            {
                id: 8,
                name: "Whisper AI",
                category: "audio",
                description: "Speech-to-text AI dengan akurasi tinggi",
                fullDescription: "Whisper dari OpenAI mengubah audio menjadi teks dengan akurasi luar biasa, bahkan dalam kondisi noise. Mendukung 99+ bahasa dengan punctuation otomatis.",
                sejarah: "Whisper dirilis oleh OpenAI pada September 2022. Model open-source yang dilatih pada 680,000 jam audio multibahasa untuk transkripsi yang akurat.",
                tahunPembuatan: "2022",
                founder: "OpenAI",
                keunggulanTeknis: [
                    "99%+ accuracy rate",
                    "99 languages supported",
                    "Noise reduction",
                    "Speaker diarization",
                    "Open source model"
                ],
                useCases: [
                    "Transkripsi kuliah",
                    "Subtitling video",
                    "Catatan rapat",
                    "Aksesibilitas",
                    "Penelitian linguistik"
                ],
                advantages: [
                    "Akurasi sangat tinggi",
                    "99 bahasa berbeda",
                    "Pengurangan noise otomatis",
                    "Gratis dan open source",
                    "Multi-speaker detection"
                ],
                howToUse: "1. Upload audio/video file\n2. Pilih bahasa\n3. Transcribe otomatis\n4. Edit dan export text",
                website: "https://openai.com/research/whisper",
                icon: "fas fa-microphone",
                color: "#10b981"
            },
            {
                id: 9,
                name: "Murf AI",
                category: "audio",
                description: "Studio voice AI untuk profesional",
                fullDescription: "Murf AI menyediakan suara AI berkualitas studio untuk konten profesional. Cocok untuk video edukasi, presentasi, dan konten pembelajaran.",
                sejarah: "Murf AI diluncurkan pada 2020 dan telah berkembang menjadi salah satu platform voice AI terkemuka dengan 120+ suara dalam 20+ bahasa.",
                tahunPembuatan: "2020",
                founder: "Murf AI",
                keunggulanTeknis: [
                    "120+ voice options",
                    "20+ languages",
                    "Studio quality",
                    "Voice customization",
                    "Pitch/speed control"
                ],
                useCases: [
                    "Video pembelajaran",
                    "E-learning courses",
                    "Presentasi bisnis",
                    "Konten YouTube",
                    "Iklan edukasi"
                ],
                advantages: [
                    "Kualitas suara studio",
                    "120+ pilihan suara",
                    "Kontrol pitch dan tempo",
                    "Integrasi dengan video",
                    "Team collaboration"
                ],
                howToUse: "1. Buat akun di murf.ai\n2. Pilih suara dan bahasa\n3. Input script text\n4. Generate dan download",
                website: "https://murf.ai",
                icon: "fas fa-headphones",
                color: "#6366f1"
            },
            {
                id: 10,
                name: "LALAL.AI",
                category: "audio",
                description: "AI untuk ekstraksi vokal dan instrumen",
                fullDescription: "LALAL.AI menggunakan AI untuk memisahkan vokal dan instrumen dari lagu. Sangat berguna untuk pembelajaran musik dan analisis audio.",
                sejarah: "LALAL.AI diluncurkan pada 2019 dan menggunakan neural networks untuk stem separation dengan akurasi tinggi.",
                tahunPembuatan: "2019",
                founder: "LALAL.AI Team",
                keunggulanTeknis: [
                    "Stem separation AI",
                    "High accuracy",
                    "Multiple stem types",
                    "Batch processing",
                    "Cloud-based"
                ],
                useCases: [
                    "Pembelajaran musik",
                    "Karaoke creation",
                    "Remixing songs",
                    "Audio analysis",
                    "Content creation"
                ],
                advantages: [
                    "Pisahkan vokal dan instrumen",
                    "Akurasi tinggi",
                    "Multiple format output",
                    "Processing cepat",
                    "User-friendly interface"
                ],
                howToUse: "1. Upload file audio\n2. Pilih proses (vokal/instrumen)\n3. Tunggu proses AI\n4. Download hasil",
                website: "https://lalal.ai",
                icon: "fas fa-wave-square",
                color: "#ef4444"
            },
            // Tambahan AI lainnya untuk lengkapi 15+
            {
                id: 11,
                name: "Runway ML",
                category: "video",
                description: "Studio kreatif AI untuk video editing",
                fullDescription: "Runway ML menghadirkan 30+ alat AI untuk produksi video. Dari penghapus background, animasi gambar, hingga generasi video dari teks.",
                sejarah: "Didirikan pada 2018, Runway ML telah menjadi platform kreatif AI terkemuka untuk profesional dan kreator.",
                tahunPembuatan: "2018",
                founder: "Runway ML",
                keunggulanTeknis: ["30+ AI tools", "Real-time editing", "Green screen AI", "Motion tracking", "4K export"],
                useCases: ["Video edukasi", "Presentasi", "Konten sosial media", "Animasi", "Editing profesional"],
                advantages: ["30+ tools AI video", "Green screen AI", "Text-to-video", "Motion tracking", "Export 4K/60fps"],
                howToUse: "1. Buat akun runwayml.com\n2. Upload video/gambar\n3. Pilih tools AI\n4. Export hasil",
                website: "https://runwayml.com",
                icon: "fas fa-video",
                color: "#ef4444"
            },
            {
                id: 12,
                name: "GitHub Copilot",
                category: "code",
                description: "AI pair programmer untuk coding",
                fullDescription: "Copilot adalah asisten coding AI yang membantu menulis kode lebih cepat dan efisien. Mendukung semua bahasa pemrograman populer.",
                sejarah: "Diluncurkan GitHub (Microsoft) pada 2021, menggunakan OpenAI Codex model.",
                tahunPembuatan: "2021",
                founder: "GitHub/Microsoft",
                keunggulanTeknis: ["Code completion", "Multi-language", "VS Code integration", "Context aware", "Learning-based"],
                useCases: ["Belajar coding", "Project development", "Code review", "Debugging", "Learning new languages"],
                advantages: ["Auto-complete code", "Multi-language support", "Integration dengan VS Code", "Code explanation", "Bug detection"],
                howToUse: "1. Install extension VS Code\n2. Login dengan GitHub\n3. Start typing code\n4. Terima saran AI",
                website: "https://github.com/features/copilot",
                icon: "fas fa-code",
                color: "#000000"
            },
            {
                id: 13,
                name: "Perplexity AI",
                category: "research",
                description: "AI search engine dengan sumber terverifikasi",
                fullDescription: "Perplexity menggabungkan search engine dengan AI untuk jawaban akurat dengan referensi sumber. Sempurna untuk penelitian akademik.",
                sejarah: "Didirikan 2022 oleh mantan peneliti OpenAI dan Google.",
                tahunPembuatan: "2022",
                founder: "Perplexity AI",
                keunggulanTeknis: ["Real-time search", "Citation generation", "Follow-up questions", "File upload", "Multimodal"],
                useCases: ["Penelitian akademik", "Verifikasi informasi", "Quick answers", "Learning topics", "Content research"],
                advantages: ["Sumber terverifikasi", "Citation otomatis", "Follow-up questions", "File upload support", "Gratis untuk dasar"],
                howToUse: "1. Kunjungi perplexity.ai\n2. Ajukan pertanyaan research\n3. Lihat sumber referensi\n4. Download hasil dalam PDF",
                website: "https://perplexity.ai",
                icon: "fas fa-search",
                color: "#10b981"
            },
            {
                id: 14,
                name: "Notion AI",
                category: "productivity",
                description: "AI workspace untuk organisasi & produktivitas",
                fullDescription: "Notion AI mengintegrasikan AI ke dalam workspace untuk membantu menulis, summarisasi, brainstorming, dan manajemen proyek.",
                sejarah: "Notion ditambahkan AI pada 2023 untuk meningkatkan produktivitas pengguna.",
                tahunPembuatan: "2023",
                founder: "Notion Labs",
                keunggulanTeknis: ["Writing assistance", "Meeting summarizer", "Task automation", "Database AI", "All-in-one"],
                useCases: ["Manajemen proyek", "Note taking", "Brainstorming", "Documentation", "Planning"],
                advantages: ["All-in-one workspace", "Writing assistance", "Meeting summarizer", "Task automation", "Database AI"],
                howToUse: "1. Login notion.so\n2. Aktifkan Notion AI\n3. Gunakan slash commands\n4. Automate workflows",
                website: "https://notion.so",
                icon: "fas fa-sticky-note",
                color: "#000000"
            },
            {
                id: 15,
                name: "Leonardo AI",
                category: "image",
                description: "Platform AI untuk game assets & design",
                fullDescription: "Leonardo AI khusus untuk generasi asset game, karakter, dan lingkungan 3D. Optimal untuk proyek game development.",
                sejarah: "Diluncurkan 2023 fokus pada asset game dan design.",
                tahunPembuatan: "2023",
                founder: "Leonardo AI",
                keunggulanTeknis: ["Game assets optimized", "3D model generation", "Texture creation", "Character design", "Real-time rendering"],
                useCases: ["Game development", "Character design", "Environment art", "Texture creation", "Prototyping"],
                advantages: ["Game assets optimized", "3D model generation", "Texture creation", "Character design", "Free tier available"],
                howToUse: "1. Join leonardo.ai\n2. Pilih model game-specific\n3. Generate assets\n4. Download PNG dengan transparency",
                website: "https://leonardo.ai",
                icon: "fas fa-gamepad",
                color: "#f59e0b"
            }
        ];

        // Load AI cards
        function loadAICards(filter = 'all') {
            const aiGrid = document.getElementById('aiGrid');
            aiGrid.innerHTML = '';
            
            const filteredData = filter === 'all' 
                ? aiData 
                : aiData.filter(ai => ai.category === filter);
            
            filteredData.forEach(ai => {
                const card = document.createElement('div');
                card.className = 'ai-card';
                card.innerHTML = `
                    <div class="ai-card-header">
                        <div class="ai-icon" style="background: ${ai.color}; box-shadow: 0 0 20px ${ai.color}80;">
                            <i class="${ai.icon}"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-color); margin-bottom: 0.5rem; font-family: 'Orbitron', sans-serif;">
                            ${ai.name}
                        </h3>
                        <span style="font-size: 0.8rem; color: ${ai.color}; background: rgba(255,255,255,0.1); padding: 0.2rem 0.8rem; border-radius: 15px; font-weight: 600; text-transform: uppercase;">
                            ${ai.category} AI
                        </span>
                    </div>
                    
                    <div class="ai-card-body">
                        <p style="color: var(--text-color); opacity: 0.9; line-height: 1.5; font-size: 0.9rem; margin-bottom: 1rem;">
                            ${ai.description}
                        </p>
                        
                        <div style="display: flex; gap: 0.3rem; flex-wrap: wrap; margin-bottom: 1rem;">
                            ${ai.advantages.slice(0, 2).map(adv => 
                                `<span style="background: rgba(255,255,255,0.1); color: var(--text-color); padding: 0.2rem 0.6rem; border-radius: 10px; font-size: 0.7rem; border: 1px solid ${ai.color}40;">
                                    ${adv.length > 30 ? adv.substring(0, 30) + '...' : adv}
                                </span>`
                            ).join('')}
                        </div>
                    </div>
                    
                    <div class="ai-card-footer">
                        <button onclick="window.open('${ai.website}', '_blank')" style="flex: 1; background: ${ai.color}; color: white; border: none; padding: 0.8rem; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                            <i class="fas fa-rocket"></i> Explore
                        </button>
                        <button onclick="showAIDetail(${ai.id})" style="flex: 1; background: transparent; color: var(--text-color); border: 1px solid ${ai.color}; padding: 0.8rem; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                `;
                aiGrid.appendChild(card);
            });
            
            document.getElementById('totalAI').textContent = filteredData.length + '+';
        }

        // Search function
        function searchAI() {
            const searchTerm = document.getElementById('searchBar').value.toLowerCase();
            const aiGrid = document.getElementById('aiGrid');
            aiGrid.innerHTML = '';
            
            const filteredData = aiData.filter(ai => 
                ai.name.toLowerCase().includes(searchTerm) ||
                ai.description.toLowerCase().includes(searchTerm) ||
                ai.category.toLowerCase().includes(searchTerm) ||
                ai.fullDescription.toLowerCase().includes(searchTerm)
            );
            
            if (filteredData.length === 0) {
                aiGrid.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-color); opacity: 0.7;">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <h3 style="font-family: 'Orbitron', sans-serif; margin-bottom: 0.5rem;">AI Tools Tidak Ditemukan</h3>
                        <p>Coba dengan kata kunci lain atau lihat semua kategori.</p>
                    </div>
                `;
                return;
            }
            
            filteredData.forEach(ai => {
                const card = document.createElement('div');
                card.className = 'ai-card';
                card.innerHTML = `
                    <div class="ai-card-header">
                        <div class="ai-icon" style="background: ${ai.color}; box-shadow: 0 0 20px ${ai.color}80;">
                            <i class="${ai.icon}"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-color); margin-bottom: 0.5rem; font-family: 'Orbitron', sans-serif;">
                            ${ai.name}
                        </h3>
                        <span style="font-size: 0.8rem; color: ${ai.color}; background: rgba(255,255,255,0.1); padding: 0.2rem 0.8rem; border-radius: 15px; font-weight: 600; text-transform: uppercase;">
                            ${ai.category} AI
                        </span>
                    </div>
                    
                    <div class="ai-card-body">
                        <p style="color: var(--text-color); opacity: 0.9; line-height: 1.5; font-size: 0.9rem; margin-bottom: 1rem;">
                            ${ai.description}
                        </p>
                    </div>
                    
                    <div class="ai-card-footer">
                        <button onclick="window.open('${ai.website}', '_blank')" style="flex: 1; background: ${ai.color}; color: white; border: none; padding: 0.8rem; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                            <i class="fas fa-rocket"></i> Explore
                        </button>
                        <button onclick="showAIDetail(${ai.id})" style="flex: 1; background: transparent; color: var(--text-color); border: 1px solid ${ai.color}; padding: 0.8rem; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; gap: 0.3rem;">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                `;
                aiGrid.appendChild(card);
            });
        }

        // Filter AI by category
        function filterAI(category) {
            loadAICards(category);
            
            // Update active button
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.style.background = 'transparent';
                btn.style.borderColor = 'rgba(0, 255, 136, 0.3)';
            });
            
            // Highlight active filter
            const activeButtons = document.querySelectorAll(`.nav-btn[onclick="filterAI('${category}')"]`);
            activeButtons.forEach(btn => {
                btn.style.background = 'rgba(0, 255, 136, 0.2)';
                btn.style.borderColor = 'var(--accent)';
            });
            
            // Close mobile menu if open
            document.getElementById('mobileFilterMenu').style.display = 'none';
            
            showNotification(`Menampilkan ${category === 'all' ? 'Semua' : category} AI`, 'info');
        }

        // Show AI Detail Modal
        function showAIDetail(id) {
            const ai = aiData.find(a => a.id === id);
            if (!ai) return;
            
            const modalBody = document.getElementById('modalBody');
            const modal = document.getElementById('aiModal');
            
            modalBody.innerHTML = `
                <div style="max-width: 700px; margin: 0 auto;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="ai-icon" style="background: ${ai.color}; box-shadow: 0 0 30px ${ai.color}80; width: 70px; height: 70px; font-size: 2rem;">
                            <i class="${ai.icon}"></i>
                        </div>
                        <div>
                            <h2 style="margin: 0; font-weight: 900; color: var(--text-color); font-size: 2.5rem; font-family: 'Orbitron', sans-serif;">
                                ${ai.name}
                            </h2>
                            <p style="margin: 0.5rem 0 0; color: var(--text-color); opacity: 0.9; font-size: 1.1rem;">
                                ${ai.description}
                            </p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h3 style="color: var(--text-color); margin-bottom: 0.5rem; font-size: 1.3rem; display: flex; align-items: center; gap: 0.5rem; font-family: 'Orbitron', sans-serif;">
                            <i class="fas fa-history" style="color: ${ai.color};"></i> 
                            Sejarah
                        </h3>
                        <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 10px; border-left: 3px solid ${ai.color};">
                            <p style="color: var(--text-color); line-height: 1.6; font-size: 1rem; margin: 0 0 0.5rem 0;">
                                <strong>Diluncurkan:</strong> ${ai.tahunPembuatan}<br>
                                <strong>Pendiri:</strong> ${ai.founder}
                            </p>
                            <p style="color: var(--text-color); line-height: 1.6; font-size: 1rem; margin: 0;">
                                ${ai.sejarah}
                            </p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="background: rgba(255,255,255,0.05); border-radius: 10px; padding: 1rem; border: 1px solid ${ai.color}40;">
                            <h4 style="color: var(--text-color); margin-bottom: 0.8rem; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-star" style="color: ${ai.color};"></i> 
                                Keunggulan
                            </h4>
                            <ul style="color: var(--text-color); padding-left: 1.2rem; font-size: 0.9rem;">
                                ${ai.advantages.map(adv => 
                                    `<li style="margin-bottom: 0.5rem;">
                                        <i class="fas fa-check" style="color: ${ai.color}; margin-right: 0.5rem;"></i>
                                        ${adv}
                                    </li>`
                                ).join('')}
                            </ul>
                        </div>
                        
                        <div style="background: rgba(255,255,255,0.05); border-radius: 10px; padding: 1rem; border: 1px solid ${ai.color}40;">
                            <h4 style="color: var(--text-color); margin-bottom: 0.8rem; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-play-circle" style="color: ${ai.color};"></i> 
                                Cara Menggunakan
                            </h4>
                            <div style="color: var(--text-color); line-height: 1.6; font-size: 0.9rem; white-space: pre-line; background: rgba(0,0,0,0.1); padding: 0.8rem; border-radius: 5px;">
                                ${ai.howToUse}
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="window.open('${ai.website}', '_blank')" style="flex: 2; background: ${ai.color}; color: white; border: none; padding: 1rem; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 1rem; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-external-link-alt"></i> 
                            Kunjungi Website
                        </button>
                        <button onclick="generatePDF(${ai.id})" style="flex: 1; background: transparent; color: var(--text-color); border: 2px solid ${ai.color}; padding: 1rem; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 1rem; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="fas fa-download"></i> 
                            PDF
                        </button>
                    </div>
                </div>
            `;
            
            modal.style.display = 'flex';
        }

        // Generate PDF
        function generatePDF(aiId) {
            const ai = aiData.find(a => a.id === aiId);
            if (!ai) return;
            
            showNotification(`🚀 Membuat PDF "${ai.name}"...`, 'info');
            
            setTimeout(() => {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Header
                doc.setFillColor(38, 38, 38);
                doc.rect(0, 0, 210, 30, 'F');
                doc.setTextColor(0, 255, 136);
                doc.setFontSize(20);
                doc.setFont('helvetica', 'bold');
                doc.text("AI BIJAK", 105, 15, { align: 'center' });
                
                // AI Name
                doc.setFillColor(parseInt(ai.color.slice(1, 3), 16), 
                               parseInt(ai.color.slice(3, 5), 16), 
                               parseInt(ai.color.slice(5, 7), 16));
                doc.rect(10, 35, 190, 12, 'F');
                doc.setTextColor(255, 255, 255);
                doc.setFontSize(16);
                doc.text(ai.name.toUpperCase(), 105, 42, { align: 'center' });
                
                // Content
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                
                let y = 60;
                
                // Description
                doc.setFont('helvetica', 'bold');
                doc.text("Deskripsi:", 15, y);
                doc.setFont('helvetica', 'normal');
                y += 7;
                const descLines = doc.splitTextToSize(ai.fullDescription, 180);
                doc.text(descLines, 15, y);
                y += descLines.length * 5 + 10;
                
                // History
                doc.setFont('helvetica', 'bold');
                doc.text("Sejarah:", 15, y);
                doc.setFont('helvetica', 'normal');
                y += 7;
                doc.text(`Diluncurkan: ${ai.tahunPembuatan}`, 15, y);
                y += 5;
                doc.text(`Pendiri: ${ai.founder}`, 15, y);
                y += 10;
                const historyLines = doc.splitTextToSize(ai.sejarah, 180);
                doc.text(historyLines, 15, y);
                y += historyLines.length * 5 + 15;
                
                // Advantages
                doc.setFont('helvetica', 'bold');
                doc.text("Keunggulan:", 15, y);
                doc.setFont('helvetica', 'normal');
                y += 7;
                ai.advantages.forEach((adv, i) => {
                    doc.text(`• ${adv}`, 15, y);
                    y += 5;
                });
                
                y += 10;
                
                // How to Use
                doc.setFont('helvetica', 'bold');
                doc.text("Cara Menggunakan:", 15, y);
                doc.setFont('helvetica', 'normal');
                y += 7;
                const howToLines = doc.splitTextToSize(ai.howToUse, 180);
                doc.text(howToLines, 15, y);
                
                // Footer
                doc.setFontSize(8);
                doc.setTextColor(150, 150, 150);
                doc.text(`© ${new Date().getFullYear()} AI Bijak - Modul Pembelajaran`, 105, 285, { align: 'center' });
                doc.text(`Dibuat untuk: ${'<?php echo htmlspecialchars($userName); ?>'}`, 105, 290, { align: 'center' });
                
                const fileName = `${ai.name.replace(/\s+/g, '_')}_Modul.pdf`;
                doc.save(fileName);
                
                showNotification(`✅ PDF "${ai.name}" berhasil diunduh!`, 'success');
            }, 1000);
        }

        // Notification function
        function showNotification(message, type = 'info') {
            const existingNotif = document.querySelector('.notification');
            if (existingNotif) existingNotif.remove();
            
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.style.background = type === 'success' ? 'rgba(0, 255, 136, 0.9)' : 'rgba(0, 217, 255, 0.9)';
            
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Theme Toggle
        function toggleTheme() {
            const body = document.body;
            const themeToggle = document.getElementById('themeToggle');
            
            if (body.classList.contains('light-theme')) {
                body.classList.remove('light-theme');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                localStorage.setItem('theme', 'dark');
                showNotification('🌙 Tema Gelap diaktifkan', 'info');
            } else {
                body.classList.add('light-theme');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                localStorage.setItem('theme', 'light');
                showNotification('☀️ Tema Terang diaktifkan', 'info');
            }
        }

        // Profile functions
        function showProfile() {
            document.getElementById('profileModal').style.display = 'flex';
            document.getElementById('userDropdown').style.display = 'none';
        }

        function closeProfile() {
            document.getElementById('profileModal').style.display = 'none';
        }

        function updateProfile() {
            showNotification('✅ Profile berhasil diperbarui!', 'success');
            setTimeout(() => {
                closeProfile();
            }, 1500);
        }

        // User Dropdown
        const userProfile = document.getElementById('userProfile');
        const userDropdown = document.getElementById('userDropdown');

        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function(e) {
            if (!userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.style.display = 'none';
            }
        });

        // Modal Close
        const modalClose = document.getElementById('modalClose');
        const modal = document.getElementById('aiModal');

        modalClose.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                modal.style.display = 'none';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modal.style.display = 'none';
                closeProfile();
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileFilterMenu');
            menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadAICards();
            
            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.body.classList.add('light-theme');
                document.getElementById('themeToggle').innerHTML = '<i class="fas fa-sun"></i>';
            }
            
            // Set theme toggle click event
            document.getElementById('themeToggle').addEventListener('click', toggleTheme);
            
            // Welcome message
            setTimeout(() => {
                showNotification(`👋 Selamat datang, ${'<?php echo htmlspecialchars($userName); ?>'}!`, 'info');
            }, 500);
        });
    </script>
</body>
</html>