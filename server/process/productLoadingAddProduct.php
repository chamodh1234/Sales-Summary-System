<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';
$product_name;
$lorry_number;


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {

        if (isset($_POST['productName']) && isset($_POST['lorryNumber'])) {
            $product_name = $_POST['productName'];
            $lorry_number = $_POST['lorryNumber'];

            $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . date('Y-m-d') . "' ");
            $Date_id = $date_id->fetch_assoc();

            $daily_lorry_id = db::crud("SELECT id FROM daily_lorry_loading WHERE date_id='".$Date_id['id']."' AND lorry_lorry_number='".$_POST['lorryNumber']."'");
            $lorry_id = $daily_lorry_id->fetch_assoc();
            // need to add product id
            $is_row_exitst = db::crud("SELECT `daily_lorry_loading_id` FROM summary_sheet WHERE product_product_id='" . $product_name . "' AND date_id = '" . $Date_id['id'] . "' AND daily_lorry_loading_id='".$lorry_id['id']."'");

            if ($is_row_exitst->num_rows > 0) {
                echo "Product Already Added";
            } else {
                $lorry = db::crud("SELECT id,date_id FROM daily_lorry_loading WHERE `date_id`='" . $Date_id['id'] . "' AND lorry_lorry_number = '" . $lorry_number . "'");
                $lorry_data = $lorry->fetch_assoc();

                db::crud("INSERT INTO summary_sheet(`daily_lorry_loading_id`,`date_id`,`product_product_id`,`lorry_lorry_number`) VALUES ('" . $lorry_data['id'] . "','" . $lorry_data['date_id'] . "','" . $product_name . "','" . $_POST['lorryNumber'] . "')");
                echo "Product Added";
            }
        } else {
        }
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
