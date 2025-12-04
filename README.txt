AI BIJAK - SETUP GUIDE & DOCUMENTATION
============================================

1. SYSTEM REQUIREMENTS
Development Environment
- XAMPP (PHP 8.2+ & MySQL)
- Visual Studio Code
- Modern Browser (Chrome/Firefox)
- Localhost (Apache server)

Server Requirements:
- PHP 8.2 or higher
- MySQL 5.7 or higher
- Apache 2.4 or higher
- JavaScript enabled in browser

2. DATABASE SETUP
Step 1: Create Database
CREATE DATABASE ai_bijak;
USE ai_bijak;

Step 2: Create Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(15),
    role ENUM('siswa', 'guru', 'admin') DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Step 3: Create User Logs Table (Optional)
CREATE TABLE user_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

Step 4: Insert Test User
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@aibijak.com', '$2y$10$YourHashedPasswordHere', 'admin');

3. CONFIGURATION FILES
config.php - Database Configuration

<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ai_bijak';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
?>

4. PROJECT STRUCTURE

ai-bijak/
├── assets/                  # Images, icons, fonts
├── logos/                  # AI tools logos
├── config.php              # Database configuration
├── dashboard.php           # Main dashboard (futuristic design)
├── index.php              # Landing page
├── login.html             # Login form
├── proses_login.php       # Login processing
├── proses_register.php    # Registration processing
├── register.html          # Registration form
├── reset-password.php     # Password reset
├── style.css              # Main stylesheet
├── script.js              # Main JavaScript
├── firebase-config.js     # Firebase config (optional)
├── pdf-generator.js       # PDF generation logic
└── README.txt             # This file

5. INSTALLATION STEPS

Step 1: Setup Local Server
1. Install XAMPP
2. Place project folder in htdocs/
3. Start Apache & MySQL in XAMPP Control Panel
4. Access via: http://localhost/ai-bijak/

Step 2: Database Import
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create database: ai_bijak
3. Import SQL from above or run queries manually

Step 3: Configuration
1. Edit config.php with your database credentials:

$host = 'localhost';
$user = 'root';
$pass = '';  // Default XAMPP password is empty
$dbname = 'ai_bijak';

Step 4: Test Registration
1. Go to: http://localhost/ai-bijak/register.html
2. Register new account
3. Login with credentials at: http://localhost/ai-bijak/login.html

6. FEATURES OVERVIEW

Main Features:
1. User Authentication - Login/Register with email or phone
2. Dashboard - Modern futuristic interface
3. AI Catalog - 15+ AI tools with 5 categories
4. Search & Filter - Real-time search and category filtering
5. PDF Generation - Download AI modules as PDF
6. Dark/Light Mode - Theme toggle with localStorage
7. User Profile - View and update profile
8. Responsive Design - Works on mobile & desktop

AI Categories:
1. Text AI - ChatGPT, Claude AI, Google Gemini, Perplexity AI
2. Image AI - DALL-E 3, Midjourney, Leonardo AI
3. Audio AI - Suno AI, ElevenLabs, Whisper AI, Murf AI, LALAL.AI
4. Video AI - Runway ML
5. Code/Productivity - GitHub Copilot, Notion AI

7. HOW TO USE

For Students/Teachers:
1. Register new account
2. Login with email/phone
3. Browse AI tools by category
4. Search for specific AI
5. Click Details for complete information
6. Download PDF for learning materials
7. Visit Website to use the AI tool
8. Update Profile as needed

For Developers:
1. All AI data is stored in dashboard.php in aiData array
2. To add new AI, add object to aiData array
3. PDF generation uses jsPDF library
4. Theme system uses CSS custom properties

8. CUSTOMIZATION

Add New AI Tool:
In dashboard.php, add to aiData array:
- javascript

{
    id: 16,
    name: "New AI Tool",
    category: "text",  // text, image, audio, video, code
    description: "Short description",
    fullDescription: "Full detailed description",
    sejarah: "Development history",
    tahunPembuatan: "2024",
    founder: "Company Name",
    keunggulanTeknis: ["Feature 1", "Feature 2"],
    useCases: ["Use case 1", "Use case 2"],
    advantages: ["Advantage 1", "Advantage 2"],
    howToUse: "Step by step guide",
    website: "https://example.com",
    icon: "fas fa-icon-name",
    color: "#hexcolor"
}


Change Theme Colors:
In dashboard.php CSS section, modify :root variables:
- css

:root {
    --primary: #your-color;
    --accent: #your-color;
    --neon-blue: #your-color;
    --bg-color: #your-color;
    --text-color: #your-color;
}

9. TROUBLESHOOTING

Common Issues:

1. Connection failed error
   - Check if MySQL is running
   - Verify database credentials in config.php
   - Ensure database ai_bijak exists

2. Login not working
   - Check if password hashing matches
   - Verify email/phone exists in database
   - Check for PHP error logs

3. PDF not downloading
   - Enable JavaScript in browser
   - Use Chrome/Edge for best compatibility
   - Check console for errors (F12)

4. Session issues
   - Ensure session_start() is at top of PHP files
   - Check browser cookies are enabled

5. Mobile layout issues
   - Clear browser cache
   - Test in different browsers

Debug Mode:
Add to config.php:
- php

error_reporting(E_ALL);
ini_set('display_errors', 1);

10. DEPLOYMENT

Shared Hosting Deployment:
1. Upload all files to public_html/
2. Create database via cPanel
3. Update config.php with hosting credentials
4. Import SQL via phpMyAdmin
5. Test login functionality

Security Checklist:
- [ ] Change default database password
- [ ] Remove debug code before production
- [ ] Use HTTPS for production
- [ ] Regular backup of database
- [ ] Update PHP to latest version

11. AI TOOLS REFERENCE

Complete AI List:
1. ChatGPT - Text generation & conversation
2. Claude AI - Ethical AI assistant
3. DALL-E 3 - Image generation from text
4. Midjourney - AI art generation
5. Google Gemini - Multimodal AI
6. Suno AI - Music generation
7. ElevenLabs - Text-to-speech
8. Whisper AI - Speech-to-text
9. Murf AI - Professional voice studio
10. LALAL.AI - Audio stem separation
11. Runway ML - Video AI tools
12. GitHub Copilot - AI pair programmer
13. Perplexity AI - AI search engine
14. Notion AI - AI workspace
15. Leonardo AI - Game asset generation

12. CONTACT & SUPPORT

Technical Support:**
- Email: sultonhasanudin320@gmail.com
- Phone: 0896-74280-380
- GitHub: [https://github.com/sulton29-beep]

For PKM Submission:
1. Ensure all features are working
2. Test on multiple devices
3. Prepare presentation materials
4. Document all code sections
5. Prepare user manual

13. FUTURE ENHANCEMENTS

Planned Features:
1. Admin panel for content management
2. User progress tracking
3. Quiz system for AI knowledge
4. Community forum
5. Mobile application
6. More AI tools (30+)
7. Multi-language support
8. API for third-party integration

Version 2.0 Goals:
- Real-time AI updates
- User-generated content
- Collaborative learning
- Advanced analytics
- Gamification elements

============================================
  SEMOGA PKM-MU SUKSES DAN BERMANFAAT! 🚀
============================================

Last Updated: Desember 2025  
Version: 1.0.0  
Compatibility: PHP 8.2+, MySQL 5.7+, Modern Browsers