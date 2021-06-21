<?php

include_once 'models/CategoryModel.php';

class CategoryController extends Controller {

    public function store()
    {
        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: category');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorizaation, X-Requested-With');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate blog category object
        $category = new CategoryModel($db);

        // Get the row categoryed data
        $data = json_decode(file_get_contents("php://input"));

        if (isset($data->category_name)) 
            $category->category_name = $data->category_name ? $data->category_name : null;

        
        // Create category
        $result = new AppResponse();
        if ($id = $category->create($data)) 
            $result->response_200('Category created, Id: ' . $id);
        else
            $result->error_200();
    }

    public function show($id)
    {
    //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate blog category object
        $category = new CategoryModel($db);

        // Get category
        $category->getSingle($id);

        if ($category->category_id) {
            // Create array
            $category_array = array(
                'category_id' => $category->category_id,
                'category_name' => $category->category_name,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
            );

            // Make JSON
            echo json_encode($category_array);        
        } else {
            // No Categories
            $result = new AppResponse();
            $result->error_200("Category not found");         
        }
    }

    public function index()
    {
        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        // load model object
        $category = new CategoryModel($db);

        // get result query
        $result = $category->get($page);

        //  get row count
        $num = $result ? sizeof($result['data']) : 0;

        //Check if any category 
        if ($num > 0) {
           // Turn to JSON && output
           echo json_encode($result);
        } else {
            // No categorys
            $result = new AppResponse();
            $result->error_200("No categories found");   
        }        
    }

    public function update($id)
    {
        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: PUT');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorizaation, X-Requested-With');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate category object
        $category = new CategoryModel($db);

        // Get category
        $category->getSingle($id);

        $result = new AppResponse();

        if ($category->category_id) {

            // Get the row category data
            $data = json_decode(file_get_contents("php://input"));

            // Set ID to update
            if ($data) 
                $category->category_name = $data->category_name ? $data->category_name : null;

            // Update category
            if ($category->update($data)) {
                $result->response_200("Category updated");         
            } else {
                $result->error_200("Category not updated");         
            }            

        } else {
            // No Categories
            $result->error_200("Category not found");
        }

    }

    public function destroy($id)
    {
        //Headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Methods: DELETE');
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorizaation, X-Requested-With');

        // Instantiate DB && connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate blog category object
        $category = new categoryModel($db);

        // Get category
        $category->getSingle($id);

        // Delete category
        $result = new AppResponse();

        if ($category->category_id) {
            
            //Category found
            if ($category->delete()) {
            
                // category deleted
                $result->response_200("Category deleted");         
            } else {
            
                // category not deleted
                $result->error_200("Category not deleted");         
            }              
        } else {
            // Not found
            $result->error_200("Category not found");        
        }

    } 

}

?>