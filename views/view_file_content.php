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
?>
<div class="file-details">
    <div class="file-details-header">
        <h2><?= htmlspecialchars($file->title) ?></h2>
        <div class="file-actions">
            <a href="<?= htmlspecialchars($file->file_path) ?>" class="btn btn-download" download>Download</a>
            <?php 
            $extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'pdf'])):
            ?>
            <a href="/view-content?id=<?= $file->id ?>" class="btn btn-view">View Online</a>
            <?php endif; ?>
            <a href="/edit?id=<?= $file->id ?>" class="btn btn-edit">Edit</a>
            <a href="/delete?id=<?= $file->id ?>" class="btn btn-delete" data-confirm="Are you sure you want to delete this file?">Delete</a>
            <a href="/app" class="btn btn-back">Back to Files</a>
        </div>
    </div>
    
    <div class="file-details-content">
        <div class="file-preview">
            <?php
            $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
            
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                echo '<img src="' . htmlspecialchars($file->file_path) . '" alt="' . htmlspecialchars($file->title) . '">';
            } else {
                echo '<div class="file-icon large">';
                switch ($extension) {
                    case 'pdf':
                        echo '<span class="icon pdf">PDF</span>';
                        break;
                    case 'doc':
                    case 'docx':
                        echo '<span class="icon doc">DOC</span>';
                        break;
                    default:
                        echo '<span class="icon file">FILE</span>';
                }
                echo '</div>';
            }
            ?>
        </div>
        
        <div class="file-info">
            <div class="info-item">
                <strong>Category:</strong>
                <span><?= htmlspecialchars($file->category) ?></span>
            </div>
            
            <div class="info-item">
                <strong>Upload Date:</strong>
                <span><?= date('F d, Y g:i A', strtotime($file->upload_date)) ?></span>
            </div>
            
            <?php if (!empty($file->deadline)): ?>
            <div class="info-item">
                <strong>Deadline:</strong>
                <span><?= date('F d, Y', strtotime($file->deadline)) ?></span>
                
                <?php
                $now = new DateTime();
                $deadline = new DateTime($file->deadline);
                $diff = $now->diff($deadline);
                
                if ($now > $deadline) {
                    echo '<span class="status overdue">Overdue by ' . $diff->days . ' day(s)</span>';
                } else {
                    echo '<span class="status upcoming">' . $diff->days . ' day(s) remaining</span>';
                }
                ?>
            </div>
            <?php endif; ?>
            
            <div class="info-item">
                <strong>File Type:</strong>
                <span><?= strtoupper(htmlspecialchars($extension)) ?></span>
            </div>
            
            <div class="info-item">
                <strong>File Size:</strong>
                <span><?= formatFileSize($file->file_size) ?></span>
            </div>
            
            <?php if (!empty($file->description)): ?>
            <div class="info-item description">
                <strong>Description:</strong>
                <p><?= nl2br(htmlspecialchars($file->description)) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div> 