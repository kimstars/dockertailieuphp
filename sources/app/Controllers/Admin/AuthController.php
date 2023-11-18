<?php

require 'app/Controllers/Admin/BackendController.php';
require_once 'app/Requests/AuthRequest.php';
require_once 'app/Models/Admin.php';
require_once 'core/Flash.php';
require_once 'core/Auth.php';
class AuthController extends BackendController
{
    public function login()
    {
        $this->setMethod('GET');
        if (Auth::loggedIn('admin')) {
            return redirect('admin/category');
        }
        return $this->view("pages/admin/login.php");
    }

    public function handleLogin()
    {
        $this->setMethod('POST');
        $request = new AuthRequest();
        $errors = $request->validateLogin($_POST);
        if (!empty($errors)) {
            return redirect('admin/auth/login');
        }
        $admin_model = new Admin();
        $data = $_POST;
        $check_auth = $admin_model->first($data);
        if (!$check_auth) {
            Flash::set('error', 'Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại!');
            return redirect('admin/auth/login');
        } else {
            $remember = isset($_POST['rememberMe']) ? true : false;
            Auth::setUser('admin', $check_auth, $remember);
            Flash::set('success', 'Đăng nhập thành công!');
            return redirect('admin/category');
        }
    }
    public function logout()
    {
        $this->setMethod('GET');
        Auth::logout('admin');
        Flash::set('success', 'Đăng xuất thành công!');
        return redirect('admin/auth/login');
    }

}
