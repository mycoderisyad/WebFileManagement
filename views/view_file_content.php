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

$viewableExtensions = [
    'jpg', 'jpeg', 'png', 'pdf', 'svg',  
    'txt', 'sql', 'md',
    'doc', 'docx',
    'xls', 'xlsx', 'csv',      
    'ppt', 'pptx'       
];

$extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
?>

<div class="file-details">
    <div class="file-details-header">
        <h2><?= htmlspecialchars($file->title) ?></h2>
        <div class="file-actions">
            <a href="<?= htmlspecialchars($file->file_path) ?>" class="btn btn-download">Download</a>
            <?php if (in_array($extension, $viewableExtensions)): ?>
                <a href="/preview?id=<?= $file->id ?>" class="btn btn-view">View Online</a>
            <?php endif; ?>
            <a href="/edit?id=<?= $file->id ?>" class="btn btn-edit">Edit</a>
            <a href="/delete?id=<?= $file->id ?>" class="btn btn-delete" data-confirm="Are you sure you want to delete this file?">Delete</a>
            <a href="/app" class="btn btn-back">Back to Files</a>
        </div>
    </div>

    <div class="file-details-content">
        <div class="file-preview">
            <div class="file-icon">
                <span class="icon <?= $extension ?>"><?= strtoupper($extension) ?></span>
            </div>
        </div>
        
        <div class="file-metadata">
            <div class="info-item">
                <strong>Category:</strong>
                <span class="category"><?= htmlspecialchars($file->category) ?></span>
            </div>
            
            <div class="info-item">
                <strong>Upload Date:</strong>
                <span><?= date('F d, Y h:i A', strtotime($file->upload_date)) ?></span>
            </div>
            
            <?php if (!empty($file->deadline)): ?>
            <div class="info-item">
                <strong>Deadline:</strong>
                <span>
                    <?= date('F d, Y', strtotime($file->deadline)) ?>
                    <?php
                    $now = new DateTime();
                    $deadline = new DateTime($file->deadline);
                    $diff = $deadline->diff($now);
                    if ($now > $deadline) {
                        echo '<span class="status overdue">Overdue by ' . $diff->days . ' day(s)</span>';
                    } else {
                        echo '<span class="status upcoming">Due in ' . $diff->days . ' day(s)</span>';
                    }
                    ?>
                </span>
            </div>
            <?php endif; ?>
            
            <div class="info-item">
                <strong>File Type:</strong>
                <span><?= strtoupper($extension) ?></span>
            </div>
            
            <div class="info-item">
                <strong>File Size:</strong>
                <span><?= formatFileSize($file->file_size) ?></span>
            </div>
            
            <?php if (!empty($file->description)): ?>
            <div class="info-item description">
                <strong>Description:</strong>
                <span><?= nl2br(htmlspecialchars($file->description)) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>