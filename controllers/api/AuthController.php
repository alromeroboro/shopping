<?php 

    include_once 'models/AuthModel.php';

class AuthController extends Controller {

    public function login() {

        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorizaation, X-Requested-With');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate blog post object
        $user = new AuthModel($db);

        // Get the row posted data
        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            $email = $data->email ? $data->email : null;
            $password = $data->password ? $data->password : null;;    
        }

        // Search user
        if ($user->getUser($email, $password)) {

            if ($this->encripted($password) == $user->password) {
                if ($user->status == 'active') {
                    if ($token = $user->insertToken()) {
                        return $token;
                    } else {
                        echo json_encode(
                            array(
                                'message'=> 'Server error'    
                            )
                        );                           
                    }
                } else {
                    echo json_encode(
                        array(
                            'message'=> 'Inactive User'    
                        )
                    );                 
               }
            } else {
                echo json_encode(
                    array(
                        'message'=> 'Invalid password'    
                    )
                );                 
            }
            echo json_encode(
                array(
                    'message'=> 'User found',
                    // 'data' => $user_arr    
                )
            );
        } else {
            echo json_encode(
                array(
                    'message'=> 'User not found'    
                )
            );        
        }   

    }

    //  Encript password
    private function encripted($password) {
        return md5($password);
    }

}


?>