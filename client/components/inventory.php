<div>

    <div class="wrapper mt-2">

        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#addproduct"> Add Products</button>
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#purchase"> Purchase</button>
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#connect"> Connect</button>
    </div>

</div>



<div class="row row-cols-5 gap-2 justify-content-start wrapper mt-3">

    <?php

    $products = db::crud("SELECT `product_product_id`,`stock` FROM inventory_main ");

    foreach ($products as $product) {
    ?>

        <div class="card <?php if ($product['stock'] > 100) {
                                echo " text-black border-top-primary";
                            } else {
                                echo " border-top-danger bg-danger text-white";
                            } ?> border-top-3  ms-2" style="max-width: 15rem; " type="button">
            <div class="card-body fw-semibold ">
                <p class="card-text m-0 fs-5 fw-bold"><?php echo $product['product_product_id'] ?></p>
                <p class="card-text m-0  fw-light"> Stock : <?php echo $product['stock'] ?></p>
            </div>
        </div>

    <?php }

    ?>


</div>



<!-- Modal -->
<div class="modal fade" id="addproduct" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content text-white bg-dark ">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product To inventory</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="d-grid gap-2">
                    <label for="" class="fw-semibold">Select Product</label>
                    <select name="" class="form-control" id="inventoryProductNameInput">
                        <option value="" disabled selected>Select Product</option>
                        <?php
                        $products = db::crud("SELECT product_id FROM product");
                        foreach ($products as $product) {
                        ?>
                            <option value="<?php echo $product['product_id'] ?>"><?php echo $product['product_id'] ?></option>
                        <?php }
                        ?>

                    </select>
                </div>
                <div class="d-grid gap-2 mt-1">
                    <label class="fw-semibold">Product Count :</label>
                    <input type="number" class="form-control" name="" id="inventoryProductCountInput">
                </div>
                <p id="inventoryAddProductAlert" class="fw-semibold text-danger mt-2 mb-0"></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addProductToInventory()" id="addProductToInventoryBtn">Update</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="purchase" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-white bg-dark ">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Purchase</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <label for="" class="fw-semibold">Select Product</label>
                    <select name="" class="form-control" id="inventoryPruchaseProductNameInput">
                        <option value="" disabled selected>Select Product</option>
                        <?php
                        $products = db::crud("SELECT product_id FROM product");
                        foreach ($products as $product) {
                        ?>
                            <option value="<?php echo $product['product_id'] ?>"><?php echo $product['product_id'] ?></option>
                        <?php }
                        ?>

                    </select>
                </div>
                <div class="d-grid gap-2 mt-1">
                    <label class="fw-semibold">Purchase Count :</label>
                    <input type="number" class="form-control" name="" id="inventoryProductPurchaseCountInput">
                </div>
                <p id="inventoryAddPurchaseAlert" class="fw-semibold text-danger mt-2 mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addProductPurchaseToInventory()">Add</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="connect" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-white bg-dark ">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Connect Product</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <label for="" class="fw-semibold">Connect</label>
                    <select name="" class="form-control" id="inventoryConnectProductNameInput">
                        <option value="" disabled selected>Select Product</option>
                        <?php
                        $products = db::crud("SELECT product_id FROM product");
                        foreach ($products as $product) {
                        ?>
                            <option value="<?php echo $product['product_id'] ?>"><?php echo $product['product_id'] ?></option>
                        <?php }
                        ?>

                    </select>
                </div>
                <div class="d-grid gap-2 mt-2">
                    <label for="" class="fw-semibold">To</label>
                    <select name="" class="form-control" id="inventoryConnectToProductNameInput">
                        <option value="" disabled selected>Select Product</option>
                        <?php
                        $products = db::crud("SELECT product_id FROM product");
                        foreach ($products as $product) {
                        ?>
                            <option value="<?php echo $product['product_id'] ?>"><?php echo $product['product_id'] ?></option>
                        <?php }
                        ?>

                    </select>
                </div>

                <p id="inventoryAddConnectAlert" class="fw-semibold text-danger mt-2 mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="productConnect()">Connect</button>
            </div>
        </div>
    </div>
</div>