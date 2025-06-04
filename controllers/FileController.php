<?php
require_once 'models/File.php';

class FileController extends Controller
{
    private const ACCEPTED_TYPES = [
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

    private const ALLOWED_EXTENSIONS = [
        'pdf',
        'doc',
        'docx',
        'jpg',
        'jpeg',
        'png',
        'svg',
        'xls',
        'xlsx',
        'csv',
        'ppt',
        'pptx',
        'sql',
        'txt',
        'zip',
        'rar',
        '7z'
    ];

    private const VIEWABLE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'pdf',
        'svg',
        'txt',
        'sql',
        'md',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

    private const MAX_FILE_SIZE = 5242880;

    private const PREVIEW_TYPES = [
        'image' => ['jpg', 'jpeg', 'png', 'svg'],
        'text' => ['txt', 'sql', 'md'],
        'word' => ['doc', 'docx'],
        'excel' => ['xls', 'xlsx'],
        'powerpoint' => ['ppt', 'pptx']
    ];

    private function getDb()
    {
        return (new Database())->connect();
    }

    private function validateFile($file)
    {
        $errors = [];
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed.';
            return $errors;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file['type'], self::ACCEPTED_TYPES) && !in_array($extension, self::ALLOWED_EXTENSIONS)) {
            $errors[] = 'Invalid file type. Accepted: PDF, DOC, DOCX, JPG, PNG, SVG, XLS, XLSX, PPT, PPTX, SQL, TXT, ZIP, RAR, 7Z.';
        }
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $errors[] = 'File size exceeds 5MB limit.';
        }
        return $errors;
    }

    private function validateForm($data)
    {
        $errors = [];
        if (empty($data['title'])) $errors[] = 'Title is required.';
        if (empty($data['category'])) $errors[] = 'Category is required.';
        return $errors;
    }

    private function uploadFile($file)
    {
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = 'uploads/' . $fileName;

        return move_uploaded_file($file['tmp_name'], $targetPath)
            ? ['success' => true, 'fileName' => $fileName, 'filePath' => $targetPath]
            : ['success' => false];
    }

    private function getPreviewType($extension)
    {
        foreach (self::PREVIEW_TYPES as $type => $extensions) {
            if (in_array($extension, $extensions)) return $type;
        }
        return $extension === 'pdf' ? 'pdf' : 'default';
    }

    private function setFileProperties($file, $data, $uploadResult = null)
    {
        $file->title = $data['title'];
        $file->description = $data['description'] ?? '';
        $file->category = ($data['category'] === 'new_category' && !empty($data['new_category']))
            ? $data['new_category'] : $data['category'];
        $file->deadline = $data['deadline'] ?? null;

        if ($uploadResult) {
            $file->filename = $uploadResult['fileName'];
            $file->file_path = $uploadResult['filePath'];
            $file->file_type = $data['file_type'];
            $file->file_size = $data['file_size'];
        }
    }

    private function handleFileById($id, $callback, $errorRedirect = '/app')
    {
        if (!$id) {
            $this->redirect($errorRedirect);
            return;
        }

        $file = new File($this->getDb());
        $file->id = $id;

        if ($file->readSingle()) {
            $callback($file);
        } else {
            $this->redirect($errorRedirect . '?message=File not found.');
        }
    }

    public function formatFileSize($bytes)
    {
        $units = ['bytes', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    public function uploadForm()
    {
        $file = new File($this->getDb());
        $this->view('upload_form', ['categories' => $file->getCategories()]);
    }

    public function saveFile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/app');
            return;
        }

        $uploadedFile = $_FILES['file'] ?? null;
        $errors = array_merge($this->validateFile($uploadedFile), $this->validateForm($_POST));

        if (!empty($errors)) {
            $file = new File($this->getDb());
            $this->view('upload_form', [
                'errors' => $errors,
                'categories' => $file->getCategories(),
                'formData' => $_POST
            ]);
            return;
        }

        $uploadResult = $this->uploadFile($uploadedFile);
        if (!$uploadResult['success']) {
            $this->redirect('/app?message=Failed to upload file.');
            return;
        }

        $file = new File($this->getDb());
        $this->setFileProperties($file, $_POST, $uploadResult);
        $file->file_type = $uploadedFile['type'];
        $file->file_size = $uploadedFile['size'];

        if ($file->create()) {
            $this->redirect('/app?message=File uploaded successfully.');
        } else {
            unlink($uploadResult['filePath']);
            $this->redirect('/app?message=Failed to save file record.');
        }
    }

    public function viewFile()
    {
        $this->handleFileById($_GET['id'] ?? null, function ($file) {
            $this->view('view_file', ['file' => $file]);
        });
    }

    public function previewFile()
    {
        $this->handleFileById($_GET['id'] ?? null, function ($file) {
            $extension = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));

            if (in_array($extension, self::VIEWABLE_EXTENSIONS)) {
                $this->view('preview', [
                    'file' => $file,
                    'preview_type' => $this->getPreviewType($extension)
                ]);
            } else {
                $this->redirect("/view?id={$file->id}&message=This file type cannot be viewed online.");
            }
        });
    }

    public function editForm()
    {
        $this->handleFileById($_GET['id'] ?? null, function ($file) {
            $this->view('edit_form', [
                'file' => $file,
                'categories' => $file->getCategories()
            ]);
        });
    }

    public function updateFile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/app');
            return;
        }

        $id = $_POST['id'] ?? null;
        $uploadedFile = !empty($_FILES['file']['name']) ? $_FILES['file'] : null;
        $errors = $this->validateForm($_POST);

        if ($uploadedFile) {
            $errors = array_merge($errors, $this->validateFile($uploadedFile));
        }

        $file = new File($this->getDb());
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
        $this->setFileProperties($file, $_POST);

        if ($uploadedFile) {
            $uploadResult = $this->uploadFile($uploadedFile);
            if (!$uploadResult['success']) {
                $this->redirect("/edit?id={$id}&message=Failed to upload new file.");
                return;
            }

            $file->filename = $uploadResult['fileName'];
            $file->file_path = $uploadResult['filePath'];
            $file->file_type = $uploadedFile['type'];
            $file->file_size = $uploadedFile['size'];
        }

        if ($file->update()) {
            if ($uploadedFile && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            $this->redirect('/app?message=File updated successfully.');
        } else {
            if ($uploadedFile && file_exists($file->file_path)) {
                unlink($file->file_path);
            }
            $this->redirect("/edit?id={$id}&message=Failed to update file.");
        }
    }

    public function deleteFile()
    {
        $this->handleFileById($_GET['id'] ?? null, function ($file) {
            $filePath = $file->file_path;
            if ($file->delete()) {
                if (file_exists($filePath)) unlink($filePath);
                $this->redirect('/app?message=File deleted successfully.');
            } else {
                $this->redirect('/app?message=Failed to delete file.');
            }
        });
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
        $db = $this->getDb();
        $file = new File($db);
        $result = $file->readByCategory($category, $orderBy, $order);

        $files = [];
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fileObj = new File($db);
                foreach ($row as $key => $value) {
                    $fileObj->$key = $value;
                }
                $row['icon_type'] = $fileObj->getFileIconType();
                $files[] = $row;
            }
        }

        $this->view('home', [
            'files' => $files,
            'categories' => $file->getCategories(),
            'currentCategory' => $category,
            'orderBy' => $orderBy,
            'order' => $order
        ]);
    }
}
