<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'app/Models/User.php';
class JWTAuth
{
    public static function generateToken($user)
    {
        $secretKey = env('SECRET_KEY');
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify("+" . env('JWT_EXPIRE', 0) . " minutes");
        $serverName = env('SEVER_NAME');
        $data = [
            'iss' => $serverName, // Issuer
            'iat' => $issuedAt->getTimestamp(), // Issued at: time when the token was generated
            'nbf' => $issuedAt->getTimestamp(), // Not before
            'exp' => $expire->getTimestamp(), // Expire
            'email' => $user['email'], // User name
            'user_id' => $user['id'], // User id
        ];
        return JWT::encode(
            $data,
            $secretKey,
            'HS256',
        );
    }

    public static function getUser()
    {
        try {
            if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                throw new Exception('Token not found in request');
            }
            $jwt = $matches[1];
            if (!$jwt) {
                throw new Exception('No token was able to be extracted from the authorization header');
            }
            $token = JWT::decode($jwt, new Key(env('SECRET_KEY'), 'HS256'));
            $email = $token->email;
            $user_id = $token->user_id;
            $user_model = new User;
            $user = $user_model->findByEmailAndId($email, $user_id);
            if (!$user) {
                throw new Exception('User not found!');
            }
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function middleware()
    {
        try {
            if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
                throw new Exception('Token not found in request');
            }
            $jwt = $matches[1];
            if (!$jwt) {
                throw new Exception('No token was able to be extracted from the authorization header');
            }
            $token = JWT::decode($jwt, new Key(env('SECRET_KEY'), 'HS256'));
            $now = new DateTimeImmutable();
            $serverName = env('SEVER_NAME');
            if ($token->iss !== $serverName ||
                $token->nbf > $now->getTimestamp() ||
                $token->exp < $now->getTimestamp()) {
                throw new Exception('Unauthorized');
            }
        } catch (Exception $e) {
            header('HTTP/1.0 400 Bad Request');
            http_response_code(401);
            echo $e->getMessage();
            exit;
        }
    }
}
