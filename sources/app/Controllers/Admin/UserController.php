<?php 

require_once('app/Controllers/Admin/BackendController.php');
require_once('app/Requests/UserRequest.php');
require_once('app/Models/User.php');
require_once('core/Flash.php');
require_once('core/Auth.php');
class UserController extends BackendController
{
    public function __construct()
    {
        parent::__construct ();
        $this->middleware->handle();
    }

    public function index() 
    {   
        $this->setMethod('GET');
        $user_model = new User();
        $users = $user_model->pagination(10);
        return $this->view("/pages/user/index.php", $users);
    }

    public function status()
    {
        $this->setMethod('GET');
        $request = new UserRequest();
        $errors = $request->validateStatus($_GET);
        if (empty($errors)) {
            $user_model = new User();
            $user_model->updateStatus($_GET);
            Flash::set('success', 'Cập nhật người dùng thành công!');
        }
        return back();
    }
}