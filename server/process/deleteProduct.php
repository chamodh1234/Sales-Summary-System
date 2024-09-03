<?php 

session_start();
require '../middleware/middleware.php';
require '../config/db.php';

$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);
if(Middleware::checkTokenValidity()){
    if(empty($validationErrors)){
        if(isset($_POST['productName'])){
           // echo db::crud("DELETE FROM product WHERE product_id='".$_POST['productName']."'");
          $pro =  db::crud("SELECT product_id FROM product WHERE product_id = '".$_POST['productName']."'");
            $pro_row = $pro->num_rows;
            if( $pro_row > 0){
                try {
                       db::crud("DELETE FROM product WHERE product_id='".$_POST['productName']."'");
                       echo "product deleted";
                } catch (\Throwable $th) {
                    echo 'cannot delete product because it referencing something!';
                }
            
                
            }else{
                echo "No product in here called :".$_POST['productName'];
            }
            
        }else{
            echo 'product name not set';
        }
       
    }else{
        echo 'invalid characters contain';
    }
}else{
    echo 'Unauthorized token';
}
?>