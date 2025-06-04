<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Management System</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <h1>File Management System</h1>
            <nav class="nav-desktop">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/app">Dashboard</a></li>
                </ul>
            </nav>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
        <nav class="nav-mobile" id="navMobile">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/app">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section class="landing-hero">
            <div class="container">
                <h1>Kelola File & Tugas Anda dengan Mudah</h1>
                <p>Sistem manajemen file yang intuitif dan sederhana untuk membantu Anda mengorganisir tugas dan dokumen dengan efisien.</p>
                <div class="hero-buttons">
                    <a href="/app" class="btn btn-primary">Mulai Sekarang</a>
                    <a href="#features" class="btn btn-secondary">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </section>
        
        <section id="features" class="landing-features">
            <div class="container">
                <h2>Fitur Utama</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📁</div>
                        <h3>Penyimpanan File</h3>
                        <p>Upload dan simpan file dalam berbagai format seperti PDF, DOCX, dan gambar dengan aman.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🏷️</div>
                        <h3>Kategorisasi</h3>
                        <p>Kelompokkan file berdasarkan kategori untuk mempermudah pencarian dan pengelolaan.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📅</div>
                        <h3>Deadline Tracking</h3>
                        <p>Tentukan deadline untuk setiap tugas dan dapatkan peringatan visual tentang status tenggat waktu.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🔄</div>
                        <h3>Sorting & Filtering</h3>
                        <p>Urutkan dan filter file berdasarkan tanggal, kategori, atau batas waktu untuk menemukan yang Anda butuhkan.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">💻</div>
                        <h3>Responsif</h3>
                        <p>Akses di berbagai perangkat dengan tampilan yang nyaman dan responsif.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Manajemen Sederhana</h3>
                        <p>Antarmuka yang intuitif dan mudah digunakan, tanpa perlu keahlian teknis khusus.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> File Management System</p>
        </div>
    </footer>

<script src="/assets/js/main.js"></script>
</body>
</html>