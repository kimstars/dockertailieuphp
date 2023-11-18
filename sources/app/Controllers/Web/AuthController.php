<?php

declare (strict_types = 1);

require_once 'core/JWTAuth.php';
require_once 'app/Controllers/Controller.php';
require_once 'app/Requests/UserRequest.php';
require_once 'app/Requests/AuthRequest.php';
require_once 'app/Models/User.php';

class AuthController extends Controller
{
    public function register()
    {
        try {
            $this->setMethod('POST');
            $request = new UserRequest();
            $errors = $request->validateCreate($_POST);
            if (!empty($errors)) {
                throw new Exception(json_encode($errors));
            }
            $user_model = new User();
            $data = $this->getRequestData();
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            if ($user = $user_model->create($data)) {
                $token = JWTAuth::generateToken($user);
                $this->handleSuccessJsonResponse(['token' => $token], 'Register success!');
            } else {
                throw new Exception('Register failed!');
            }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function authorize()
    {
        try {
            $this->setMethod('POST');
            $request = new AuthRequest();
            $errors = $request->validateLogin($_POST);
            if (!empty($errors)) {
                throw new Exception(json_encode($errors));
            }
            $user_model = new User();
            $data = $this->getRequestData();
            if ($checkAuth = $user_model->attempt($data)) {
                $token = JWTAuth::generateToken($checkAuth);
                $this->handleSuccessJsonResponse(['token' => $token], 'Login sucesss!');
            } else {
                throw new Exception('Unauthorized!', 401);
            }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }
}
