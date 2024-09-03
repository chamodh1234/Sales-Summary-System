<?php
session_start();
require '../config/db.php';
require '../middleware/middleware.php';
$lorryId;
$refName;


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {

   if (empty($validationErrors)) {


      if (isset($_POST['lorryId']) && isset($_POST['refName']) && isset($_POST['route'])) {
         $lorryId = $_POST['lorryId'];
         $refName = $_POST['refName'];
         $Date_id;
         $return = 0;
         $date = db::crud("SELECT `id` FROM `date` WHERE `date` = '" . date('Y-m-d') . "'");


         if ($date->num_rows == 0) {
            db::crud("INSERT INTO `date`(`date`) VALUES('" . date("Y-m-d") . "')");
            $date = db::crud("SELECT id FROM `date` WHERE `date` = '" . date('Y-m-d') . "'");
            $Date_id =  $date->fetch_assoc()['id'];
         } else {
            $Date_id = $date->fetch_assoc()['id'];
         }

         $is_lorry_exsist = db::crud("SELECT * FROM daily_lorry_loading WHERE lorry_lorry_number='" . $lorryId . "' AND ref_ref_id='" . $refName . "' AND date_id='" . $Date_id . "' ");
         if ($is_lorry_exsist->num_rows == 0) {
            $return = db::crud("INSERT INTO daily_lorry_loading(`lorry_lorry_number`,`ref_ref_id`,`date_id`,`route_route`) VALUES ('" . $lorryId . "','" . $refName . "','" . $Date_id . "','" . $_POST['route'] . "')");
            $id = db::crud("SELECT `id` FROM daily_lorry_loading WHERE lorry_lorry_number='" . $lorryId . "' AND ref_ref_id='" . $refName . "' AND date_id='" . $Date_id . "' ");
            $ID = $id->fetch_assoc();
            db::crud("INSERT INTO inventory_lorry(`daily_lorry_loading_id`) VALUES('" . $ID['id'] . "')");
         }



         if ($return == 1) {
            echo  "Insert Successfull";
         } else if ($is_lorry_exsist->num_rows > 0) {
            echo "Lorry Already Added";
         } else {
            echo  "Insert Not Successfull. Error occur !";
         }
      }
      
   } else {
      echo 'Unwanted characters included';
   }
} else {
   echo ' Unauthorized Token';
}
