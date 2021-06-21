<?php

class AppResponse extends Controller {

    private $response = [
        'status'=> "ok",
        'result'=> array()
    ];

    public function response_200($msg) {
        $this->send_response('ok', '200', $msg);       
    }

    public function error_200($msg = "Datos incorrectos") {
        $this->send_response('error', '200', $msg);       
    }

    public function error_400() {
        $this->send_response('error', '400', "Incorrect data format");
    }

    public function error_404() {
        $this->send_response('error', '404', "Page not found");
    }

    public function error_405() {
        $this->send_response('error', '405', "Method not allowed");
    }
    
    public function error_500($msg) {
        $this->send_response('error', '500', $msg);
    }
    
    private function send_response ($status, $status_id, $msg) {
        header('Content-Type: application/json');
        $this->response['status'] = $status;
        $this->response['result'] = array(
            'status_id' => $status_id,
            'message' => $msg
        );
        http_response_code($this->response['result']['status_id']);
        echo json_encode($this->response);                   
    }
    
}