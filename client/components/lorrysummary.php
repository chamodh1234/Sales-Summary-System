<?php
$lorry_number;
$date;
$lorry_loading_id;
if (isset($_GET['dt']) && isset($_GET['lr'])) {
  $lorry_number = $_GET['lr'];
  $date = $_GET['dt'];
  $Date = db::crud("SELECT id FROM `date` WHERE `date` = '" . $date . "'");
  $Date_id =  $Date->fetch_assoc();
  $lorry_id = db::crud("SELECT * FROM daily_lorry_loading WHERE lorry_lorry_number = '" . $lorry_number . "' AND date_id='" . $Date_id['id'] . "'");
  if ($lorry_id->num_rows > 0) {
    $lorry_loading_id = $lorry_id->fetch_assoc();
  }
}

if($Date->num_rows>0){

?>

<div class="wrapper mt-3 mb-3">
  <div class="d-flex justify-content-around">
    <div class="d-grid">
      <p class="fw-semibold m-0 ">Date : <?php echo $date ?></p>
      <p class="fw-semibold m-0">Lorry No : <?php echo $lorry_number ?> </p>
    </div>
    <div class="d-grid">
      <p class="fw-semibold m-0">Route : <?php echo $lorry_loading_id['route_route']?> </p>
      <p class="fw-semibold m-0">Ref/Driver :<?php echo $lorry_loading_id['ref_ref_id']?></p>
    </div>
  </div>
</div>

<?php 
  $summary_row = db::crud("SELECT * FROM summary_sheet WHERE daily_lorry_loading_id='" . $lorry_loading_id['id'] . "'");
  $count = 1;
  $total = 0;
  $retuns = 0;
  $sales_after_returns = 0;

  if($summary_row->num_rows>0){
?>

<div class="table-responsive" id="summaryView">
  <table class="table table-bordered border-dark">
    <thead>
      <tr>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">No</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">Item Name</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">Loading</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">U/Loading</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">Sale</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">Unit Price</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">Value</p>
        </th>
        <th scope="col">
          <p class="justify-content-center m-0 d-flex">RTN</p>
        </th>

      </tr>
    </thead>
    <tbody>
      <?php
    
      foreach ($summary_row as $row) {

      ?>

        <tr style="line-height: 10px;">
          <th scope="row"><?php echo $count ?></th>
          <td><?php echo $row['product_product_id'] ?></td>
          <td><?php if ($row['loading'] == 0 || $row['loading'] == null) {
                echo "Incomplete";
              } else echo $row['loading'] ?></td>
          <td><?php if ($row['uloading'] == null) {
                echo "Incomplete";
              } else echo $row['uloading'] ?></td>
          <td><?php if ($row['sales'] == 0 || $row['sales'] == null) {
                echo "Incomplete";
              } else echo $row['sales'] ?></td>
          <td><?php if ($row['unit_price'] == 0 || $row['unit_price'] == null) {
                echo "Incomplete";
              } else echo $row['unit_price'] ?></td>
          <td><?php if ($row['value'] == 0 || $row['value'] == null) {
                echo "Incomplete";
              } else echo $row['value'] ?></td>
          <td><?php if ($row['rtn'] == null) {
                echo "Incomplete";
              } else echo $row['rtn'] ?></td>
        </tr>
      <?php
        $count++;
        if ($row['value'] != null && $row['unit_price'] != null) {
          $total += $row['value'];
        }

        if ($row['rtn'] != null && $row['unit_price'] != null) {
          $retuns += $row['rtn'] ;
        }


        $is_summary_sheet_final = db::crud("SELECT id FROM summary_sheet_final WHERE daily_lorry_loading_id='" . $lorry_loading_id['id'] . "'");

        if ($is_summary_sheet_final->num_rows == 0) {
          db::crud("INSERT INTO summary_sheet_final(`total`,`returns`,`sales_after_returns`,`daily_lorry_loading_id`) VALUES('" . $total . "','" . $retuns . "','" . $sales_after_returns . "','" . $lorry_loading_id['id'] . "')");
        } else {
          db::crud("UPDATE summary_sheet_final SET total='" . $total . "',returns='" . $retuns . "',sales_after_returns='" . $sales_after_returns . "' WHERE daily_lorry_loading_id='" . $lorry_loading_id['id'] . "'");
        }
      }

      $sales_after_returns = $total - $retuns;
      ?>

      <tr style="line-height: 10px;">
        <th scope="row"></th>
        <td colspan="5">
          <p class="justify-content-end d-flex m-0">TOTAL</p>
        </td>
        <td><?php echo $total; ?></td>
        <td></td>
      </tr>
      <tr style="line-height: 10px;">
        <th scope="row"></th>
        <td colspan="5">
          <p class="justify-content-end d-flex m-0">RETURN</p>
        </td>
        <td><?php echo $retuns ?></td>
        <td></td>
      </tr>
      <tr style="line-height: 10px;">
        <th scope="row"></th>
        <td colspan="5">
          <p class="justify-content-end d-flex m-0">SALES AFTER RETURN</p>
        </td>
        <td><?php echo $sales_after_returns ?></td>
        <td></td>
      </tr>

    </tbody>
  </table>
</div>

 <?php 
  }else{
    ?>

<div class="d-flex justify-content-center align-items-center " >
            <div class="">
                <div class="d-flex justify-content-center">
                     <img src="../public/nodata.png" alt="" width="100" height="90"> 
                </div>
               
                <p class="fw-semibold fs-4">Not Yet Product Load to lorry</p>
            </div>
            
        </div> 

  <?php
  }
  
  

        $final_cash = db::crud("SELECT * FROM final_cash_statement LEFT JOIN daily_lorry_loading ON final_cash_statement.daily_lorry_loading_id=daily_lorry_loading.id WHERE daily_lorry_loading.lorry_lorry_number = '".$_GET['lr']."' AND daily_lorry_loading.date_id ='".$Date_id['id']."'");

        $final_cash_data = $final_cash->fetch_assoc();
        
        if($final_cash->num_rows>0){    
        ?>


<div class="col-5">
    <table class="table table-bordered border-dark">
        <thead>
            <tr>
                <th scope="col">
                    <p class="d-flex justify-content-center m-0">Denomination</p>
                </th>
                <th scope="col">
                    <p class="d-flex justify-content-center m-0">Qty</p>
                </th>
                <th scope="col">
                    <p class="d-flex justify-content-center m-0">Value</p>
                </th>

            </tr>
        </thead>

       
        <tbody>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">5000</p>
                </th>
                <td><?php echo $final_cash_data['fiveth']/5000?></td>
                <td><?php echo $final_cash_data['fiveth']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">1000</p>
                </th>
                <td><?php echo $final_cash_data['oneth']/1000?></td>
                <td><?php echo $final_cash_data['oneth']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">500</p>
                </th>
                <td><?php echo $final_cash_data['fiveh']/500?></td>
                <td><?php echo $final_cash_data['fiveh']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">100</p>
                </th>
                <td><?php echo $final_cash_data['hund']/100?></td>
                <td><?php echo $final_cash_data['hund']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">50</p>
                </th>
                <td><?php echo $final_cash_data['fity']/50?></td>
                <td><?php echo $final_cash_data['fity']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">20</p>
                </th>
                <td><?php echo $final_cash_data['twenty']/20?></td>
                <td><?php echo $final_cash_data['twenty']/20?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th scope="row">
                    <p class="m-0 d-flex justify-content-end">coins</p>
                </th>
                <td></td>
                <td><?php echo $final_cash_data['coins']?></td>
            </tr>


            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Total</p>
                </th>
                <td><?php echo $final_cash_data['total']?></td>
            </tr>

            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Total Cash</p>
                </th>
                <td><?php echo $final_cash_data['total_cash']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Total Cheques</p>
                </th>
                <td><?php echo $final_cash_data['total_cheques']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Total Credit Bills</p>
                </th>
                <td><?php echo $final_cash_data['total_credit_bill']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Discount</p>
                </th>
                <td><?php echo $final_cash_data['discount']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Expenses</p>
                </th>
                <td><?php echo $final_cash_data['expences']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Grand Total</p>
                </th>
                <td><?php echo $final_cash_data['grand_total']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Receipt For Previos Bill</p>
                </th>
                <td><?php echo $final_cash_data['recipt_for_prev_bill']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Net  Sale</p>
                </th>
                <td><?php echo $final_cash_data['net_sale']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Stock Value After Sale</p>
                </th>
                <td><?php echo $final_cash_data['stock_value_after_returns']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Difference</p>
                </th>
                <td><?php echo $final_cash_data['difference']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Extra Earned</p>
                </th>
                <td><?php echo $final_cash_data['extra_earned']?></td>
            </tr>
            <tr style="line-height: 10px;">
                <th colspan="2" scope="row">
                    <p class="d-flex m-0 ">Difference If Any</p>
                </th>
                <td><?php echo $final_cash_data['dif_if_any']?></td>
            </tr>
        </tbody>
    </table>
</div>


<div>
  <button onclick="printDailyLorrySummary('<?php echo $_GET['dt'] ?>','<?php echo $_GET['lr'] ?>')" class="btn btn-primary mb-3" >print</button>
</div>

<?php
        } else{
          ?>

<div class="d-flex justify-content-center align-items-center " style="height: 50vh;">
            <div class="">
                <div class="d-flex justify-content-center">
                     <img src="../public/nodata.png" alt="" width="100" height="90"> 
                </div>
               
                <p class="fw-semibold fs-4">Not Yet Complete final statement</p>
            </div>
            
        </div>


      <?php  }
    }else{
        ?>
        <div class="d-flex justify-content-center align-items-center " style="height: 50vh;">
            <div class="">
                <div class="d-flex justify-content-center">
                     <img src="../public/nodata.png" alt="" width="100" height="90"> 
                </div>
               
                <p class="fw-semibold fs-4">No Data Found On <?php echo $_GET['y'].'-'.$_GET['m'].' In Ref id :'.$_GET['rid'] ?></p>
            </div>
            
        </div>
       
   <?php }



?>