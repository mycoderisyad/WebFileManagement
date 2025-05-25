<h2>Upload New File</h2>

<form action="/save" method="POST" enctype="multipart/form-data" class="upload-form">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?= isset($formData['title']) ? htmlspecialchars($formData['title']) : '' ?>" required>
    </div>
    
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"><?= isset($formData['description']) ? htmlspecialchars($formData['description']) : '' ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php 
            if (!empty($categories)):
                foreach ($categories as $category):
                    $selected = (isset($formData['category']) && $formData['category'] === $category) ? 'selected' : '';
            ?>
                    <option value="<?= htmlspecialchars($category) ?>" <?= $selected ?>><?= htmlspecialchars($category) ?></option>
            <?php 
                endforeach;
            endif;
            ?>
            <option value="new_category" <?= (isset($formData['category']) && $formData['category'] === 'new_category') ? 'selected' : '' ?>>Add New Category</option>
        </select>
    </div>
    
    <div class="form-group new-category-group" style="display: none;">
        <label for="new_category">New Category:</label>
        <input type="text" id="new_category" name="new_category" value="<?= isset($formData['new_category']) ? htmlspecialchars($formData['new_category']) : '' ?>">
    </div>
    
    <div class="form-group">
        <label for="deadline">Deadline (optional):</label>
        <input type="date" id="deadline" name="deadline" value="<?= isset($formData['deadline']) ? htmlspecialchars($formData['deadline']) : '' ?>">
    </div>
    
    <div class="form-group">
        <label for="file">File:</label>
        <input type="file" id="file" name="file" required>
        <small>Accepted file types: PDF, DOC, DOCX, JPG, PNG, TXT (Max size: 5MB)</small>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-edit">Upload</button>
        <a href="/app" class="btn btn-back">Cancel</a>
    </div>
</form>

<script src="/assets/js/main.js"></script> 