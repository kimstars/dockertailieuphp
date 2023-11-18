<?php

require_once 'app/Controllers/Controller.php';

class WebController extends Controller
{

    public function index()
    {
        return render("web/index.php", []);
    }

    public function documentation()
    {
        return render("web/pages/api-document.php", []);
    }
    public function upload()
    {
        return render("web/pages/demoupload.php", []);
    }

    public function policy()
    {
        return render("web/pages/policy-private.php", []);
    }

    public function removeFBapp()
    {
        return render("web/pages/guide-remove-fbapp.php", []);
    }

    public function versionDocumentation()
    {
        try {
            $version = $_GET['version'];
            $path = "config/documentation-$version.json";
            if (file_exists($path)) {
                $json_data = file_get_contents($path);
                $data = json_decode($json_data);
                $data->servers[0]->url = env('API_URL');
                $json_data = json_encode($data);
                echo $json_data;
            } else {
                throw new Exception('Documentation version not found!', 404);
            }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }
}
