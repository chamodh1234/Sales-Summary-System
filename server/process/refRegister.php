<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {
        if (isset($_POST['ref'])) {
            db::crud("INSERT INTO `ref`(`ref_id`) VALUES('" . $_POST['ref'] . "')");
            echo "Insert";
        } else {
            echo "error happen in route registration";
        }
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
