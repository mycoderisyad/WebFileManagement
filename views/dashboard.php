<?php
$categories = [];
foreach ($files as $file) {
    if (!isset($categories[$file->category])) {
        $categories[$file->category] = [];
    }
    $categories[$file->category][] = $file;
}
?>

<div class="app-container">
    <header class="app-header">
        <h1>File Management System</h1>
        <nav>
            <ul class="nav-menu">
                <li><a href="/">Home</a></li>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/upload">Upload File</a></li>
            </ul>
        </nav>
    </header>

    <section class="file-section">
        <h2>My Files</h2>
        
        <div class="file-filters">
            <div class="category-filter">
                <label>Filter by Category:</label>
                <select onchange="window.location.href=this.value">
                    <option value="/app" <?= empty($_GET['category']) ? 'selected' : '' ?>>All Categories</option>
                    <?php foreach ($categories as $category => $files): ?>
                    <option value="/app?category=<?= urlencode($category) ?>" 
                            <?= isset($_GET['category']) && $_GET['category'] === $category ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="sort-options">
                <span>Sort by:</span>
                <a href="?sort=date">Date</a> |
                <a href="?sort=title">Title</a> |
                <a href="?sort=deadline">Deadline</a>
            </div>

            <div class="view-toggle">
                <button class="btn btn-secondary" data-view="grid">Grid</button>
                <button class="btn btn-secondary" data-view="list">List</button>
            </div>
        </div>

        <div class="file-grid">
            <?php 
            $displayFiles = isset($_GET['category']) 
                ? ($categories[$_GET['category']] ?? [])
                : $files;
            
            foreach ($displayFiles as $file): 
                $extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
            ?>
            <div class="file-card">
                <div class="file-type <?= $extension ?>"><?= strtoupper($extension) ?></div>
                <div class="file-info">
                    <h3 class="file-title"><?= htmlspecialchars($file->title) ?></h3>
                    <div class="file-meta">
                        <div>Uploaded: <?= date('M d, Y', strtotime($file->upload_date)) ?></div>
                        <?php if (!empty($file->deadline)): ?>
                        <div>Due: <?= date('M d, Y', strtotime($file->deadline)) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="file-actions">
                    <a href="<?= htmlspecialchars($file->file_path) ?>" class="btn btn-primary" download>
                        <i class="fas fa-download"></i> Download
                    </a>
                    <?php if (in_array($extension, $viewableExtensions)): ?>
                    <a href="/preview?id=<?= $file->id ?>" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <?php endif; ?>
                    <a href="/edit?id=<?= $file->id ?>" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button class="btn btn-secondary" onclick="confirmDelete(<?= $file->id ?>)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($displayFiles)): ?>
            <div class="empty-folder">
                <i class="fas fa-folder-open"></i>
                <p>No files in this category</p>
                <button class="btn btn-primary" onclick="showUploadModal()">
                    <i class="fas fa-upload"></i> Upload File
                </button>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<div id="uploadModal" class="modal">
</div>
<div id="deleteModal" class="modal">
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="/assets/css/styles.css">
<script src="/assets/js/main.js"></script> 