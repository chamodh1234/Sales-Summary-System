<div class="">
    <div class="d-flex mt-3 wrapper">

        <img src="../public/lorry_face.png" width="40" height="40" alt="">
        <p class="fw-semibold fs-2 wrapper "><?php echo $_GET['lr_id'] ?></p>
    </div>

    <div class="wrapper">
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop"> Add Products</button>

    </div>
    <div class="row row-cols-4 gap-2 mt-5 mb-3 wrapper">
        <?php
        $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . date('Y-m-d') . "' ");
        $Date_id = $date_id->fetch_assoc();
        $daily_lorry_id = db::crud("SELECT id FROM daily_lorry_loading WHERE date_id='".$Date_id['id']."' AND lorry_lorry_number='".$_GET['lr_id']."'");
        $lorry_id = $daily_lorry_id->fetch_assoc();
        $loaded = db::crud("SELECT summary_sheet.*,product.unit_price FROM summary_sheet LEFT JOIN product ON summary_sheet.product_product_id = product.product_id WHERE date_id='" . $Date_id['id'] . "' AND daily_lorry_loading_id='".$lorry_id['id']."'  ");
    
        foreach ($loaded as $load) {

            if ($load['state'] == 0) {
        ?>


                <div class="card border-top-primary border-top-3  ms-2" style="max-width: 15rem; " type="button" data-coreui-toggle="modal" data-coreui-target="#pid<?php echo $load['product_product_id'] ?>">
                    <div class="card-body fw-semibold ">
                        <p class="card-text m-0"><?php echo $load['product_product_id'] ?></p>
                    </div>
                </div>


        <?php
            }
        }

        ?>
    </div>
    <div class="wrapper fs-3 fw-semibold">
        Loaded
    </div>
    <div class="row row-cols-4 gap-2 wrapper">
        <?php
        foreach ($loaded as $load) {
            if ($load['state'] == 1) {

        ?>


                <div class="card border-top-warning border-top-3 mb-3 mt-1 ms-2" style="max-width: 15rem; " type="button" data-coreui-toggle="modal" data-coreui-target="#pid<?php echo $load['product_product_id'] ?>">
                    <div class="card-body fw-semibold ">
                        <p class="card-text m-0"><?php echo $load['product_product_id'] ?></p>
                    </div>
                </div>


        <?php
            }
        } ?>
    </div>

    <div class="wrapper fs-3 fw-semibold">
        Unloaded
    </div>

 <div class="row row-cols-4 gap-2 wrapper">
    <?php
    foreach ($loaded as $load) {
        if ($load['state'] == 2) {

    ?>



           
                <div class="card border-top-success border-top-3 mb-3 mt-1 ms-2" style="max-width: 15rem; " type="button" data-coreui-toggle="modal" data-coreui-target="#pid<?php echo $load['product_product_id'] ?>">
                    <div class="card-body fw-semibold ">
                        <p class="card-text m-0"><?php echo $load['product_product_id'] ?></p>
                    </div>
                </div>
           




<?php
        }
    }

?>
 </div>
</div>






<div class="modal fade" id="staticBackdrop" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product To Lorry</h5>

            </div>
            <div class="modal-body fw-semibold">
                <div class="d-grid gap-2">
                    <div>
                        <label for="" class="d-flex gap-2">Select Product : <p class="text-danger">*</p></label>

                        <select name="" class='form-control' id="productLoadingAddProductSelect">
                            <option value="" selected disabled>Select Product</option>
                            <?php

                            $products = db::crud("SELECT product_id FROM product");

                            foreach ($products as $product) {
                            ?>
                                <option value="<?php echo $product['product_id'] ?>"><?php echo $product['product_id'] ?></option>
                            <?php
                            }
                            ?>


                        </select>
                    </div>

                </div>

                <p class="mt-2 text-danger" id="productLoadingAddProductAlert"></p>


            </div>
            <?php
            $id = 0;

            if (isset($_GET['lr_id'])) {
                $id = $_GET['lr_id'];
            }


            ?>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="productLoadingAddProduct('<?php echo $id ?>')" id="productLoadingAddProductBtn">Add</button>
            </div>
        </div>
    </div>
</div>


<?php



foreach ($loaded as $load) {
?>

    <div class="modal fade" id="pid<?php echo $load['product_product_id'] ?>" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Product To Lorry</h5>

                </div>
                <div class="modal-body fw-semibold">
                    <div class="d-grid gap-2">
                        <div class="d-flex gap-4">
                            <div class="d-grid gap-2 col-3">
                                <label for="" class="align-content-center " style="height: 40px;">Loading :</label>
                                <label for="" class="align-content-center" style="height: 40px;">Unit Price :</label>

                                <?php if ($load['state'] == 1 || $load['state'] == 2) {
                                ?> <label for="" class="align-content-center" style="height: 40px;">Unloading :</label>

                                    <label for="" class="align-content-center" style="height: 40px;">Sales :</label>
                                    <label for="" class="align-content-center" style="height: 40px;">Value :</label>

                                    <label for="" class="align-content-center" style="height: 40px;">RTN:</label>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="col-8 d-grid gap-2">
                                <div class="col-4">
                                    <input type="number" id="loadingInput<?php echo $load['product_product_id'] ?>" name="" class="form-control" <?php
                                                                                                                                                    if ($load['state'] == 1 || $load['state'] == 2) {
                                                                                                                                                        echo "disabled ";
                                                                                                                                                        echo  "value='" . $load['loading'] . "'";
                                                                                                                                                    }
                                                                                                                                                    ?> min='1'>
                                </div>
                                <div class="col-4">
                                    <input type="text" disabled value="123" name="" class="form-control" id="unitPriceInput<?php echo $load['product_product_id'] ?>" min='1'>
                                </div>


                                <?php if ($load['state'] == 1 || $load['state'] == 2) {

                                ?> <div class="col-4">
                                        <input type="number" <?php if ($load['state'] == 2) {
                                                                    echo "disabled ";
                                                                    echo  "value='" . $load['uloading'] . "'";
                                                                } ?> name="" class="form-control" id="unloadingInput<?php echo $load['product_product_id'] ?>" min="0" value="" oninput="salesAndValueCalculation('<?php echo $load['product_product_id'] ?>')" min='1'>
                                    </div>

                                    <div class="col-4">
                                        <input type="text" name="" <?php if ($load['state'] == 2) {
                                                                        echo "disabled ";
                                                                        echo  "value='" . $load['sales'] . "'";
                                                                    } ?> disabled class="form-control" id="salesInput<?php echo $load['product_product_id'] ?>" min='1'>
                                    </div>
                                    <div class="col-4">
                                        <input type="text" <?php if ($load['state'] == 2) {
                                                                echo "disabled ";
                                                                echo  "value='" . $load['value'] . "'";
                                                            } ?> disabled name="" class="form-control" id="valueInput<?php echo $load['product_product_id'] ?>" min='1'>
                                    </div>

                                    <div class="col-4">
                                        <input type="number" name="" <?php if ($load['state'] == 2) {
                                                                            echo "disabled ";
                                                                            echo  "value='" . $load['rtn'] . "'";
                                                                        } ?> class="form-control" id="rtnInput<?php echo $load['product_product_id'] ?>" value="0" min='1'>
                                    </div>
                                <?php } ?>



                            </div>
                        </div>

                        <p class="text-danger" id="productLoadingInputAlert<?php echo $load['product_product_id'] ?>"></p>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <?php
                    if ($load['state'] == 1) {
                    ?>
                        <button type="button" class="btn btn-primary" id="productUnloadBtn" onclick="productLoadingMoveToLoaded('<?php echo $load['product_product_id'] ?>','<?php echo $_GET['lr_id'] ?>','uload')">Checked</button>
                    <?php
                    } else if ($load['state'] == 0) {
                    ?>
                        <button type="button" class="btn btn-primary" id="productLoadedBtn" onclick="productLoadingMoveToLoaded('<?php echo $load['product_product_id'] ?>','<?php echo $_GET['lr_id'] ?>','load')">Loaded</button>
                    <?php  }
                    ?>

                </div>
            </div>
        </div>
    </div>

<?php }

?>


<div class="wrapper">
    <button type="button" class="btn btn-primary " data-coreui-toggle="modal" data-coreui-target="#finalcash">
        Add Final
    </button>

</div>

<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="finalcash" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content bg-dark text-white fw-semibold">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Final Cash Statement</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <p>Denomination</p>
                <div class="d-flex gap-2 mt-1">
                    <div class="d-grid col-6">
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">5000</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">1000</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">500</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">100</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">50</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">20</label>
                        <label for="" class="align-content-center d-grid justify-content-end pe-4">coins</label>
                        <label for="" class="align-content-center">TOTAL</label>
                        <label for="" class="align-content-center">TOTAL CASH</label>
                        <label for="" class="align-content-center">TOTAL CHEQUES</label>
                        <label for="" class="align-content-center">TOTAL CREDIT BILLS</label>
                        <label for="" class="align-content-center">DISCOUNT</label>
                        <label for="" class="align-content-center">EXPENSES</label>
                        <label for="" class="align-content-center">GRAND TOTAL</label>
                        <label for="" class="align-content-center">RECIPT FOR PREV BILL</label>
                        <label for="" class="align-content-center">NET SALE</label>
                        <label for="" class="align-content-center">STOCK VAL AFTER RTN</label>
                        <label for="" class="align-content-center">DIFFERENCE</label>
                        <label for="" class="align-content-center">EXTRA EARNED</label>
                        <label for="" class="align-content-center">DIFFERENCE IF ANY</label>
                    </div>
                    <div class="d-grid gap-1 col-4">
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash(),calculateGrandTotal()" min="0" name="" id="fiveth">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() ,calculateGrandTotal()" min="0" name="" id="th">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() ,calculateGrandTotal()" min="0" name="" id="fiveh">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() ,calculateGrandTotal()" min="0" name="" id="hun">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() ,calculateGrandTotal()" min="0" name="" id="fif">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() , calculateGrandTotal()" min="0" name="" id="twn">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calcutaleTotalAndCash() ,calculateGrandTotal()" min="0" name="" id="coins">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0"  disabled  name="" id="total">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" disabled name="" id="totalch">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" onchange="calculateGrandTotal()" min="0" name="" id="totalche">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" min="0"onchange="calculateGrandTotal()" name="" id="totalcrebill">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" min="0" onchange="calculateGrandTotal()" name="" id="discount">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" min="0" onchange="calculateGrandTotal()" name="" id="expe">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" disabled min="0" name="" id="grand">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0"  min="0" onchange="calcutaleNetSale()" name="" id="reciptfprevbil">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" value="0" min="0" disabled name="" id="netsale">
                        </div>
                <?php 
                    $Date = db::crud("SELECT id FROM `date` WHERE `date` = '" . date('Y-m-d'). "'");
                    $Date_id =  $Date->fetch_assoc();
                    $lorry_id = db::crud("SELECT * FROM daily_lorry_loading WHERE lorry_lorry_number = '" . $_GET['lr_id'] . "' AND date_id='" . $Date_id['id'] . "'");
                    $lorry_loading_id;

                    if ($lorry_id->num_rows > 0) {
                        $lorry_loading_id = $lorry_id->fetch_assoc();
                      }

                    $summary_row = db::crud("SELECT * FROM summary_sheet WHERE daily_lorry_loading_id='" . $lorry_loading_id['id'] . "'");
                    $total = 0;
                    $retuns = 0;
                    $sales_after_returns = 0;

                    foreach($summary_row as $row){
                        if ($row['value'] != null && $row['unit_price'] != null) {
                            $total += $row['value'] ;
                          }
                  
                          if ($row['rtn'] != null && $row['unit_price'] != null) {
                            $retuns += $row['rtn'] ;
                          }
                  
                    }

                    $sales_after_returns = $total - $retuns

                    ?>


                        <div class="col-12">
                            <input type="number" class="form-control" disabled value="<?php echo $sales_after_returns ?>" min="0" name="" id="stockvlafrtn">
                        </div>
                        <div class="col-12">
                            <input type="number" disabled class="form-control" value="0" min="0" name="" id="diff">
                        </div>
                        <div class="col-12">
                            <input type="number" class="form-control" onchange="calculateDifferenceIfAny()" value="0" min="0" name="" id="exearn">
                        </div>
                        <div class="col-12">
                            <input type="number" disabled class="form-control" value="0" min="0" name="" id="diffifany">
                        </div>
                    </div>
                </div>
                <div class=" d-flex ">


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="finalCashStatement('<?php echo $_GET['lr_id'] ?>')">Add</button>
            </div>
        </div>
    </div>
</div>