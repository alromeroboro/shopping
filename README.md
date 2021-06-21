# SHOPPING
Shopping app API. PHP and MySQL (No Framework)

#### REQUISITOS

 - PHP 7.3
 - Mysql o MariaDB

#### INSTALATION

 - Clonar el repositorio.

#### DATABASES

 - Create a new database.
 - Update conection information in /config/config.php
 - Execute query with the file /database/product_category

#### ENDPOINTS

- GET     
    - /api/categories
    - json response: 20 first categories ordered by name.
- GET     
    - /api/categories/?page=1
    - json response: 20 categories per page ordered by name.
- GET
    - /api/categories/{id}
    - json response: Category info ID = {id}
- POST    
    - /api/categories
    - json format: {"category_name" = "This is the category name"}
    - response: id of the new category
- PUT
    - /api/categories/{id}
    - json format: {"category_name" = "New category name"}
    - response: Updated/Not Updated message
- DELETE  
    - /api/categories/{id}
    - response: Deleted/Not Deleted message
