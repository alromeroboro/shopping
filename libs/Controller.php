<?php

class Controller {

    function loadModel($model, $db) {
        $url = 'models/' . $model .'.php';

        if (file_exists($url )) {
            require($url);
            $this->model = new $model($db); 
        }
    }

}
?>