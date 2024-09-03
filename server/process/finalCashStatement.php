<?php
session_start();
require '../middleware/middleware.php';
require '../config/db.php';


$postData = $_POST;
$validationErrors = Middleware::validatePostData($postData);

if (Middleware::checkTokenValidity()) {
    if (empty($validationErrors)) {

        if (
            isset($_POST['fiveth']) &&
            isset($_POST['th']) &&
            isset($_POST['fiveh']) &&
            isset($_POST['hun']) &&
            isset($_POST['fif']) &&
            isset($_POST['twn']) &&
            isset($_POST['coins']) &&
            isset($_POST['total']) &&
            isset($_POST['totalch']) &&
            isset($_POST['totalche']) &&
            isset($_POST['totalcrebill']) &&
            isset($_POST['discount']) &&
            isset($_POST['expe']) &&
            isset($_POST['grand']) &&
            isset($_POST['reciptfprevbil']) &&
            isset($_POST['netsale']) &&
            isset($_POST['stockvlafrtn']) &&
            isset($_POST['diff']) &&
            isset($_POST['exearn']) &&
            isset($_POST['diffifany']) &&
            isset($_POST['lorryNumber'])
        ) {


            $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . date('Y-m-d') . "' ");
            $Date_id = $date_id->fetch_assoc();
            $lorry_loading_id = db::crud("SELECT `id` FROM daily_lorry_loading WHERE lorry_lorry_number='" . $_POST['lorryNumber'] . "' AND date_id = '" . $Date_id['id'] . "'");
            $lorry_id = $lorry_loading_id->fetch_assoc();
            
            $is_final = db::crud("SELECT id FROM final_cash_statement WHERE daily_lorry_loading_id='" . $lorry_id['id'] . "'");


            if ($is_final->num_rows == 0) {
                db::crud("INSERT INTO final_cash_statement(`fiveth`,`oneth`,`fiveh`,`hund`,`fity`,`twenty`,`coins`,`total`,`total_cash`,`total_cheques`,`total_credit_bill`,`discount`,`expences`,`grand_total`,`recipt_for_prev_bill`,`net_sale`,`stock_value_after_returns`,`difference`,`extra_earned`,`dif_if_any`,`daily_lorry_loading_id`) VALUES ('" . $_POST['fiveth'] . "',
'" . $_POST['th'] . "',
'" . $_POST['fiveh'] . "',
'" . $_POST['hun'] . "',
'" . $_POST['fif'] . "',
'" . $_POST['twn'] . "',
'" . $_POST['coins'] . "',
'" . $_POST['total'] . "',
'" . $_POST['totalch'] . "',
'" . $_POST['totalche'] . "',
'" . $_POST['totalcrebill'] . "',
'" . $_POST['discount'] . "',
'" . $_POST['expe'] . "',
'" . $_POST['grand'] . "',
'" . $_POST['reciptfprevbil'] . "',
'" . $_POST['netsale'] . "',
'" . $_POST['stockvlafrtn'] . "',
'" . $_POST['diff'] . "',
'" . $_POST['exearn'] . "',
'" . $_POST['diffifany'] . "',
'" . $lorry_id['id'] . "'
)");

echo 'added';
echo $_POST['stockvlafrtn'];
            } else {
                echo "Already added !";
            }
        }  // Data is valid, proceed with processing
    } else {
        echo 'Unwanted characters included';
    }
} else {
    echo ' Unauthorized Token';
}
