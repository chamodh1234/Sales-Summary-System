<?php 

session_start();
require '../middleware/middleware.php';
require '../config/db.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);
if(Middleware::checkTokenValidity()){
    if(empty($validationErrors)){
        if(isset($_POST['connect']) && isset($_POST['to'])){
            db::crud("UPDATE product SET `connect`='".$_POST['to']."' WHERE product_id='".$_POST['connect']."'");
            echo 'connect'; 
        }else{
            echo 'data not set';
        }
       
    }else{
        echo 'invalid characters contain';
    }
}else{
    echo 'Unauthorized token';
}

?>