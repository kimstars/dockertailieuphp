<?php

require_once 'app/Controllers/Web/WebController.php';
require_once 'app/Models/File.php';
class FileController extends WebController
{

    public function __construct()
    {
        // $this->middleware('ApiAuthenticationMiddleware');
    }
    public function index()
    {
        try {
            if ($this->isMethod('GET')) {
                if (!isset($_GET['id'])) {
                    throw new Exception('id not found!');
                }
                $id = $_GET['id'];
                if (!$id) {
                    throw new Exception('Invalid ID supplied', 400);
                }
                $file_model = new File();
                $file = $file_model->find($id);
                if (!$file) {
                    throw new Exception('file not found!', 404);
                }
                $this->handleSuccessJsonResponse($file);
            }
            // else if ($this->isMethod('POST')) {
            //     $this->handleSuccessJsonResponse("Created");
            // } else if ($this->isMethod('PUT')) {
            //     if (!isset($_GET['id']) || !$_GET['id']) {
            //         throw new Exception('Update failed, id not found!');
            //     }
            //     $this->handleSuccessJsonResponse("Updated");
            // } else if ($this->isMethod('DELETE')) {
            //     if (!isset($_GET['id']) || !$_GET['id']) {
            //         throw new Exception('Delete failed, id not found!');
            //     }
            //     $this->handleSuccessJsonResponse("Deleted");
            // }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function list()
    {
        try {
            $this->setMethod('GET');
            $file_model = new File();
            $files = $file_model->findAll();
            $this->handleSuccessJsonResponse($files);
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }
}
