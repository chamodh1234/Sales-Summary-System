<div class="pt-2">
    <div class="wrapper">
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop"> Add Lorry</button>

    </div>
    <div class="row row-cols-4 gap-2 justify-content-start wrapper mt-3">

        <?php

        $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . date('Y-m-d') . "'");
        $Date_id = $date_id->fetch_assoc();
        if($date_id->num_rows>0){
             $lorries = db::crud("SELECT lorry_lorry_number FROM daily_lorry_loading WHERE date_id='".$Date_id['id']."'"); 
        
      

        foreach ($lorries as $lorry) {
        ?>

            <a href="?lr_id=<?php echo $lorry['lorry_lorry_number'] ?>" class="text-decoration-none">
                <div class="card border-top-primary border-top-3 mb-3" style="max-width: 15rem;">
                    <div class="card-header d-flex justify-content-between align-content-center">

                        <img src="../public/lorry_face.png" width="30" height="30" alt="">
                        <h5 class="card-title text-primary m-0 align-content-center"><?php echo $lorry['lorry_lorry_number'] ?></h5>
                    </div>
                    <div class="card-body fw-semibold">
                        <p class="card-text m-0 mb-2 fw-bold">Date : <?php echo date('m-d') ?> </p>
                       
                    </div>
                </div>
            </a>

        <?php }
        }
        ?>



    </div>


</div>




<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-coreui-backdrop="static" data-coreui-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="staticBackdropLabel">Add Lorry</h5>

            </div>
            <div class="modal-body fw-semibold">
                <div class="d-grid gap-2">

                    <div>
                        <label for="" class="d-flex gap-2">Select Lorry : <p class="text-danger">*</p></label>
                        <select name="" class='form-control' id="productLoadingSelectLorry">
                            <option value="" selected disabled>Select Lorry</option>
                            <?php
                            $lorry_names = db::crud("SELECT lorry_number FROM lorry");
                            foreach ($lorry_names as $lorry_name) {
                            ?>
                                <option value="<?php echo $lorry_name['lorry_number'] ?>"><?php echo $lorry_name['lorry_number'] ?></option>
                            <?php
                            }
                            ?>



                        </select>
                    </div>
                    <div>
                        <label for="" class="d-flex gap-2">Select Ref : <p class="text-danger">*</p></label>
                        <select name="" class='form-control' id="productLoadingSelectRef">
                            <option value="" selected disabled>Select Ref</option>
                            <?php

                            $refs = db::crud("SELECT ref_id FROM ref");
                            foreach ($refs as $ref) {
                            ?>
                                <option value="<?php echo $ref['ref_id'] ?>"><?php echo $ref['ref_id'] ?></option>
                            <?php
                            }
                            ?>


                        </select>
                    </div>
                    <div>
                        <label for="" class="d-flex gap-2">Select Route : <p class="text-danger">*</p></label>
                        <select name="" class='form-control' id="productLoadingSelectRoute">
                            <option value="" selected disabled>Select Route</option>
                            <?php

                            $route = db::crud("SELECT `route` FROM `route`");
                            foreach ($route as $Route) {
                            ?>
                                <option value="<?php echo $Route['route'] ?>"><?php echo $Route['route'] ?></option>
                            <?php
                            }
                            ?>


                        </select>
                    </div>

                    <p class="text-danger" id="productLoadingAddLorryAlert"></p>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="productLoadingAddLorry()" id="productLoadingAddLorryBtn">Add</button>
            </div>
        </div>
    </div>
</div>