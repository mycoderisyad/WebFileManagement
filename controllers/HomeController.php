<?php
require_once 'models/File.php';

class HomeController extends Controller {
    public function landing() {
        include 'views/landing.php';
    }
    
    public function index() {
        $db = (new Database())->connect();
        $file = new File($db);
        $orderBy = $_GET['orderBy'] ?? 'upload_date';
        $order = $_GET['order'] ?? 'DESC';
        $result = $file->read($orderBy, $order);
        
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
            'orderBy' => $orderBy,
            'order' => $order
        ]);
    }
} 