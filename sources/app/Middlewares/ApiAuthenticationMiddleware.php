<?php


require_once 'app/Middlewares/BaseMiddleware.php';
require_once 'core/JWTAuth.php';

class ApiAuthenticationMiddleware extends BaseMiddleware
{
    public function handle($parameters = null)
    {   
        if (isset($parameters['excepts']) && isset($_GET['action']) && in_array($_GET['action'], $parameters['excepts'])) {
            return true;
        } else {
            JWTAuth::middleware();
        }
    }
}
