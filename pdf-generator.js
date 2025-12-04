// PDF GENERATOR FOR MODULES - ULTRA SIMPLE FIXED VERSION
let jsPDFLoaded = false;

// Download PDF module - SIMPLE FIX
async function downloadPDF(aiId, aiName, buttonElement) {
    try {
        console.log('Starting PDF download for:', aiId, aiName);
        
        // Show loading on button
        let originalHTML = '';
        if (buttonElement) {
            originalHTML = buttonElement.innerHTML;
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat PDF...';
            buttonElement.disabled = true;
        }
        
        // Get AI data
        const ai = getAIData(aiId, aiName);
        console.log('AI Data:', ai);
        
        // Create PDF
        const pdf = await generatePDF(ai);
        
        // Download PDF
        const fileName = `Modul_${cleanFileName(ai.name)}_AI_Bijak.pdf`;
        pdf.save(fileName);
        
        // Show success message
        showPDFNotification('✅ PDF berhasil diunduh!', 'success');
        
        return true;
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showPDFNotification('❌ Gagal membuat PDF', 'error');
        return false;
        
    } finally {
        // Restore button
        if (buttonElement) {
            buttonElement.innerHTML = '<i class="fas fa-download"></i> Download Modul PDF';
            buttonElement.disabled = false;
        }
    }
}

// Get AI data helper
function getAIData(aiId, aiName) {
    // Try to find in global data
    if (window.allAIData && Array.isArray(window.allAIData)) {
        const found = window.allAIData.find(a => a.id === aiId || a.name === aiName);
        if (found) return found;
    }
    
    // Try current AI data
    if (window.currentAIData) return window.currentAIData;
    
    // Fallback data
    return {
        id: aiId || 'ai-001',
        name: aiName || 'AI Tools',
        category: 'General AI',
        short_desc: 'Modul pembelajaran AI untuk pelajar Indonesia.',
        detailed_desc: `AI ini membantu siswa dalam menyelesaikan tugas sekolah dengan cara yang mudah dan menyenangkan. Cocok untuk siswa SMP, SMA, dan SMK.`,
        advantages: 'Mudah digunakan oleh pemula\nHasil akurat dan cepat\nGratis untuk pendidikan\nMendukung bahasa Indonesia\nAman untuk pelajar',
        try_link: 'https://aibijak.com'
    };
}

// Load jsPDF - SIMPLE VERSION
async function loadJsPDF() {
    if (window.jspdf && jsPDFLoaded) {
        return window.jspdf.jsPDF;
    }
    
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        
        script.onload = () => {
            console.log('jsPDF loaded successfully');
            jsPDFLoaded = true;
            resolve(window.jspdf.jsPDF);
        };
        
        script.onerror = (err) => {
            console.error('Failed to load jsPDF:', err);
            reject(new Error('Failed to load PDF library'));
        };
        
        document.head.appendChild(script);
    });
}

// Generate PDF - FIXED FOR INDONESIAN TEXT
async function generatePDF(ai) {
    try {
        // Load jsPDF
        const jsPDF = await loadJsPDF();
        
        // Create document
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        
        // Page setup
        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        const margin = 20;
        const contentWidth = pageWidth - (margin * 2);
        
        // ========== PAGE 1: COVER ==========
        
        // Background
        doc.setFillColor(99, 102, 241); // Blue
        doc.rect(0, 0, pageWidth, pageHeight, 'F');
        
        // Title
        doc.setFontSize(32);
        doc.setTextColor(255, 255, 255);
        doc.setFont('helvetica', 'bold');
        doc.text('AI BIJAK', pageWidth / 2, 60, { align: 'center' });
        
        doc.setFontSize(18);
        doc.text('Platform Edukasi AI', pageWidth / 2, 75, { align: 'center' });
        doc.text('untuk Pelajar Indonesia', pageWidth / 2, 85, { align: 'center' });
        
        // AI Name
        doc.setFontSize(36);
        doc.text(ai.name, pageWidth / 2, 120, { align: 'center' });
        
        // Category
        doc.setFillColor(139, 92, 246); // Purple
        doc.roundedRect(pageWidth / 2 - 25, 135, 50, 12, 6, 6, 'F');
        doc.setFontSize(12);
        doc.setTextColor(255, 255, 255);
        doc.text(ai.category.toUpperCase(), pageWidth / 2, 142, { align: 'center' });
        
        // Footer
        doc.setFontSize(10);
        doc.setTextColor(255, 255, 255, 0.7);
        doc.text('www.aibijak.com', pageWidth / 2, pageHeight - 20, { align: 'center' });
        
        // ========== PAGE 2: CONTENT ==========
        doc.addPage();
        
        let yPos = margin;
        
        // Header
        doc.setFontSize(20);
        doc.setTextColor(99, 102, 241);
        doc.setFont('helvetica', 'bold');
        doc.text(`MODUL: ${ai.name}`, margin, yPos);
        yPos += 15;
        
        // Divider
        doc.setDrawColor(99, 102, 241);
        doc.setLineWidth(0.5);
        doc.line(margin, yPos, pageWidth - margin, yPos);
        yPos += 10;
        
        // Description
        doc.setFontSize(12);
        doc.setTextColor(30, 41, 59); // Dark text
        doc.setFont('helvetica', 'bold');
        doc.text('DESKRIPSI:', margin, yPos);
        yPos += 8;
        
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        const description = ai.detailed_desc || ai.short_desc || 'Deskripsi AI untuk pembelajaran.';
        const descLines = doc.splitTextToSize(description, contentWidth);
        doc.text(descLines, margin, yPos);
        yPos += (descLines.length * 5) + 15;
        
        // Features/Advantages - NO EMOJI, USE SIMPLE BULLETS
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text('KEUNGGULAN:', margin, yPos);
        yPos += 8;
        
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        
        // Process advantages (remove emoji, fix encoding)
        let advantagesText = ai.advantages || 'Tidak ada informasi keunggulan';
        
        // Replace emoji and special characters with simple text
        advantagesText = advantagesText
            .replace(/[✓✅✔]/g, '• ')  // Replace checkmarks with bullet
            .replace(/[🚀📚🎯✨]/g, '')  // Remove other emojis
            .replace(/\n/g, '\n• ');    // Add bullet to each line
        
        const advantageLines = doc.splitTextToSize(advantagesText, contentWidth);
        doc.text(advantageLines, margin, yPos);
        yPos += (advantageLines.length * 5) + 15;
        
        // How to Use
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text('CARA MENGGUNAKAN:', margin, yPos);
        yPos += 8;
        
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        
        // Simple numbered steps (no emoji)
        const howToUse = `1. Kunjungi website AI tersebut
2. Buat akun gratis (jika diperlukan)
3. Masukkan pertanyaan atau perintah
4. Dapatkan hasil secara instan
5. Gunakan untuk referensi belajar`;
        
        const howToLines = doc.splitTextToSize(howToUse, contentWidth);
        doc.text(howToLines, margin, yPos);
        yPos += (howToLines.length * 5) + 15;
        
        // Try Now Section
        doc.setFillColor(248, 250, 252); // Light gray
        doc.roundedRect(margin, yPos, contentWidth, 25, 5, 5, 'F');
        
        doc.setFontSize(12);
        doc.setTextColor(99, 102, 241);
        doc.setFont('helvetica', 'bold');
        doc.text('COBA SEKARANG:', margin + 10, yPos + 10);
        
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        const tryLink = ai.try_link || 'https://aibijak.com';
        doc.text(tryLink, margin + 10, yPos + 18);
        
        // Make it clickable
        doc.link(margin + 10, yPos + 14, doc.getTextWidth(tryLink), 6, { url: tryLink });
        
        yPos += 35;
        
        // Footer
        const date = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        doc.setFontSize(9);
        doc.setTextColor(100, 116, 139);
        doc.text(`Dibuat: ${date}`, margin, pageHeight - 15);
        doc.text('AI Bijak - Tugas PKM', pageWidth - margin, pageHeight - 15, { align: 'right' });
        
        return doc;
        
    } catch (error) {
        console.error('Error in generatePDF:', error);
        throw error;
    }
}

// Helper functions
function cleanFileName(name) {
    return name
        .replace(/[^\w\s]/gi, '')  // Remove special characters
        .replace(/\s+/g, '_')       // Replace spaces with underscores
        .substring(0, 50);          // Limit length
}

function showPDFNotification(message, type = 'info') {
    // Check if showNotification exists
    if (typeof showNotification === 'function') {
        showNotification(message, type);
        return;
    }
    
    // Fallback to simple alert
    alert(message);
    
    // Or create simple notification
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Simple button handler
function setupPDFButtons() {
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.download-pdf-btn');
        
        if (button) {
            e.preventDefault();
            e.stopPropagation();
            
            const aiId = button.getAttribute('data-ai-id') || 
                        button.getAttribute('data-id');
            
            const aiName = button.getAttribute('data-ai-name') || 
                          button.getAttribute('data-name') || 
                          button.textContent.replace('Download Modul PDF', '').trim() ||
                          'AI';
            
            console.log('PDF button clicked:', { aiId, aiName, button });
            
            downloadPDF(aiId, aiName, button);
        }
    });
}

// Test function - untuk debugging
window.testPDF = async function() {
    const testAI = {
        id: 'test-ai',
        name: 'ChatGPT untuk Pelajar',
        category: 'Text AI',
        short_desc: 'AI untuk membantu menulis dan brainstorming ide',
        detailed_desc: 'ChatGPT dapat membantu pelajar dalam menulis esai, mencari referensi, menerjemahkan bahasa, menjelaskan konsep sulit, dan latihan soal.',
        advantages: '✓ Mudah digunakan oleh pemula\n✓ Hasil akurat dan cepat\n✓ Gratis untuk pendidikan\n✓ Mendukung bahasa Indonesia\n✓ Aman untuk pelajar',
        try_link: 'https://chat.openai.com'
    };
    
    console.log('Testing PDF generation...');
    const result = await downloadPDF('test-ai', 'ChatGPT');
    console.log('Test result:', result);
};

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Setting up PDF buttons...');
    setupPDFButtons();
    
    // Also initialize after a short delay to catch dynamically loaded buttons
    setTimeout(setupPDFButtons, 1000);
});

// Export function for manual calls
window.downloadAIPDF = downloadPDF;
window.generateAIPDF = generatePDF;