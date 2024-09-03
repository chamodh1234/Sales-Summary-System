<div class="wrapper">

    <div class=" mt-3">
        <button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#staticBackdrop"> Add Route</button>

    </div>
    <div class="fw-semibold fs-2 mt-2">
        Listed Routes
    </div>
</div>


    <div class="row row-cols-4 gap-2 justify-content-start wrapper mt-3">
       <?php 
       $routes = db::crud("SELECT * FROM `route`");

       foreach($routes as $route){
        ?>
<div class="card border-top-primary border-top-3 mb-3" style="max-width: 15rem;">
               
                <div class="card-body fw-semibold d-flex justify-content-between">
                     <img src="../public/listed-route.png" width="40" height="40" alt="">
                    <h5 class="card-title text-primary m-0 align-content-center">Route</h5>
                  
                  
                </div>
                  <div>
                        <?php echo $route['route'] ?>
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
                <h5 class="modal-title" id="staticBackdropLabel">Add Route</h5>
     
            </div>
            <div class="modal-body fw-semibold">
                <div class="d-grid gap-2">
                    <div>
                        <label for="">Route :</label>
                       <input type="text" class="form-control" id="routeRegisterInput">
                    </div>
                    
                   
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="registerRoute()">Add</button>
            </div>
        </div>
    </div>
</div>
