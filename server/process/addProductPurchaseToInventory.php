<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';

$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);



if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {
        if (isset($_POST['productName']) && isset($_POST['productCount'])) {
            $is_exist = db::crud("SELECT id FROM inventory_main WHERE product_product_id='" . $_POST['productName'] . "'");
            $date = db::crud("SELECT id FROM `date` WHERE `date` = '" . date('Y-m-d') . "'");
            $Date_id =  $date->fetch_assoc();
            if ($date->num_rows > 0) {
                if ($is_exist->num_rows > 0) {
                    db::crud("UPDATE inventory_main SET stock=stock+'" . $_POST['productCount'] . "' WHERE product_product_id='" . $_POST['productName'] . "'  ");
                    db::crud("INSERT INTO purchase(`purchase`,`product_product_id`,`date_id`) VALUES('" . $_POST['productCount'] . "','" . $_POST['productName'] . "','" . $Date_id['id'] . "')");
                    echo "update";
                } else {
                    db::crud("INSERT INTO inventory_main(`stock`,`product_product_id`) VALUES ('" . $_POST['productCount'] . "','" . $_POST['productName'] . "')");
                    db::crud("INSERT INTO purchase(`purchase`,`product_product_id`,`date_id`) VALUES('" . $_POST['productCount'] . "','" . $_POST['productName'] . "','" . $Date_id['id'] . "')");
                    echo "insert";
                }
            } else {
                db::crud("INSERT INTO `date`(`date`) VALUES('" . date("Y-m-d") . "')");
                $DATE = db::crud("SELECT id FROM `date` WHERE `date` = '" . date('Y-m-d') . "'");
                $DATE_ID =  $DATE->fetch_assoc();

                if ($is_exist->num_rows > 0) {
                    db::crud("UPDATE inventory_main SET stock=stock+'" . $_POST['productCount'] . "' WHERE product_product_id='" . $_POST['productName'] . "'  ");
                    db::crud("INSERT INTO purchase(`purchase`,`product_product_id`,`date_id`) VALUES('" . $_POST['productCount'] . "','" . $_POST['productName'] . "','" . $DATE_ID['id'] . "')");
                    echo "update";
                } else {
                    db::crud("INSERT INTO inventory_main(`stock`,`product_product_id`) VALUES ('" . $_POST['productCount'] . "','" . $_POST['productName'] . "')");
                    db::crud("INSERT INTO purchase(`purchase`,`product_product_id`,`date_id`) VALUES('" . $_POST['productCount'] . "','" . $_POST['productName'] . "','" . $DATE_ID['id'] . "')");
                    echo "update";
                }
            }
        }
    } else {
       echo 'Unwanted characters contains';
    }
} else {
    echo ' Unauthorized Token';
}
