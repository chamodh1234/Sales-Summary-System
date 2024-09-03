<?php 
session_start();
require '../config/db.php';


if(isset($_POST['lo'])){
    db::crud("UPDATE user SET loged='0' WHERE user_name='".$_SESSION['un']."'");
    session_destroy();
    $_COOKIE['PHPSESSID'] ='';
}

?>