<?php

include_once 'database/Database.php';
require_once 'controllers/AppResponse.php';

class App {

    private $controller_name;
    private $controller_path;
    private $method;
    private $url_params_number = 0;
    private $url_params = NULL;

    function __construct()
    {
        $url = $this->get_url_array();
        $this->setControllerAttributes($url);
        $this->setControllerURLParameters($url);

        if (file_exists($this->controller_path)) {

            require_once $this->controller_path;
            $controller = new $this->controller_name;

            if ($this->method == 'GET') {
                
                if ($this->url_params_number == 0) {
                    // LIST
                    $controller->index();
                }
                else if ($this->url_params_number == 1) {
                    // SHOW id = $url[1]
                    $controller->show($this->url_params[0]);
                }
                else {
                    // Error Path Not Found
                    $result = new AppResponse();
                    $result->error_404();
                }
            } else if ($this->method == 'POST') {

                if ($this->url_params_number == 0) {

                    if ($this->controller_name != 'AuthController') {
                        // CREATE
                        $controller->store();                        
                    } else {
                        //     // Login user
                        //     if ($url[0] == 'auth')
                        //         $controller->login();
                        //     else
                        //         $result = false;
                    }
                }

            } else if ($this->method == 'PUT') {
                
                if ($this->url_params_number == 1) {
                    // UPDATE id = $url[1]
                    $controller->update($this->url_params[0]);
                } else {
                    $result = new AppResponse();
                    $result->error_404();                   
                }                

            } else if ($this->method == 'DELETE') {

                if ($this->url_params_number == 1) {
                    // DELETE id = $url[1]
                    $controller->destroy($this->url_params[0]);
                } else {
                    $result = new AppResponse();
                    $result->error_404();                   
                }                
                    
            } else {
                $result = new AppResponse();
                $result->error_405();                   
            }
        } else {
            $result = new AppResponse();
            $result->error_404();            
        }

    }

    private function get_url_array() {

        $url = isset($_GET['url']) ? $_GET['url'] : NULL;
        $url = rtrim($url, '/');
        $url = explode('/', $url);
        return $url;

    }

    private function setControllerAttributes($url) {
        $api =  ($url[0] == 'api')  ? 'api/' : '';
        // controller name
        if ($url[1] == 'auth')
            $this->controller_name = 'AuthController';
        else {
            if (substr($url[1], -3) == 'ies')
                $this->controller_name = ucfirst(substr($url[1], 0, -3)) . 'yController';
            else
                $this->controller_name = ucfirst(substr($url[1], 0, -1)) . 'Controller';
        }
            
        // controller file
        $this->controller_path = 'controllers/' . $api . $this->controller_name . '.php';
        // controller method
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    private function setControllerURLParameters($url) {
        if (sizeof($url) > 2) {
            $this->url_params_number = sizeof($url) - 2;
            $this->url_params = [];
            for ($i=2; $i < sizeof($url); $i++) {
                array_push($this->url_params, $url[$i]);
            }
        }        
    }
} 

?>