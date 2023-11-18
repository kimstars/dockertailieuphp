<?php

require_once 'app/Controllers/Admin/BackendController.php';
require_once 'app/Requests/AdminRequest.php';
require_once 'app/Models/Admin.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';
class AdminController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware->handle();
    }

    public function index()
    {
        $this->setMethod('GET');
        $admin_model = new Admin();
        $users = $admin_model->all();
        return $this->view("/pages/admin/index.php", ['users' => $users]);
    }

    public function handleCreate()
    {
        $this->setMethod('POST');
        $request = new AdminRequest();
        $errors = $request->validateCreate($_POST);
        if (empty($errors)) {
            $admin_model = new Admin();
            $data = $_POST;
            $data['password'] = md5($data['password']);
            if ($admin_model->create($data)) {
                Flash::set('success', 'Tạo người dùng thành công!');
                return $this->ajax([]);
            }
        }
        return $this->ajax($errors, 500);
    }

    public function handleUpdate()
    {
        $this->setMethod('POST');
        $request = new AdminRequest();
        $errors = $request->validateUpdate($_POST);
        if (empty($errors)) {
            $admin_model = new Admin();
            $id = $_POST['id'];
            $data = $_POST;
            unset($data['email']);
            if (!empty($data['password'])) {
                $data['password'] = md5($data['password']);
            } else {
                $data['password'] = $data['oldPassword'];
            }
            $data['updated_at'] = date('Y-m-d H:i:s');
            if ($admin_model->update($data, $id)) {
                Flash::set('success', 'Cập nhật người dùng thành công!');
                return $this->ajax([]);
            }
        }
        return $this->ajax($errors, 500);
    }

    public function handleDelete()
    {
        $this->setMethod('GET');
        if (isset($_GET['id'])) {
            if (Auth::getUser('admin')['id'] == $_GET['id']) {
                Flash::set('error', 'Không thể xoá chính mình!');
                return redirect('admin/user');
            } else {
                $admin_model = new Admin();
                if ($admin_model->delete($_GET['id'])) {
                    Flash::set('success', 'Xoá người dùng thành công!');
                    return redirect('admin/user');
                }
            }
        };
    }

}
