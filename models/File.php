<?php
class File
{
    private $conn;
    private $table = 'files';
    public $id;
    public $title;
    public $description;
    public $category;
    public $filename;
    public $file_path;
    public $file_type;
    public $file_size;
    public $upload_date;
    public $deadline;
    public $icon_type;

    private const ICON_MAPPING = [
        'pdf' => 'pdf',
        'doc' => 'doc', 'docx' => 'doc',
        'jpg' => 'img', 'jpeg' => 'img', 'png' => 'img', 'svg' => 'img',
        'xls' => 'xls', 'xlsx' => 'xls',
        'ppt' => 'ppt', 'pptx' => 'ppt',
        'sql' => 'sql',
        'txt' => 'txt',
        'zip' => 'zip', 'rar' => 'zip', '7z' => 'zip'
    ];

    public function __construct($db)
    {
        $this->conn = $db;
        $this->icon_type = 'file';
    }

    private function clean($data)
    {
        return !empty($data) ? htmlspecialchars(strip_tags($data)) : null;
    }

    private function execute($stmt)
    {
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    private function bindParams($stmt, $includeFile = false)
    {
        $title = $this->clean($this->title);
        $description = $this->clean($this->description);
        $category = $this->clean($this->category);
        $deadline = $this->clean($this->deadline);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':deadline', $deadline);
        
        if ($includeFile) {
            $filename = $this->clean($this->filename);
            $filePath = $this->clean($this->file_path);
            $fileType = $this->clean($this->file_type);
            $fileSize = $this->clean($this->file_size);

        $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':file_path', $filePath);
            $stmt->bindParam(':file_type', $fileType);
            $stmt->bindParam(':file_size', $fileSize);
        }
    }

    public function create()
    {
        $query = "INSERT INTO {$this->table} SET title=:title, description=:description, category=:category, filename=:filename, file_path=:file_path, file_type=:file_type, file_size=:file_size, upload_date=NOW(), deadline=:deadline";
        $stmt = $this->conn->prepare($query);
        $this->bindParams($stmt, true);
        return $this->execute($stmt);
    }

    public function read($orderBy = 'upload_date', $order = 'DESC')
    {
        $query = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByCategory($category, $orderBy = 'upload_date', $order = 'DESC')
    {
        $query = "SELECT * FROM {$this->table} WHERE category = :category ORDER BY {$orderBy} {$order}";
        $stmt = $this->conn->prepare($query);
        $cleanCategory = $this->clean($category);
        $stmt->bindParam(':category', $cleanCategory);
        $stmt->execute();
        return $stmt;
    }

    public function readSingle()
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $cleanId = $this->clean($this->id);
        $stmt->bindParam(':id', $cleanId);

        if ($stmt->execute() && ($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            foreach ($row as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            $this->icon_type = $this->getFileIconType();
            return true;
        }
        return false;
    }

    public function update()
    {
        $hasFile = !empty($this->filename) && !empty($this->file_path);
        $fileFields = $hasFile ? ', filename=:filename, file_path=:file_path, file_type=:file_type, file_size=:file_size' : '';

        $query = "UPDATE {$this->table} SET title=:title, description=:description, category=:category, deadline=:deadline{$fileFields} WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $this->bindParams($stmt, $hasFile);
        $cleanId = $this->clean($this->id);
        $stmt->bindParam(':id', $cleanId);

        return $this->execute($stmt);
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $cleanId = $this->clean($this->id);
        $stmt->bindParam(':id', $cleanId);
        return $this->execute($stmt);
    }

    public function getCategories()
    {
        $query = "SELECT DISTINCT category FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'category');
        }

    public function getFileIconType()
    {
        if (empty($this->filename)) return 'file';
        
        $extension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
        return self::ICON_MAPPING[$extension] ?? 'file';
    }
}
