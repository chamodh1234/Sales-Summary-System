<?php 

session_start();

if(isset($_GET['comp'])){
    if($_GET['comp']=='1006'){
       
        //header('Token: a');
        
    }
}
 if($_SERVER[''] =='a'){
           header('Location: ../../client/pages/admin.php?comp=1006'); 
        }
?>