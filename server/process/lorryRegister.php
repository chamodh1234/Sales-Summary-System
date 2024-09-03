<?php
session_start();
require '../middleware/middleware.php';
require '../config/db.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {


        if (isset($_POST['lorryNumber'])) {
            db::crud("INSERT INTO lorry(`lorry_number`) VALUES('" . $_POST['lorryNumber'] . "')");
            echo "Insert";
        } else {
            echo "error happen in lorry registration";
        }
        // Data is valid, proceed with processing
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
