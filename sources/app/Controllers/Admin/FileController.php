<?php

require_once 'app/Controllers/Admin/BackendController.php';
require_once 'app/Requests/DocumentRequest.php';
require_once 'app/Models/Document.php';
require_once 'app/Models/Category.php';
require_once 'core/Flash.php';
require_once 'core/Storage.php';
require_once 'core/Auth.php';
require_once 'app/Services/UploadService.php';
class FileController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware->handle();
    }

    public function index()
    {
        $this->setMethod('GET');
        $uploadService = new UploadService;
        $list = $uploadService->list();
        foreach ($list as $value) {
            $url = url('admin/file/delete', ['id' => $value['id']]);
            echo '<p><a href="https://drive.google.com/open?id='.$value['id'].'" target="_blank">' . $value['name']. '</a> 
                    '.$value['id'].'=====> <a href="'.$url.'">delete</a></p>';
        }
    }

    public function delete()
    {
        $this->setMethod('GET');
        $uploadService = new UploadService;
        $id = $_GET['id'];
        $uploadService->deleteByUploadId($id);
        return back();
    }
}
