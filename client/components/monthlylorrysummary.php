<?php
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
    $start_date =  $Date->fetch_assoc();
    $end_date =    $Date->fetch_assoc();

    // $route = db::crud("SELECT route_route FROM daily_lorry_loading WHERE lorry_lorry_number = '" . $lorry_number . "' AND date_id='" . $Date_id['id'] . "'");

}


if (isset($_GET['rid'])) {

    if ($Date->num_rows > 0) {

?>
        <div class="wrapper mt-3 mb-3">
            <div class="d-flex justify-content-around">
                <div class="d-grid">
                    <p class="fw-semibold m-0 ">Month : <?php echo $_GET['y'] . "-" . $_GET['m'] ?></p>
                    <p class="fw-semibold m-0">Lorry No : <?php echo $lorry_number ?> </p>
                </div>
                <div class="d-grid">
                    <!-- <p class="fw-semibold m-0">Route : </p>-->
                    <p class="fw-semibold m-0">Ref/Driver : <?php echo $_GET['rid'] ?></p>
                </div>
            </div>
        </div>


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

                    $query = "SELECT product_product_id,unit_price, SUM(loading) AS loading ,SUM(uloading) AS uloading, SUM(sales) AS sales , SUM(rtn) AS rtn, SUM(`unit_price`* sales) AS `value` FROM summary_sheet INNER JOIN daily_lorry_loading
ON summary_sheet.daily_lorry_loading_id = daily_lorry_loading.id 
WHERE daily_lorry_loading.lorry_lorry_number='" . $_GET['lr'] . "' AND daily_lorry_loading.ref_ref_id='" . $_GET['rid'] . "' 
AND summary_sheet.date_id BETWEEN " . $start_date['id'] . " AND '" . $end_date['id'] . "' GROUP BY summary_sheet.product_product_id,summary_sheet.unit_price";



                    "SELECT product_product_id,unit_price, SUM(loading) AS loading ,SUM(uloading) AS uloading, SUM(sales) AS sales , SUM(rtn) AS rtn, SUM(`unit_price`* sales) AS `value` FROM summary_sheet 
WHERE (date_id BETWEEN '" . $start_date['id'] . "' AND '" . $end_date['id'] . "') AND (lorry_lorry_number='" . $_GET['lr'] . "') GROUP BY product_product_id,unit_price";

                    $summary_row = db::crud($query);
                    $count = 1;
                    $total = 0;
                    $retuns = 0;
                    $sales_after_returns = 0;
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
                            <td><?php if ($row['sales'] == null) {
                                    echo "Incomplete";
                                } else echo $row['sales'] ?></td>
                            <td><?php if ($row['unit_price'] == null) {
                                    echo "Incomplete";
                                } else echo $row['unit_price'] ?></td>
                            <td><?php if ($row['value'] == null) {
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
                            $retuns += $row['rtn'] * $row['unit_price'];
                        }


                        $is_summary_sheet_final = db::crud("SELECT id FROM summary_sheet_final_monthly WHERE `year`='" . $_GET['y'] . "' AND month='" . $_GET['m'] . "' AND lorry_lorry_number='" . $_GET['lr'] . "'");

                        if ($is_summary_sheet_final->num_rows == 0) {
                            db::crud("INSERT INTO summary_sheet_final_monthly(`total`,`returns`,`sales_after_return`,`year`,`month`,`lorry_lorry_number`) VALUES('" . $total . "','" . $retuns . "','" . $sales_after_returns . "','" . $_GET['y'] . "','" . $_GET['m'] . "','" . $_GET['lr'] . "')");
                        } else {
                            db::crud("UPDATE summary_sheet_final_monthly SET total='" . $total . "',returns='" . $retuns . "',sales_after_return='" . $sales_after_returns . "' WHERE `year`='" . $_GET['y'] . "' AND month='" . $_GET['m'] . "' AND lorry_lorry_number='" . $_GET['lr'] . "' ");
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

                <?php

                $final_cash = db::crud("SELECT SUM(fiveth) AS fiveth, SUM(oneth) AS oneth, SUM(fiveh) AS fiveh , SUM(hund) AS hund, SUM(fity) AS fity , SUM(twenty) AS twenty, SUM(coins) AS coins, SUM(total) AS total, SUM(total_cash) AS total_cash, SUM(total_cheques) AS total_cheques, SUM(total_credit_bill) AS total_credit_bill, SUM(discount) AS discount , SUM(expences) AS expences , SUM(grand_total) AS grand_total, SUM(recipt_for_prev_bill) AS recipt_for_prev_bill, SUM(net_sale) AS net_sale, SUM(stock_value_after_returns) AS stock_value_after_returns , SUM(difference) AS difference, SUM(extra_earned) AS extra_earned, SUM(dif_if_any) AS dif_if_any FROM final_cash_statement INNER JOIN daily_lorry_loading 
ON final_cash_statement.daily_lorry_loading_id = daily_lorry_loading.id 
WHERE daily_lorry_loading.lorry_lorry_number = '" . $_GET['lr'] . "' AND daily_lorry_loading.ref_ref_id='" . $_GET['rid'] . "'
AND daily_lorry_loading.date_id BETWEEN '" . $start_date['id'] . "' AND '" . $end_date['id'] . "'");

                $final_cash_data = $final_cash->fetch_assoc();


                ?>

                <tbody>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">5000</p>
                        </th>
                        <td><?php echo $final_cash_data['fiveth'] / 5000 ?></td>
                        <td><?php echo $final_cash_data['fiveth'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">1000</p>
                        </th>
                        <td><?php echo $final_cash_data['oneth'] / 1000 ?></td>
                        <td><?php echo $final_cash_data['oneth'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">500</p>
                        </th>
                        <td><?php echo $final_cash_data['fiveh'] / 500 ?></td>
                        <td><?php echo $final_cash_data['fiveh'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">100</p>
                        </th>
                        <td><?php echo $final_cash_data['hund'] / 100 ?></td>
                        <td><?php echo $final_cash_data['hund'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">50</p>
                        </th>
                        <td><?php echo $final_cash_data['fity'] / 50 ?></td>
                        <td><?php echo $final_cash_data['fity'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">20</p>
                        </th>
                        <td><?php echo $final_cash_data['twenty'] / 20 ?></td>
                        <td><?php echo $final_cash_data['twenty']  ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th scope="row">
                            <p class="m-0 d-flex justify-content-end">coins</p>
                        </th>
                        <td></td>
                        <td><?php echo $final_cash_data['coins'] ?></td>
                    </tr>


                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Total</p>
                        </th>
                        <td><?php echo $final_cash_data['total'] ?></td>
                    </tr>

                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Total Cash</p>
                        </th>
                        <td><?php echo $final_cash_data['total_cash'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Total Cheques</p>
                        </th>
                        <td><?php echo $final_cash_data['total_cheques'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Total Credit Bills</p>
                        </th>
                        <td><?php echo $final_cash_data['total_credit_bill'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Discount</p>
                        </th>
                        <td><?php echo $final_cash_data['discount'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Expenses</p>
                        </th>
                        <td><?php echo $final_cash_data['expences'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Grand Total</p>
                        </th>
                        <td><?php echo $final_cash_data['grand_total'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Receipt For Previos Bill</p>
                        </th>
                        <td><?php echo $final_cash_data['recipt_for_prev_bill'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Net Sale</p>
                        </th>
                        <td><?php echo $final_cash_data['net_sale'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Stock Value After Sale</p>
                        </th>
                        <td><?php echo $final_cash_data['stock_value_after_returns'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Difference</p>
                        </th>
                        <td><?php echo $final_cash_data['difference'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Extra Earned</p>
                        </th>
                        <td><?php echo $final_cash_data['extra_earned'] ?></td>
                    </tr>
                    <tr style="line-height: 10px;">
                        <th colspan="2" scope="row">
                            <p class="d-flex m-0 ">Difference If Any</p>
                        </th>
                        <td><?php echo $final_cash_data['dif_if_any'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>

            <button class="btn btn-primary mb-3" onclick="printMonthlySummary('<?php echo $_GET['m'] ?>','<?php echo $_GET['y'] ?>','<?php echo $_GET['lr'] ?>','<?php echo $_GET['rid'] ?>')">
                print
            </button>
        </div>

    <?php
    } else {
    ?>
        <div class="d-flex justify-content-center align-items-center " style="height: 50vh;">
            <div class="">
                <div class="d-flex justify-content-center">
                    <img src="../public/nodata.png" alt="" width="100" height="90">
                </div>

                <p class="fw-semibold fs-4">No Data Found On <?php echo $_GET['y'] . '-' . $_GET['m'] . ' In Ref id :' . $_GET['rid'] ?></p>
            </div>

        </div>

<?php }
}

?>