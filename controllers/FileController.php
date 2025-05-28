<?php
require_once 'models/File.php';

class FileController extends Controller
{
    private $acceptedFileTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/x-sql',
        'application/sql',
        'text/plain',
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/vnd.rar',
        'application/x-7z-compressed',
        'application/octet-stream'
    ];
    private $maxFileSize = 5242880;
    private $viewableExtensions = [
        'jpg', 'jpeg', 'png', 'pdf', 'svg',
        'txt', 'sql', 'md', 
        'doc', 'docx',   
        'xls', 'xlsx',    
        'ppt', 'pptx' 
    ];

    private function getDatabaseConnection()
    {
        $database = new Database();
        return $database->connect();
    }

    private function validateFile($uploadedFile) {
        $errors = [];
        if (!$uploadedFile || $uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed.';
        } else {
            $allowedExtensions = [
                'pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'svg',
                'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'sql', 'txt', 'zip', 'rar', '7z'
            ];
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
            if (
                !in_array($uploadedFile['type'], $this->acceptedFileTypes) &&
                !in_array($fileExtension, $allowedExtensions)
            ) {
                $errors[] = 'Invalid file type. Accepted types: PDF, DOC, DOCX, JPG, PNG, SVG, XLS, XLSX, PPT, PPTX, SQL, TXT, ZIP, RAR, 7Z.';
            }
            if ($uploadedFile['size'] > $this->maxFileSize) {
                $errors[] = 'File size exceeds the limit (5MB).';
            }
        }
        return $errors;
    }

    private function validateFormData($data)
    {
        $errors = [];
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['category'])) {
            $errors[] = 'Category is required.';
        }
        return $errors;
    }

    private function processFileUpload($uploadedFile)
    {
        $fileName = time() . '_' . basename($uploadedFile['name']);
        $targetFilePath = 'uploads/' . $fileName;

        if (move_uploaded_file($uploadedFile['tmp_name'], $targetFilePath)) {
            return [
                'success' => true,
                'fileName' => $fileName,
                'filePath' => $targetFilePath
            ];
        }
        return ['success' => false];
    }

    public function formatFileSize($bytes)
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

    private function getFileIconType($filename) {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
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
            'csv' => 'xls',
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
    public function uploadForm()
    {
        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $this->view('upload_form', ['categories' => $file->getCategories()]);
    }
    public function saveFile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/app');
            return;
        }

        $uploadedFile = $_FILES['file'] ?? null;
        $errors = array_merge(
            $this->validateFile($uploadedFile),
            $this->validateFormData($_POST)
        );

        if (!empty($errors)) {
            $db = $this->getDatabaseConnection();
            $file = new File($db);
            $this->view('upload_form', [
                'errors' => $errors,
                'categories' => $file->getCategories(),
                'formData' => $_POST
            ]);
            return;
        }

        $uploadResult = $this->processFileUpload($uploadedFile);
        if (!$uploadResult['success']) {
            $this->redirect('/app?message=Failed to upload file.');
            return;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);

        $file->title = $_POST['title'];
        $file->description = $_POST['description'] ?? '';
        $file->category = ($_POST['category'] === 'new_category' && !empty($_POST['new_category']))
            ? $_POST['new_category']
            : $_POST['category'];
        $file->filename = $uploadResult['fileName'];
        $file->file_path = $uploadResult['filePath'];
        $file->file_type = $uploadedFile['type'];
        $file->file_size = $uploadedFile['size'];
        $file->deadline = $_POST['deadline'] ?? null;

        if ($file->create()) {
            $this->redirect('/app?message=File uploaded successfully.');
        } else {
            unlink($uploadResult['filePath']);
            $this->redirect('/app?message=Failed to save file record.');
        }
    }

    public function viewFile()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/app');
            return;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $file->id = $id;

        if ($file->readSingle()) {
            $this->view('view_file', ['file' => $file]);
        } else {
            $this->redirect('/app?message=File not found.');
        }
    }

    public function previewFile()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/app');
            return;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $file->id = $id;

        if ($file->readSingle()) {
            $extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
            
            if (in_array($extension, $this->viewableExtensions)) {
                $preview_type = $this->getPreviewType($extension);
                $this->view('preview', [
                    'file' => $file,
                    'preview_type' => $preview_type
                ]);
            } else {
                $this->redirect('/view?id=' . $id . '&message=This file type cannot be viewed online. Please download it instead.');
            }
        } else {
            $this->redirect('/app?message=File not found.');
        }
    }

    public function editForm()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/app');
            return;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $file->id = $id;

        if ($file->readSingle()) {
            $this->view('edit_form', [
                'file' => $file,
                'categories' => $file->getCategories()
            ]);
        } else {
            $this->redirect('/app?message=File not found.');
        }
    }

    // Update file
    public function updateFile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/app');
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->redirect('/app');
            return;
        }

        $errors = $this->validateFormData($_POST);
        $uploadedFile = null;
        $newFileUploaded = false;

        if (!empty($_FILES['file']['name'])) {
            $uploadedFile = $_FILES['file'];
            $errors = array_merge($errors, $this->validateFile($uploadedFile));
            $newFileUploaded = true;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $file->id = $id;

        if (!empty($errors)) {
            if ($file->readSingle()) {
                $this->view('edit_form', [
                    'file' => $file,
                    'categories' => $file->getCategories(),
                    'errors' => $errors
                ]);
            } else {
                $this->redirect('/app?message=File not found.');
            }
            return;
        }
        if (!$file->readSingle()) {
            $this->redirect('/app?message=File not found.');
            return;
        }
        $oldFilePath = $file->file_path;
        $file->title = $_POST['title'];
        $file->description = $_POST['description'] ?? '';
        $file->category = ($_POST['category'] === 'new_category' && !empty($_POST['new_category']))
            ? $_POST['new_category']
            : $_POST['category'];
        $file->deadline = $_POST['deadline'] ?? null;

        if ($newFileUploaded) {
            $uploadResult = $this->processFileUpload($uploadedFile);
            if (!$uploadResult['success']) {
                $this->redirect('/edit?id=' . $id . '&message=Failed to upload new file.');
                return;
            }

            $file->filename = $uploadResult['fileName'];
            $file->file_path = $uploadResult['filePath'];
            $file->file_type = $uploadedFile['type'];
            $file->file_size = $uploadedFile['size'];
        }

        if ($file->update()) {
            if ($newFileUploaded && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            $this->redirect('/app?message=File updated successfully.');
        } else {
            if ($newFileUploaded && file_exists($file->file_path)) {
                unlink($file->file_path);
            }
            $this->redirect('/edit?id=' . $id . '&message=Failed to update file.');
        }
    }

    public function deleteFile()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/app');
            return;
        }

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $file->id = $id;

        if ($file->readSingle()) {
            $filePath = $file->file_path;
            if ($file->delete()) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $this->redirect('/app?message=File deleted successfully.');
            } else {
                $this->redirect('/app?message=Failed to delete file.');
            }
        } else {
            $this->redirect('/app?message=File not found.');
        }
    }

    public function filterByCategory()
    {
        $category = $_GET['category'] ?? '';
        if (empty($category)) {
            $this->redirect('/app');
            return;
        }

        $orderBy = $_GET['orderBy'] ?? 'upload_date';
        $order = $_GET['order'] ?? 'DESC';

        $db = $this->getDatabaseConnection();
        $file = new File($db);
        $result = $file->readByCategory($category, $orderBy, $order);
        
        if ($result && $result->rowCount() > 0) {
            $files = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fileObj = new File($db);
                foreach ($row as $key => $value) {
                    $fileObj->$key = $value;
                }
                $row['icon_type'] = $fileObj->getFileIconType();
                $files[] = $row;
            }
        } else {
            $files = [];
        }

        $this->view('home', [
            'files' => $files,
            'categories' => $file->getCategories(),
            'currentCategory' => $category,
            'orderBy' => $orderBy,
            'order' => $order
        ]);
    }

    private function getPreviewType($extension) {
        $imageTypes = ['jpg', 'jpeg', 'png', 'svg'];
        $textTypes = ['txt', 'sql', 'md'];
        
        if (in_array($extension, $imageTypes)) {
            return 'image';
        } elseif ($extension === 'pdf') {
            return 'pdf';
        } elseif (in_array($extension, $textTypes)) {
            return 'text';
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return 'word';
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return 'excel';
        } elseif (in_array($extension, ['ppt', 'pptx'])) {
            return 'powerpoint';
        }
        
        return 'default';
    }
}
