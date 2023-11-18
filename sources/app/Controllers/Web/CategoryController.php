<?php

require_once 'app/Controllers/Web/WebController.php';
require_once 'app/Models/Category.php';
class CategoryController extends WebController
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
                $recursive = isset($_GET['recursive']) ? filter_var($_GET['recursive'], FILTER_VALIDATE_BOOLEAN) : false;
                if (!$id) {
                    throw new Exception('Invalid ID supplied', 400);
                }
                $category_model = new Category();
                $category = $recursive ? $category_model->recursiveUser($id) : $category_model->find($id);
                if (!$category) {
                    throw new Exception('Category not found!', 404);
                }
                $this->handleSuccessJsonResponse($category);
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
            $recursive = isset($_GET['recursive']) ? filter_var($_GET['recursive'], FILTER_VALIDATE_BOOLEAN) : false;
            $category_model = new Category();
            $categories = $recursive ? $category_model->recursiveUser() : $category_model->listActive();
            $this->handleSuccessJsonResponse($categories);
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }
}
