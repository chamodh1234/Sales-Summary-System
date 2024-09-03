<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {

    if (empty($validationErrors)) {

        if (isset($_POST['submit']) && isset($_POST['lorryNumber']) && isset($_POST['productName']) && isset($_POST['loading']) && $_POST['loading'] != '') {
            $product_name = $_POST['productName'];
            $lorry_number = $_POST['lorryNumber'];
            $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . date('Y-m-d') . "' ");
            $Date_id = $date_id->fetch_assoc();
            $lorry = db::crud("SELECT id,date_id FROM daily_lorry_loading WHERE `date_id`='" . $Date_id['id'] . "' AND lorry_lorry_number = '" . $lorry_number . "'");
            $lorry_data = $lorry->fetch_assoc();

            $is_connect = db::crud("SELECT `product_product_id` FROM product WHERE product_id='" . $_POST['productName'] . "'");
            $product_id;
            $connect_product = $is_connect->fetch_assoc();
            if ($is_connect->num_rows > 0) {
                if ($connect_product['product_product_id'] != null) {
                    $product_id = $connect_product['product_product_id'];
                } else {
                    $product_id = $_POST['productName'];
                }
            }


            $is_stock = db::crud("SELECT `id`,`stock` FROM inventory_main WHERE product_product_id='" . $product_id . "'");
            $stock = $is_stock->fetch_assoc();



            if ($_POST['submit'] == 'load') {

                if ($is_connect->num_rows > 0) {
                    if ($connect_product['product_product_id'] == null) {
                        if ($is_stock->num_rows == 0) {
                            echo "Before loading stock , insert stock to the inventory";
                        } else if ($stock['stock'] < $_POST['loading']) {
                            echo "Not enough stock available";
                        } else {
                            db::crud("UPDATE summary_sheet SET `state`= '1',`loading`='" . $_POST['loading'] . "' WHERE product_product_id='" . $product_name . "' AND daily_lorry_loading_id='" . $lorry_data['id'] . "'");
                            echo 'Moved to Loaded';
                            db::crud("UPDATE inventory_lorry SET `count`=`count`+'" . $_POST['loading'] . "' WHERE daily_lorry_loading_id='" . $lorry_data['id'] . "'");
                            db::crud("UPDATE inventory_main SET stock = stock-'" . $_POST['loading'] . "' WHERE product_product_id='" . $product_name . "' ");
                        }
                    }else{
                        db::crud("UPDATE summary_sheet SET `state`= '1',`loading`='" . $_POST['loading'] . "' WHERE product_product_id='" . $product_name . "' AND daily_lorry_loading_id='" . $lorry_data['id'] . "'");
                        echo 'Moved to Loaded';
                    }
                }
            } else if ($_POST['submit'] == 'uload' && isset($_POST['unloading']) && isset($_POST['unitPrice']) && isset($_POST['sales']) && isset($_POST['value']) && isset($_POST['rtn'])) {
                db::crud("UPDATE summary_sheet SET `state`= '2',uloading='" . $_POST['unloading'] . "', sales ='" . $_POST['sales'] . "',rtn='" . $_POST['rtn'] . "' , unit_price='" . $_POST['unitPrice'] . "' , `value`='" . $_POST['value'] . "' WHERE product_product_id='" . $product_name . "' AND daily_lorry_loading_id='" . $lorry_data['id'] . "'");
                if ($_POST['unloading'] > 0) {
                    if($is_connect->num_rows >0){
                        if($connect_product['product_product_id'] == null){
                        db::crud("UPDATE inventory_main SET stock = stock+'" . $_POST['unloading'] . "' WHERE product_product_id='" . $product_id . "'");
                        }
                     }                   
                }
                echo 'Moved to Unloaded';
            } else {
                echo "Error happen in loading/unloading formdata ";
            }
        } else if (isset($_POST['loading'])) {
            if ($_POST['loading'] == '') {
                echo 'Input Numeric Value';
            }
        } else {
            echo 'Input loading value';
        }
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
