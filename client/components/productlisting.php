<div class="wrapper">
<div class="d-flex gap-2">
    <div class=" mt-3">
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop"> List Product</button>

    </div>
    <div class=" mt-3">
        <button type="button" class="btn btn-danger" data-coreui-toggle="modal" data-coreui-target="#delete">Delete Product</button>

    </div>
</div>
    
    <div class="fw-semibold fs-2 mt-2">
        Listed Items
    </div>
</div>
<div class="row row-cols-4 gap-2 justify-content-start wrapper mt-3">
    <?php

    $products = db::crud("SELECT * FROM product");

    foreach ($products as $product) {
    ?>
        <div class="card border-top-primary border-top-3 " style="max-width: 15rem;">
            <div class="card-header d-flex justify-content-between align-content-center">

                <img src="../public/products.png" width="30" height="30" alt="">
                <h5 class="card-title text-primary m-0 align-content-center"><?php echo $product['product_id'] ?></h5>
            </div>
            <div class="card-body fw-semibold">

                <p class="card-text m-0">Unit Price : <?php echo $product['unit_price'] ?></p>

            </div>
        </div>


    <?php
    }

    ?>
</div>




<div class="modal fade" id="staticBackdrop" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="staticBackdropLabel">Add Product</h5>

            </div>
            <div class="modal-body fw-semibold">
                <div class="d-grid gap-2">
                    <div>
                        <label for="">Product Name :</label>
                        <input type="text" id="productNameRegInput" class="form-control">
                    </div>
                    <div>
                        <label for="">Unit Price :</label>
                        <input type="Number" id="productUnitPriceRegInput" class="form-control">
                    </div>

                </div>

                <p class="text-danger mt-2 mb-0" id="productRegAlert"></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="registerProduct()">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="staticBackdropLabel">Delete Product</h5>

            </div>
            <div class="modal-body fw-semibold">
                <div class="d-grid gap-2">
                    <div>
                        <label for="">Product Name :</label>
                        <input type="text" id="productNameRegInputDel" class="form-control">
                    </div>
                </div>

                <p class="text-danger mt-2 mb-0" id="productDelAlert"></p>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="deleteProduct()">Delete</button>
            </div>
        </div>
    </div>
</div>