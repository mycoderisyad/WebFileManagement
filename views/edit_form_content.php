<?php
function formatFileSize($bytes)
{
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
<h2>Edit File Details</h2>

<form action="/update" method="POST" class="edit-form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $file->id ?>">

    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($file->title) ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"><?= htmlspecialchars($file->description) ?></textarea>
    </div>

    <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
            if (!empty($categories)):
                foreach ($categories as $category):
                    $selected = ($file->category === $category) ? 'selected' : '';
            ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $selected ?>><?= htmlspecialchars($category) ?></option>
            <?php
                endforeach;
            endif;
            ?>
            <option value="new_category">Add New Category</option>
        </select>
    </div>

    <div class="form-group new-category-group" style="display: none;">
        <label for="new_category">New Category:</label>
        <input type="text" id="new_category" name="new_category">
    </div>

    <div class="form-group">
        <label for="deadline">Deadline (optional):</label>
        <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($file->deadline) ?>">
    </div>

    <div class="form-group">
        <label>Current File:</label>
        <div class="current-file">
            <span class="filename"><?= htmlspecialchars($file->filename) ?></span>
            <span class="filesize">(<?= formatFileSize($file->file_size) ?>)</span>
        </div>
    </div>

    <div class="form-group">
        <label for="file">Replace File (optional):</label>
        <input type="file" id="file" name="file"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.svg,.xls,.xlsx,.ppt,.pptx,.sql,.txt,.zip,.rar,.7z,.csv">
        <small>
            Accepted file types: PDF, DOC, DOCX, JPG, JPEG, PNG, SVG, XLS, XLSX, PPT, PPTX, SQL, TXT, ZIP, RAR, 7Z, CSV (Max size: 5MB)
        </small>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-edit">Update</button>
        <a href="/app" class="btn btn-back">Cancel</a>
    </div>
</form>