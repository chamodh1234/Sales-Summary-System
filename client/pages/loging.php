<?php 
session_start();
require '../../server/middleware/middleware.php';
Middleware::checklog();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="client/bootstrap.css">
    <link rel="stylesheet" href="client/global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-OaSt6YlNk8f06OeGRPsV4UfP2F3Si8sd9Rqxt7iOdIsBKk+zbBLgwCyBwoBqLjDE" crossorigin="anonymous">
    <title>amd_project</title>
</head>

<body class="nunito-light">

    <div class="">
        <div class="d-flex justify-content-center fs-2 fw-semibold" style="margin-top: 150px;">Loging</div>
        <div class="d-flex justify-content-center">
            <div class="fw-semibold mt-4 border border-3 border-primary rounded rounded-2 p-5 shadow-lg  ">
                <div>
                    <label for="">User Name :</label>
                    <div>
                        <input type="text" name="" id="userName" class="form-control border border-1 border-secondary">
                    </div>
                </div>
                <div>
                    <label for="">Password :</label>
                    <div>
                        <input type="password" name="" id="password" class="form-control border border-1 border-secondary">
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-primary" onclick="loging()">Loging</button>
                </div>
            </div>

        </div>

    </div>


<script src="../index.js"></script>
</body>

</html>