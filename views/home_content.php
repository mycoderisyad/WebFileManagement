<?php
function getFileSubtitle($extension)
{
    $subtitles = [
        'doc' => 'MS Word Document',
        'docx' => 'MS Word Document',
        'xls' => 'MS Excel Document',
        'xlsx' => 'MS Excel Document',
        'csv' => 'MS Excel Document',
        'ppt' => 'MS PowerPoint Document',
        'pptx' => 'MS PowerPoint Document',
        'pdf' => 'PDF Document',
        'jpg' => 'Image',
        'jpeg' => 'Image',
        'png' => 'Image',
        'gif' => 'Image',
        'bmp' => 'Image',
        'svg' => 'Image',
        'txt' => 'Text Document',
        'md' => 'Markdown Document',
        'zip' => 'Archive',
        'rar' => 'Archive',
        '7z' => 'Archive',
        'html' => 'Web Document',
        'htm' => 'Web Document',
        'css' => 'Stylesheet',
        'js' => 'JavaScript File',
        'php' => 'PHP File',
        'sql' => 'SQL Database'
    ];
    return $subtitles[$extension] ?? ucfirst($extension) . ' Document';
}

$viewableExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'svg', 'txt', 'sql', 'md', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
$hasFiles = !empty($files);
?>

<div class="controls <?= $hasFiles ? '' : 'controls-empty' ?>">
    <?php if ($hasFiles): ?>
        <div class="upload-button">
            <a href="/upload">Upload file</a>
        </div>

        <div class="sort">
            <a href="<?= isset($currentCategory) && $currentCategory !== '' ? '/category?category=' . urlencode($currentCategory) . '&orderBy=title&order=' . (($orderBy === 'title' && $order === 'ASC') ? 'DESC' : 'ASC') : '/app?orderBy=title&order=' . (($orderBy === 'title' && $order === 'ASC') ? 'DESC' : 'ASC') ?>" class="<?= $orderBy === 'title' ? 'active' : '' ?>">
                Title <?= ($orderBy === 'title' && $order === 'DESC') ? '↓' : '↑' ?>
            </a>

            <a href="<?= isset($currentCategory) && $currentCategory !== '' ? '/category?category=' . urlencode($currentCategory) . '&orderBy=upload_date&order=' . (($orderBy === 'upload_date' && $order === 'DESC') ? 'ASC' : 'DESC') : '/app?orderBy=upload_date&order=' . (($orderBy === 'upload_date' && $order === 'DESC') ? 'ASC' : 'DESC') ?>" class="<?= $orderBy === 'upload_date' ? 'active' : '' ?>">
                Date <?= ($orderBy === 'upload_date' && $order === 'DESC') ? '↓' : '↑' ?>
            </a>

            <a href="<?= isset($currentCategory) && $currentCategory !== '' ? '/category?category=' . urlencode($currentCategory) . '&orderBy=deadline&order=' . (($orderBy === 'deadline' && $order === 'ASC') ? 'DESC' : 'ASC') : '/app?orderBy=deadline&order=' . (($orderBy === 'deadline' && $order === 'ASC') ? 'DESC' : 'ASC') ?>" class="<?= $orderBy === 'deadline' ? 'active' : '' ?>">
                Deadline <?= ($orderBy === 'deadline' && $order === 'DESC') ? '↓' : '↑' ?>
            </a>
        </div>

        <div class="filter">
            <form action="/category" method="GET">
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php $selected = (isset($currentCategory) && $currentCategory === $category) ? 'selected' : ''; ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $selected ?>><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </form>
        </div>
    <?php else: ?>
    <?php endif; ?>
</div>

<div class="files-container <?= $hasFiles ? 'grid' : 'empty' ?>">
    <?php if ($hasFiles): ?>
        <?php foreach ($files as $row): ?>
            <?php $extension = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION)); ?>
            <div class="file-card" onclick="window.location.href='/view?id=<?= $row['id'] ?>'">
                <div class="card-header">
                    <div class="file-icon-small">
                        <span class="icon-small <?= $extension ?>"><?= strtoupper($extension) ?></span>
                    </div>

                    <div class="header-content">
                        <h3 class="file-title"><?= htmlspecialchars($row['title']) ?></h3>
                        <p class="file-subtitle"><?= htmlspecialchars(getFileSubtitle($extension)) ?></p>
                    </div>

                    <div class="file-menu" onclick="event.stopPropagation()">
                        <button class="menu-toggle" onclick="toggleFileMenu(event, <?= $row['id'] ?>)">
                            <span>⋮</span>
                        </button>
                        <div class="file-dropdown" id="menu-<?= $row['id'] ?>">
                            <a href="<?= htmlspecialchars($row['file_path']) ?>" download>Download</a>
                            <a href="/edit?id=<?= $row['id'] ?>">Edit</a>
                            <a href="/delete?id=<?= $row['id'] ?>" data-confirm="Are you sure you want to delete this file?">Delete</a>
                            <?php if (in_array($extension, $viewableExtensions)): ?>
                                <a href="/preview?id=<?= $row['id'] ?>">Views</a>
                                <a href="/view?id=<?= $row['id'] ?>">Details</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <?php if (!empty($row['description'])): ?>
                        <p class="file-description"><?= htmlspecialchars($row['description']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="card-bottom">
                    <div class="category-tag"><?= htmlspecialchars($row['category']) ?></div>
                    <div class="date-info">
                        <?php if (!empty($row['deadline'])): ?>
                            <?= date('j M', strtotime($row['upload_date'])) ?> - <?= date('j M y', strtotime($row['deadline'])) ?>
                        <?php else: ?>
                            <?= date('j/m/y', strtotime($row['upload_date'])) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-files">
            <h3>No files yet</h3>
            <p>Start building your file collection by uploading your first file.</p>
            <a href="/upload" class="upload-link">Upload a file</a>
        </div>
    <?php endif; ?>
</div>