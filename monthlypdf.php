<?php 
session_start();
require 'server/config/db.php';
require 'fpdf186/fpdf.php';
$lorry_number;
$date;
$lorry_loading_id;
$start_date;
$end_date;

if (isset($_GET['m']) && isset($_GET['lr']) && isset($_GET['y']) && isset($_GET['rid'])) {
    $lorry_number = $_GET['lr'];
    // $date = $_GET['dt'];
    $Date = db::crud("(SELECT * FROM `date` WHERE `date` LIKE '" . $_GET['y'] . "-" . $_GET['m'] . "-%'
ORDER BY id ASC LIMIT 1) UNION ALL 
(SELECT * FROM `date` WHERE `date` LIKE '" . $_GET['y'] . "-" . $_GET['m'] . "-%' ORDER BY id DESC LIMIT 1)");
    $start_date=  $Date->fetch_assoc();
    $end_date =    $Date->fetch_assoc();

   // $route = db::crud("SELECT route_route FROM daily_lorry_loading WHERE lorry_lorry_number = '" . $lorry_number . "' AND date_id='" . $Date_id['id'] . "'");

}
  $summary_row = db::crud("SELECT product_product_id,unit_price, SUM(loading) AS loading ,SUM(uloading) AS uloading, SUM(sales) AS sales , SUM(rtn) AS rtn, SUM(`unit_price`* sales) AS `value` FROM summary_sheet INNER JOIN daily_lorry_loading
ON summary_sheet.daily_lorry_loading_id = daily_lorry_loading.id 
WHERE daily_lorry_loading.lorry_lorry_number='".$_GET['lr']."' AND daily_lorry_loading.ref_ref_id='".$_GET['rid']."' 
AND summary_sheet.date_id BETWEEN ".$start_date['id']." AND '".$end_date['id']."' GROUP BY summary_sheet.product_product_id,summary_sheet.unit_price");

  $final_cash = db::crud("SELECT SUM(fiveth) AS fiveth, SUM(oneth) AS oneth, SUM(fiveh) AS fiveh , SUM(hund) AS hund, SUM(fity) AS fity , SUM(twenty) AS twenty, SUM(coins) AS coins, SUM(total) AS total, SUM(total_cash) AS total_cash, SUM(total_cheques) AS total_cheques, SUM(total_credit_bill) AS total_credit_bill, SUM(discount) AS discount , SUM(expences) AS expences , SUM(grand_total) AS grand_total, SUM(recipt_for_prev_bill) AS recipt_for_prev_bill, SUM(net_sale) AS net_sale, SUM(stock_value_after_returns) AS stock_value_after_returns , SUM(difference) AS difference, SUM(extra_earned) AS extra_earned, SUM(dif_if_any) AS dif_if_any FROM final_cash_statement INNER JOIN daily_lorry_loading 
ON final_cash_statement.daily_lorry_loading_id = daily_lorry_loading.id 
WHERE daily_lorry_loading.lorry_lorry_number = '".$_GET['lr']."' AND daily_lorry_loading.ref_ref_id='".$_GET['rid']."'
AND daily_lorry_loading.date_id BETWEEN '".$start_date['id']."' AND '".$end_date['id']."'");

 $final_cash_data = $final_cash->fetch_assoc();
        

$pdf =new FPDF();

$pdf->SetTopMargin(2);
$pdf->SetAutoPageBreak(true,10);
$pdf->SetLeftMargin(10);
$pdf->AddPage('P',"A4");

$pdf->SetFont("Arial","UB",12);

$pdf->Cell('190','10','AKALANKA MILK DISTRIBUTOR - MONTHLY SALES SUMMARY',0,1,'C');

$pdf->SetFont("Arial",'');
$pdf->Cell(100,5,'Date : '.$_GET['y'].'-'.$_GET['m'],0,0);
$pdf->Cell(50,5,'Ref/Driver : '.$_GET['rid'],0,1);
$pdf->Cell(100,5,'Lorry No : '.$_GET['lr'],0,0);


$pdf->SetFont("Arial",'B',10);
$pdf->Cell(20,7,'',0,1);
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
$pdf->Cell(40,5,$final_cash_data['fiveth'],1,1,'C');

$pdf->Cell(50,5,'1000',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['oneth']/1000,1,0,'C');
$pdf->Cell(40,5,$final_cash_data['oneth'],1,1,'C');

$pdf->Cell(50,5,'500',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['fiveh']/500,1,0,'C');
$pdf->Cell(40,5,$final_cash_data['fiveh'],1,1,'C');

$pdf->Cell(50,5,'100',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['hund']/100,1,0,'C');
$pdf->Cell(40,5,$final_cash_data['hund'],1,1,'C');

$pdf->Cell(50,5,'50',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['fity']/50,1,0,'C');
$pdf->Cell(40,5,$final_cash_data['fity'],1,1,'C');

$pdf->Cell(50,5,'20',1,0,'R');
$pdf->Cell(20,5,$final_cash_data['twenty']/20,1,0,'C');
$pdf->Cell(40,5,$final_cash_data['twenty'],1,1,'C');

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

?>