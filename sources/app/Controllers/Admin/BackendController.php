<?php 

require_once('app/Middlewares/AdminMiddleware.php');
require('app/Controllers/Controller.php');
class BackendController extends Controller
{
    protected $middleware;
    public function __construct()
    {
        $this->middleware = new AdminMiddleware();
    }

    public function view($view, $data = []) 
    {
        return render("admin/$view", $data);
    }
}