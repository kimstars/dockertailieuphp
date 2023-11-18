<?php
require './traits/Middleware.php';

class Controller
{
    use Middleware;

    public function ajax($data = [], $statusCode = 200)
    {
        http_response_code($statusCode);
        if ($statusCode >= 200 && $statusCode <= 300) {
            echo json_encode([
                'status' => 1,
                'data' => $data,
            ]);
            return;
        }
        echo json_encode([
            'status' => 0,
            'data' => $data,
        ]);
    }

    /**
     * handleExceptionJsonResponse function
     *
     * @param Exception $e
     * @return void
     */
    public function handleExceptionJsonResponse(Exception $e)
    {
        http_response_code($e->getCode() == 0 ? 500 : $e->getCode());
        echo json_encode([
            'errors' => $e->getMessage(),
            // 'code' => $e->getCode(),
        ]);
        return;
    }

    /**
     * handleSuccessJsonResponse function
     *
     * @param mixed $data
     * @param string $message
     * @return void
     */
    public function handleSuccessJsonResponse($data = null, string $message = '')
    {
        http_response_code(200);
        $res = [];
        if ($message) {
            $res = [
                'message' => $message,
                'data' => $data,
            ];
        } else {
            $res = $data;
        }
        echo json_encode($res);
        return;
    }

    public function setMethod($method)
    {
        $method = strtoupper($method);
        if ($method !== $_SERVER['REQUEST_METHOD']) {
            throw new Exception("The {$_SERVER['REQUEST_METHOD']} method is not supported for this route. Supported methods: $method.");
        }
    }

    public function isMethod($method)
    {
        return $_SERVER['REQUEST_METHOD'] == strtoupper($method);
    }

    public function getRequestData()
    {
        if ($this->isMethod('GET')) {
            return $_GET;
        }
        if ($this->isMethod('POST')) {
            return $_POST;
        }
        if ($this->isMethod('PUT')) {
            parse_str(file_get_contents("php://input"),$putData); 
            return $putData;
        }
    }

}
