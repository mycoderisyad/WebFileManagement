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
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/app">Dashboard</a></li>
                    <li><a href="/upload">Upload File</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <?php if (isset($_GET['message'])): ?>
                <div class="message">
                    <span class="close-btn">&times;</span>
                    <?= htmlspecialchars($_GET['message']) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="error-container">
                    <span class="close-btn">&times;</span>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php include $content ?? 'home.php'; ?>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Task & File Management System</p>
        </div>
    </footer>
    <div class="confirm-overlay" id="confirmOverlay">
        <div class="confirm-dialog">
            <div class="confirm-title">Confirm Action</div>
            <div class="confirm-message" id="confirmMessage"></div>
            <div class="confirm-buttons">
                <button class="confirm-btn confirm-btn-cancel" id="confirmCancel">Cancel</button>
                <button class="confirm-btn confirm-btn-confirm" id="confirmOk">Confirm</button>
            </div>
        </div>
    </div>
    
    <script src="/assets/js/main.js"></script>
</body>
</html> 