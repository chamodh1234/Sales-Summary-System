<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {

        if (isset($_POST['productName']) && isset($_POST['unitPrice'])) {
            $result = db::crud("INSERT INTO product(`product_id`,`unit_price`) VALUES ('" . $_POST['productName'] . "','" . $_POST['unitPrice'] . "')");
            if ($result) {
                echo 'done';
            }
        }
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
