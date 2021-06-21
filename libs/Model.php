<?php

class Model {

    function __construct()
    {
        $this->db = new Database();
    }

    public function getData($stmt) {

        $arr = array();
        $arr['data'] =  array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            array_push($arr['data'], $row);
        }

        return $arr;

    }

    public function getDataRow($stmt) {

        $data = $this->getData($stmt);
        if (sizeof($data['data']) > 0)
            return $data['data'][0];
        return false;

    }
}
?>