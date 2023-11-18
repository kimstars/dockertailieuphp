<?php

require_once 'app/Controllers/Admin/BackendController.php';
require_once 'app/Requests/CategoryRequest.php';
require_once 'app/Models/Category.php';
require_once 'app/Models/Pagination.php';
require_once 'core/Flash.php';
require_once 'core/Storage.php';
require_once 'core/Auth.php';
class CategoryController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware->handle();
    }

    public function index()
    {
        $this->setMethod('GET');
        $categories_model = new Category();
        $categories = $categories_model->recursiveAdmin();
        return $this->view("pages/category/index.php", ['categories' => $categories]);
    }

    public function handleCreate()
    {
        $this->setMethod('POST');
        $request = new CategoryRequest();
        $errors = $request->validateCreate($_POST);
        if (empty($errors)) {
            $category_model = new Category();
            $data = $_POST;
            if ($category_model->create($data)) {
                Flash::set('success', 'Tạo danh mục thành công!');
                return $this->ajax([]);
            }
        }
        return $this->ajax($errors, 500);
    }

    public function handleUpdate()
    {
        $this->setMethod('POST');
        $request = new CategoryRequest();
        $errors = $request->validateUpdate($_POST);
        if (empty($errors)) {
            $id = $_POST['id'];
            $category_model = new Category();
            $data = array_merge($_POST, ['updated_at' => now()]);
            if ($category_model->update($data, $id)) {
                Flash::set('success', 'Cập nhật danh mục thành công!');
                return $this->ajax([]);
            }
        }
        return $this->ajax($errors, 500);
    }

    public function status()
    {
        $this->setMethod('GET');
        $request = new CategoryRequest();
        $errors = $request->validateStatus($_GET);
        if (empty($errors)) {
            $category_model = new Category();
            $category_model->updateStatusRecursive($_GET);
            Flash::set('success', 'Cập nhật trạng thái danh mục thành công!');
        }
        return back();
    }

    public function handleDelete()
    {
        $this->setMethod('GET');
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $category_model = new Category();
            $category_model->deleteRecursive($id);
            Flash::set('success', 'Xoá danh mục thành công!');
            return back();
        };
    }

}
