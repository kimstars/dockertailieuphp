<?php 

require_once('app/Middlewares/BaseMiddleware.php');
require_once('core/Auth.php');

class AdminMiddleware extends BaseMiddleware
{
    public function handle($parameters = null)
    {
        if (!Auth::loggedIn('admin')) {
            return redirect('admin/auth/login');
        }
        return true;
    }
}