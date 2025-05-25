<?php
class File {
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
    
    public function __construct($db) {
        $this->conn = $db;
        $this->icon_type = 'file';
    }
    
    private function cleanData($data) {
        return !empty($data) ? htmlspecialchars(strip_tags($data)) : null;
    }
    
    private function bindCommonParams($stmt) {
        $title = $this->cleanData($this->title);
        $description = $this->cleanData($this->description);
        $category = $this->cleanData($this->category);
        $deadline = $this->cleanData($this->deadline);
        
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':deadline', $deadline);
    }
    
    private function bindFileParams($stmt) {
        $filename = $this->cleanData($this->filename);
        $filepath = $this->cleanData($this->file_path);
        $filetype = $this->cleanData($this->file_type);
        $filesize = $this->cleanData($this->file_size);
        
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':file_path', $filepath);
        $stmt->bindParam(':file_type', $filetype);
        $stmt->bindParam(':file_size', $filesize);
    }
    
    private function executeQuery($stmt) {
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            printf("Error: %s.\n", $e->getMessage());
            return false;
        }
    }
    
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET
            title = :title,
            description = :description,
            category = :category,
            filename = :filename,
            file_path = :file_path,
            file_type = :file_type,
            file_size = :file_size,
            upload_date = NOW(),
            deadline = :deadline';
            
        $stmt = $this->conn->prepare($query);
        $this->bindCommonParams($stmt);
        $this->bindFileParams($stmt);
        
        return $this->executeQuery($stmt);
    }
    
    public function read($orderBy = 'upload_date', $order = 'DESC') {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY ' . $orderBy . ' ' . $order;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readByCategory($category, $orderBy = 'upload_date', $order = 'DESC') {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE category = :category ORDER BY ' . $orderBy . ' ' . $order;
        $stmt = $this->conn->prepare($query);
        $cleanCategory = $this->cleanData($category);
        $stmt->bindParam(':category', $cleanCategory);
        $stmt->execute();
        return $stmt;
    }
    
    public function getFileIconType() {
        if (empty($this->filename)) {
            return 'file';
        }
        
        $extension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
        
        $iconTypes = [
            'pdf' => 'pdf',
            'doc' => 'doc',
            'docx' => 'doc',
            'jpg' => 'img',
            'jpeg' => 'img',
            'png' => 'img',
            'svg' => 'img',
            'xls' => 'xls',
            'xlsx' => 'xls',
            'ppt' => 'ppt',
            'pptx' => 'ppt',
            'sql' => 'sql',
            'txt' => 'txt',
            'zip' => 'zip',
            'rar' => 'zip',
            '7z' => 'zip'
        ];
        
        return isset($iconTypes[$extension]) ? $iconTypes[$extension] : 'file';
    }
    
    public function readSingle() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = :id LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $cleanId = $this->cleanData($this->id);
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
    
    public function update() {
        $updateFile = !empty($this->filename) && !empty($this->file_path) && 
                     !empty($this->file_type) && !empty($this->file_size);
        
        $query = 'UPDATE ' . $this->table . ' SET
            title = :title,
            description = :description,
            category = :category,
            deadline = :deadline' .
            ($updateFile ? ',
            filename = :filename,
            file_path = :file_path,
            file_type = :file_type,
            file_size = :file_size' : '') .
            ' WHERE id = :id';
        
        $stmt = $this->conn->prepare($query);
        $this->bindCommonParams($stmt);
        $cleanId = $this->cleanData($this->id);
        $stmt->bindParam(':id', $cleanId);
        
        if ($updateFile) {
            $this->bindFileParams($stmt);
        }
        
        return $this->executeQuery($stmt);
    }
    
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $cleanId = $this->cleanData($this->id);
        $stmt->bindParam(':id', $cleanId);
        return $this->executeQuery($stmt);
    }
    
    public function getCategories() {
        $query = 'SELECT DISTINCT category FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $categories = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category'];
        }
        return $categories;
    }
} 