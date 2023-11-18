<?php 

require_once('app/Controllers/Admin/BackendController.php');
require_once('app/Requests/UserRequest.php');
require_once('app/Models/User.php');
require_once('app/Models/Dashboard.php');
require_once('core/Flash.php');
require_once('core/Auth.php');
class DashboardController extends BackendController
{
    public function __construct()
    {
        parent::__construct ();
        $this->middleware->handle();
    }

    public function index() 
    {   
        $model = new Dashboard();
        $sellings = $model->sellings();
        $data = [];
        $data['countProduct'] = $model->countProduct();
        $data['countCategory'] = $model->countCategory();
        $data['countOrder1M'] = $model->countOrder1M();
        $data['totalOrder1M'] = $model->totalOrder1M();
        return $this->view("pages/dashboard/index.php", ['data' => $data, 'sellings' => $sellings]);
    }

}