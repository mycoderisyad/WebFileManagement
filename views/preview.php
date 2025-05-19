<?php
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

$extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));

// Get file content for text files
$file_content = '';
if (in_array($extension, ['txt', 'sql', 'md'])) {
    $file_content = @file_get_contents($file->file_path) ?: 'Unable to read file content';
}
?>

<div class="file-viewer">
    <div class="file-viewer-header">
        <h2><?= htmlspecialchars($file->title) ?></h2>
        <div class="file-actions">
            <a href="<?= htmlspecialchars($file->file_path) ?>" class="btn btn-download" download>Download</a>
            <a href="/edit?id=<?= $file->id ?>" class="btn btn-edit">Edit</a>
            <a href="/delete?id=<?= $file->id ?>" class="btn btn-delete" data-confirm="Are you sure you want to delete this file?">Delete</a>
            <a href="/view?id=<?= $file->id ?>" class="btn btn-back">File Details</a>
            <a href="/app" class="btn btn-back">Back to Files</a>
        </div>
    </div>
    
    <div class="file-info-bar">
        <div class="file-meta">
            <span class="file-type"><?= strtoupper(htmlspecialchars($extension)) ?></span>
            <span class="file-size"><?= formatFileSize($file->file_size) ?></span>
        </div>
    </div>
    
    <div class="file-viewer-content">
        <?php if (in_array($extension, ['jpg', 'jpeg', 'png'])): ?>
            <div class="image-viewer">
                <img src="<?= htmlspecialchars($file->file_path) ?>" alt="<?= htmlspecialchars($file->title) ?>">
            </div>
        <?php elseif ($extension === 'pdf'): ?>
            <div class="pdf-viewer">
                <iframe src="<?= htmlspecialchars($file->file_path) ?>" width="100%" height="800px"></iframe>
            </div>
        <?php elseif (in_array($extension, ['txt', 'sql', 'md'])): ?>
            <div class="text-viewer">
                <pre><?= htmlspecialchars($file_content) ?></pre>
            </div>
        <?php else: ?>
            <div class="unsupported-format">
                <div class="file-icon large <?= $extension ?>">
                    <span class="icon"><?= strtoupper($extension) ?></span>
                </div>
                <p>Preview not available for this file type. Please download the file to view it.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<link rel="stylesheet" href="/assets/css/styles.css">