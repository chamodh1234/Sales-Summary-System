<?php
session_start();
require '../middleware/middleware.php';
require '../config/db.php';

$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {

        if (isset($_POST['productName']) && isset($_POST['productCount'])) {

            $is_exist = db::crud("SELECT id FROM inventory_main WHERE product_product_id='" . $_POST['productName'] . "'");
            if ($is_exist->num_rows > 0) {
                db::crud("UPDATE inventory_main SET stock=stock+'" . $_POST['productCount'] . "' WHERE product_product_id='" . $_POST['productName'] . "'  ");
                echo "update";
            } else {
                db::crud("INSERT INTO inventory_main(`stock`,`product_product_id`) VALUES ('" . $_POST['productCount'] . "','" . $_POST['productName'] . "')");
                echo "insert";
            }
        }
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
