<?php 
session_start();
require 'server/config/db.php';
require 'fpdf186/fpdf.php';

require 'server/middleware/middleware.php';

if(Middleware::checkTokenValidity()){

if (isset($_GET['dt']) && isset($_GET['lr'])) {
    $lorry_number = $_GET['lr'];
    $date = $_GET['dt'];
    $Date = db::crud("SELECT id FROM `date` WHERE `date` = '" . $date . "'");
    $Date_id =  $Date->fetch_assoc();
    $lorry_id = db::crud("SELECT * FROM daily_lorry_loading WHERE lorry_lorry_number = '" . $lorry_number . "' AND date_id='" . $Date_id['id'] . "'");
    if ($lorry_id->num_rows > 0) {
      $lorry_loading_id = $lorry_id->fetch_assoc();
    }
  }else{
    header("Location: client/pages/admin.php");
  }

  $summary_row = db::crud("SELECT * FROM summary_sheet WHERE daily_lorry_loading_id='" . $lorry_loading_id['id'] . "'");

  $final_cash = db::crud("SELECT * FROM final_cash_statement LEFT JOIN daily_lorry_loading ON final_cash_statement.daily_lorry_loading_id=daily_lorry_loading.id WHERE daily_lorry_loading.lorry_lorry_number = '".$_GET['lr']."' AND daily_lorry_loading.date_id ='".$Date_id['id']."'");

 $final_cash_data = $final_cash->fetch_assoc();
        

$pdf =new FPDF();

$pdf->SetTopMargin(2);
$pdf->SetAutoPageBreak(true,10);
$pdf->SetLeftMargin(10);
$pdf->AddPage('P',"A4");

$pdf->SetFont("Arial","UB",12);

$pdf->Cell('190','10','AKALANKA MILK DISTRIBUTOR - DAILY SALES SUMMARY',0,1,'C');

$pdf->SetFont("Arial",'');
$pdf->Cell(100,5,'Date : '.$_GET['dt'],0,0);
$pdf->Cell(50,5,'Route : '.$lorry_loading_id['route_route'],0,1);
$pdf->Cell(100,5,'Lorry No : '.$_GET['lr'],0,0);
$pdf->Cell(50,5,'Ref/driver : '.$lorry_loading_id['ref_ref_id'],0,1);

$pdf->SetFont("Arial",'B',10);
$pdf->Cell(20,5,'',0,1);
$pdf->Cell(10,5,'No',1,0);
$pdf->Cell(40,5,'ITEM NAME',1,0);
$pdf->Cell(25,5,'LOADING',1,0);
$pdf->Cell(25,5,'U/LOADING',1,0);
$pdf->Cell(20,5,'SALE',1,0);
$pdf->Cell(27,5,'UNIT PRICE',1,0);
$pdf->Cell(20,5,'VALUE',1,0);
$pdf->Cell(20,5,'RTN',1,1);

$pdf->SetFont("Arial",'',10);
$x=1;

$total =0;
$return =0;
$sales_after_returns =0;

foreach($summary_row as $row){


$pdf->Cell(10,5,$x,1,0);
$pdf->Cell(40,5,$row['product_product_id'],1,0);
$pdf->Cell(25,5,$row['loading'],1,0,"C");
$pdf->Cell(25,5,$row['uloading'],1,0,"C");
$pdf->Cell(20,5,$row['sales'],1,0,"C");
$pdf->Cell(27,5,$row['unit_price'],1,0,'R');
$pdf->Cell(20,5,$row['unit_price']*$row['sales'],1,0,'C');
$pdf->Cell(20,5,$row['rtn'],1,1,'C');
$x++;
$total += $row['unit_price']*$row['sales'];
$return += $row['unit_price']*$row['rtn'];
$sales_after_returns = $total - $return;
}

$pdf->Cell(10,5,'',1,0);
$pdf->Cell(137,5,'TOTAL',1,0,'R');
$pdf->Cell(20,5,$total,1,0,'C');
$pdf->Cell(20,5,'',1,1);

$pdf->Cell(10,5,'',1,0);
$pdf->Cell(137,5,'RETURN',1,0,'R');
$pdf->Cell(20,5,$return,1,0,'C');
$pdf->Cell(20,5,'',1,1);

$pdf->Cell(10,5,'',1,0);
$pdf->Cell(137,5,'SALES AFTER RETURN',1,0,'R');
$pdf->Cell(20,5,$sales_after_returns,1,0,'C');
$pdf->Cell(20,5,'',1,1);

$pdf->Cell(10,10,'',0,1);

$pdf->Cell(50,5,'Denomination',1,0,'C');
$pdf->Cell(20,5,'QTY',1,0,'C');
$pdf->Cell(40,5,'Value',1,1,'C');

$pdf->Cell(50,5,'5000',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['fiveth']/5000,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'1000',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['oneth']/1000,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'500',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['fiveh']/500,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'100',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['hund']/100,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'50',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['fity']/50,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'20',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['twenty']/20,1,0,'C');
$pdf->Cell(40,5,'',1,1);

$pdf->Cell(50,5,'COINS',1,0,'R');
$pdf->Cell(20,5,'',1,0);
$pdf->Cell(40,5,$final_cash_data['coins'],1,1,'C');

$pdf->Cell(70,5,'TOTAL',1,0);
$pdf->Cell(40,5,$final_cash_data['total'],1,1,'C');

$pdf->Cell(70,5,'TOTAL CASH',1,0);
$pdf->Cell(40,5,$final_cash_data['total_cash'],1,1,'C');

$pdf->Cell(70,5,'TOTAL CHEQUES',1,0);
$pdf->Cell(40,5,$final_cash_data['total_cheques'],1,1,'C');

$pdf->Cell(70,5,'TOTAL CREDIT BILL',1,0);
$pdf->Cell(40,5,$final_cash_data['total_credit_bill'],1,1,'C');

$pdf->Cell(70,5,'DISCOUNT',1,0);
$pdf->Cell(40,5,$final_cash_data['discount'],1,1,'C');

$pdf->Cell(70,5,'EXPENSES',1,0);
$pdf->Cell(40,5,$final_cash_data['expences'],1,1,'C');

$pdf->Cell(70,5,'GRAND TOTAL',1,0);
$pdf->Cell(40,5,$final_cash_data['grand_total'],1,1,'C');

$pdf->Cell(70,5,'RECEIPT FOR PREVIOUS BILL',1,0);
$pdf->Cell(40,5,$final_cash_data['recipt_for_prev_bill'],1,1,'C');

$pdf->Cell(70,5,'NET SALE',1,0);
$pdf->Cell(40,5,$final_cash_data['net_sale'],1,1,'C');

$pdf->Cell(70,5,'STOCK VALUE AFTER RETURNS',1,0);
$pdf->Cell(40,5,$final_cash_data['stock_value_after_returns'],1,1,'C');

$pdf->Cell(70,5,'DIFFERENCE',1,0);
$pdf->Cell(40,5,$final_cash_data['difference'],1,1,'C');

$pdf->Cell(70,5,'EXTRA EARNED',1,0);
$pdf->Cell(40,5,$final_cash_data['extra_earned'],1,1,'C');

$pdf->Cell(70,5,'DIFFERENCE IF ANY',1,0);
$pdf->Cell(40,5,$final_cash_data['dif_if_any'],1,1,'C');



$pdf->Output();

}
?>