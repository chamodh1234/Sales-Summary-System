<?php 
session_start();
require '../config/db.php';

if(isset($_POST['userName']) && isset($_POST['password'])){
    $user = db::crud("SELECT user_name,`role`,`loged` FROM user WHERE user_name='".$_POST['userName']."' AND password='".$_POST['password']."'  ");
    $user_Data = $user->fetch_assoc();

 
    if($user->num_rows>0 && $user_Data['loged']!=1){
           db::crud("UPDATE user SET session_id='".$_COOKIE['PHPSESSID']."',loged='1' WHERE user_name='".$_POST['userName']."'");
        $_SESSION['un'] = $_POST['userName'];
        $_SESSION['r'] = $user_Data['role'];
        //header("Location: admin.php?comp=1001");
        echo'user found';
    }else{
        echo 'already loged';
    }
}


?>