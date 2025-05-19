<h2>My Files</h2>

<div class="controls">
    <div class="filter">
        <form action="/category" method="GET">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php 
                if (!empty($categories)):
                    foreach ($categories as $category):
                        $selected = (isset($currentCategory) && $currentCategory === $category) ? 'selected' : '';
                ?>
                        <option value="<?= htmlspecialchars($category) ?>" <?= $selected ?>><?= htmlspecialchars($category) ?></option>
                <?php 
                    endforeach;
                endif;
                ?>
            </select>
        </form>
    </div>
    
    <div class="sort">
        <span>Sort by:</span>
        <a href="<?= isset($currentCategory) ? '/category?category=' . urlencode($currentCategory) . '&' : '/app' ?>?orderBy=upload_date&order=<?= ($orderBy === 'upload_date' && $order === 'DESC') ? 'ASC' : 'DESC' ?>" class="<?= $orderBy === 'upload_date' ? 'active' : '' ?>">
            Date <?= ($orderBy === 'upload_date' && $order === 'DESC') ? '↓' : '↑' ?>
        </a>
        <a href="<?= isset($currentCategory) ? '/category?category=' . urlencode($currentCategory) . '&' : '/app' ?>?orderBy=title&order=<?= ($orderBy === 'title' && $order === 'ASC') ? 'DESC' : 'ASC' ?>" class="<?= $orderBy === 'title' ? 'active' : '' ?>">
            Title <?= ($orderBy === 'title' && $order === 'ASC') ? '↑' : '↓' ?>
        </a>
        <a href="<?= isset($currentCategory) ? '/category?category=' . urlencode($currentCategory) . '&' : '/app' ?>?orderBy=deadline&order=<?= ($orderBy === 'deadline' && $order === 'ASC') ? 'DESC' : 'ASC' ?>" class="<?= $orderBy === 'deadline' ? 'active' : '' ?>">
            Deadline <?= ($orderBy === 'deadline' && $order === 'ASC') ? '↑' : '↓' ?>
        </a>
    </div>
    
    <div class="view-toggle">
        <button id="grid-view" class="active">Grid</button>
        <button id="list-view">List</button>
    </div>
</div>

<div class="files-container grid">
    <?php if (!empty($files)): ?>
        <?php foreach ($files as $row): ?>
            <div class="file-card" onclick="window.location.href='/view?id=<?= $row['id'] ?>'">
                <div class="file-icon">
                    <span class="icon <?= $row['icon_type'] ?>"><?= strtoupper($row['icon_type']) ?></span>
                </div>
                <div class="file-info">
                    <div class="category-badge"><span class="category"><?= htmlspecialchars($row['category']) ?></span></div>
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <div class="file-meta">
                        <div class="meta-dates">
                            <span class="uploaded">Uploaded: <?= date('M d, Y', strtotime($row['upload_date'])) ?></span>
                            <?php if (!empty($row['deadline'])): ?>
                                <span class="deadline">Deadline: <?= date('M d, Y', strtotime($row['deadline'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($row['description'])): ?>
                        <p class="description"><?= htmlspecialchars($row['description']) ?></p>
                    <?php endif; ?>
                    <div class="file-actions">
                        <a href="<?= htmlspecialchars($row['file_path']) ?>" class="btn btn-download" download onclick="event.stopPropagation()">Download</a>
                        <?php 
                        $extension = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
                        $viewableExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'svg', 'txt', 'sql', 'md', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
                        if (in_array($extension, $viewableExtensions)):
                        ?>
                        <a href="/preview?id=<?= $row['id'] ?>" class="btn btn-view" onclick="event.stopPropagation()">View Online</a>
                        <?php endif; ?>
                        <a href="/edit?id=<?= $row['id'] ?>" class="btn btn-edit" onclick="event.stopPropagation()">Edit</a>
                        <a href="/delete?id=<?= $row['id'] ?>" class="btn btn-delete" data-confirm="Are you sure you want to delete this file?" onclick="event.stopPropagation()">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-files">
            <p>No files found. <a href="/upload">Upload a file</a> to get started.</p>
        </div>
    <?php endif; ?>
</div>

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

<script src="/assets/js/main.js"></script>