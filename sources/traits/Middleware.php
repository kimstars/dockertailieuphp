<?php 

trait Middleware 
{
    /**
     * Gọi middleware vào controller
     *
     * @param [type] $middleware: Tên file middleware được gọi, bỏ đuôi php. Ví dụ: Authenticated
     * @param array $params: Tham số truyền sang middleware
     * @return [void]
     */
    public function middleware($middleware, $params = [])
    {
        require_once("app/Middlewares/{$middleware}.php");
        $middlewareClass = new $middleware();
        return call_user_func(array($middlewareClass, 'handle'), $params);
    }
}