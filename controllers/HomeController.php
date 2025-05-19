<?php
require_once 'models/File.php';

class HomeController extends Controller {
    public function landing() {
        include 'views/landing.php';
    }
    
    public function index() {
        $database = new Database();
        $db = $database->connect();
        $file = new File($db);
        $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'upload_date';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $result = $file->read($orderBy, $order);
        
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
        
        $categories = $file->getCategories();
        $this->view('home', [
            'files' => $files,
            'categories' => $categories,
            'orderBy' => $orderBy,
            'order' => $order
        ]);
    }
} 