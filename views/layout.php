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
            <p>&copy; <?= date('Y') ?> File Management System</p>
        </div>
    </footer>
<div class="confirm-overlay">
    <div class="confirm-dialog">
        <div class="confirm-title">Confirm Delete</div>
        <div class="confirm-message">Are you sure you want to delete this file?</div>
        <div class="confirm-buttons">
            <button class="confirm-btn confirm-btn-cancel">Cancel</button>
            <button class="confirm-btn confirm-btn-confirm">Delete</button>
        </div>
    </div>
</div>
    <script src="assets/js/main.js"></script>
</body>
</html> 