<?php
session_start();
require '../middleware/middleware.php';
require '../config/db.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {

    if (empty($validationErrors)) {

        if (isset($_POST['route'])) {
            db::crud("INSERT INTO `route`(`route`) VALUES('" . $_POST['route'] . "')");
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
