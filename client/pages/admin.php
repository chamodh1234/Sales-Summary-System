<?php 
session_set_cookie_params(24 * 60 * 60,null,null,true,true);
ini_set('session.gc_maxlifetime', 24 * 60 * 60);

session_start();
require '../../server/config/db.php';
require '../../server/middleware/middleware.php';
Middleware::run();
Middleware::setToken();
Middleware::userBasedAction();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap.css">
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../coreui.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-OaSt6YlNk8f06OeGRPsV4UfP2F3Si8sd9Rqxt7iOdIsBKk+zbBLgwCyBwoBqLjDE" crossorigin="anonymous">
    <title>amd_project</title>
</head>

<body class="nunito-light" id="printbody">


<?php 

?>
    <div class="primary_color wrapper align-content-center fs-1 nunito-bold " style="height: 10vh;">
        AKALANKA MILK DISTRIBUTOR
    </div>

    <div class="d-flex " style="height: 90vh;">
        <div class="col-2  d-grid align-content-between ">
            <div>
                <div class=" wrapper fs-3 fw-bold align-content-center  secondery_bg" style="height: 60px;">
                    <?php echo $_SESSION['un'] ?>
                </div>

                <a href="?comp=1001" class="text-decoration-none text-dark">
                <div class="  wrapper fw-semibold align-content-center gap-2 " style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/product_loading.png" height="20" width="20" alt="">
                        <p class="m-0">Product Loading</p>
                    </div>
                </div>
            </a>

                <a href="?comp=1002" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/product.png" height="20" width="20" alt="">
                        <p class="m-0">Register Product</p>
                    </div>
                </div></a>
                <a href="?comp=1003" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/lorry.png" height="20" width="20" alt="">
                        <p class="m-0">Register Lorry</p>
                    </div>
                </div></a>
                <a href="?comp=1004" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/route.png" height="20" width="20" alt="">
                        <p class="m-0">Route listing</p>
                    </div>
                </div></a>
                <a href="?comp=1005" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/add-user.png" height="20" width="20" alt="">
                        <p class="m-0">Register Ref</p>
                    </div>
                </div></a>
                <a href="?comp=1006" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/summary.png" height="20" width="20" alt="">
                        <p class="m-0">Summary</p>
                    </div>
                </div></a>
                <a href="?comp=1007" class="text-decoration-none text-dark">
                <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                    <div class="d-flex gap-2">
                        <img src="../public/warehouse.png" height="20" width="20" alt="">
                        <p class="m-0">Inventory</p>
                    </div>
                </div></a>
            </div>
            
            <div class=" wrapper fw-semibold align-content-center" style="height: 60px;">
                <div class="d-flex justify-content-between" onclick="logout()">
                    <p class="m-0">Logout</p>
                    <img src="../public/logout.png" height="20" width="20" alt="">

                </div>
            </div>

        </div>
        <div class="col-10 component_bg wrapper overflow-y-scroll">
            <?php 
            if (isset($_GET['comp']) && $_GET['comp'] ==1001) {
                require '../components/productloading.php';}
            if (isset($_GET['comp']) && $_GET['comp'] == 1002) require '../components/productlisting.php';
            if (isset($_GET['comp']) && $_GET['comp'] == 1003) require '../components/lorrylisting.php';
            if (isset($_GET['comp']) && $_GET['comp'] == 1004) require '../components/routelisting.php';
            if (isset($_GET['comp']) && $_GET['comp'] == 1005) require '../components/registerref.php';
            if (isset($_GET['comp']) && $_GET['comp'] == 1006) require '../components/summary.php';
            if (isset($_GET['comp']) && $_GET['comp'] == 1007) require '../components/inventory.php';
           if(isset($_GET['lr_id'])) require '../components/lorry.php';
           ?>  

        </div>

    </div>
<script src="../index.js"></script>
<script src="../bootstrap.bundle.min.js"></script>
</body>

</html>