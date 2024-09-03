    <?php
    if (isset($_GET['lr'])) {

    ?>
        <div class="d-flex justify-content-between">
            <button class="btn btn-secondary mt-1" onclick="goBack()">back</button>


            <?php
            if (isset($_GET['m']) && isset($_GET['y']) && !isset($_GET['dt']) && isset($_GET['mss']) && isset($_GET['lr'])) {
            ?>

                <div class="col-4 mt-1 d-flex">
                    <p class="col-6 d-flex justify-content-end pe-3 fw-semibold">Select Ref : </p>
                    <select name="" id="refInputMonthlySummary" class="form-control bg-primary text-white"
                        onchange="selectRefMonthlySummary('<?php echo $_GET['m'] ?>','<?php echo $_GET['y'] ?>','<?php echo $_GET['lr'] ?>')">
                        <option value="" selected disabled class="bg-primary">Select Ref</option>
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
            <?php }

            ?>


        </div>


        <?php 
        if(isset($_GET['m']) && isset($_GET['y']) && !isset($_GET['dt']) && isset($_GET['mss']) && isset($_GET['lr']) && !isset($_GET['rid'])){
?>
<p class="d-flex justify-content-center fw-semibold fs-4 ">Select Ref</p>

   <?php  
   
}
        
        
        ?>
        

    <?php }

    ?>


    <?php
    if (!isset($_GET['lr'])) {
    ?>

        <div class="fw-semibold fs-2 mt-2">
            Check Summary
        </div>

    <?php }

    ?>
    <?php
    if (!isset($_GET['lr'])) {
    ?>
        <div class="row row-cols-4">
            <div>
                <label for="" class="fw-semibold">Select Date :</label>
                <div class="col-8 d-flex">

                    <input type="date" name="" class="form-control" id="summaryDateInput" onchange="checkSummary()">
                </div>
            </div>
            <div>
                <label for="" class="fw-semibold">Select Month :</label>
                <div class="col-12 d-flex">
                    <select name="" class="form-control" id="monthSlectSummaryInput">
                        <option value="" selected disabled>Select Month</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">Auguest</option>
                        <option value="09">Spetember</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="" class="fw-semibold">Select Year :</label>
                <div class="col-12 d-flex">
                    <select name="" id="yearSelectSummary" class="form-control" id>
                        <option value="" selected disabled>Select Month</option>
                        <?php
                        for ($x = 2024; $x < 2050; $x++) {
                        ?>
                            <option value="<?php echo $x ?>"><?php echo $x ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class=" d-flex align-items-end">

                <div class="col-12">

                    <button class="btn btn-primary col-6" onclick="monthlyView()">Apply</button>
                </div>
            </div>

        </div>

    <?php
    }

    if (isset($_GET['dt']) && !isset($_GET['lr'])) {
    ?>
        <div class="row row-cols-5 gap-1 justify-content-start wrapper mt-3">
            <?php
            $date_id = db::crud("SELECT id FROM `date` WHERE `date`='" . $_GET['dt'] . "' ");
            $Date_id = $date_id->fetch_assoc();
            if ($date_id->num_rows > 0) {
                $lorry_loading_id = db::crud("SELECT `lorry_lorry_number`,`ref_ref_id` FROM daily_lorry_loading WHERE date_id = '" . $Date_id['id'] . "'");
                if ($lorry_loading_id->num_rows > 0) {
                    foreach ($lorry_loading_id as $id) {
            ?>
                        <a href="?comp=1006&lr=<?php echo $id['lorry_lorry_number'] ?>&dt=<?php echo $_GET['dt'] ?>" class="text-decoration-none p-0 text-dark">
                            <div class="card border-top-primary border-top-3 mb-3" style="max-width: 17rem;">
                                <div class="card-body fw-semibold d-flex justify-content-between">
                                    <img src="../public/lorry_face.png" width="40" height="40" alt="">
                                    <div class="d-grid">
                                        <h5 class="card-title text-primary m-0 align-content-center"><?php echo $id['lorry_lorry_number'] ?></h5>
                                        <p class="m-0"><?php echo $id['ref_ref_id'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php
                    }
                    ?>
        </div>
    <?php
                } else {
    ?>
        <div class="d-flex col-12 justify-content-center align-items-center " style="height: 50vh;">
            <div class="">
                <div class="d-flex justify-content-center">
                    <img src="../public/nodata.png" alt="" width="100" height="90">
                </div>

                <p class="fw-semibold fs-4">You are not load the products</p>
            </div>

        </div>

    <?php   }
            } else {
    ?>
    <div class="d-flex col-12 justify-content-center align-items-center " style="height: 50vh;">
        <div class="">
            <div class="d-flex justify-content-center">
                <img src="../public/nodata.png" alt="" width="100" height="90">
            </div>

            <p class="fw-semibold fs-4">You are not yet work on this day</p>
        </div>

    </div>
    <?php
            }

    ?>


    <div>
    <?php
    } else if (isset($_GET['lr']) && !isset($_GET['mss'])) {

        require '../components/lorrysummary.php';
    }    ?>





    <?php

    if (isset($_GET['m']) && isset($_GET['y']) && !isset($_GET['dt']) && !isset($_GET['mss'])) {

    ?>
        <div class="mt-2 fw-semibold fs-3">All Listed Lorries</div>

        <div class="row row-cols-5 gap-1 justify-content-start wrapper mt-3">
            <?php
            $lorry_id = db::crud("SELECT * FROM lorry");
            if ($lorry_id->num_rows > 0) {
                foreach ($lorry_id as $id) {
            ?>
                    <a href="?comp=1006&m=<?php echo $_GET['m'] ?>&y=<?php echo $_GET['y'] ?>&mss=true&lr=<?php echo $id['lorry_number'] ?>" class="text-decoration-none p-0 text-dark">
                        <div class="card border-top-primary border-top-3 mb-3" style="max-width: 17rem;">
                            <div class="card-body fw-semibold d-flex justify-content-between">
                                <img src="../public/lorry_face.png" width="40" height="40" alt="">
                                <div class="d-grid">
                                    <h5 class="card-title text-primary m-0 align-content-center"><?php echo $id['lorry_number'] ?></h5>
                                    <p class="m-0"></p>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php
                }
                ?>
        </div>
    <?php
            } else {
                echo "No lorry listed here";
            }
    ?>

    <div>
    <?php
    } else if (isset($_GET['lr']) && isset($_GET['m']) && isset($_GET['y']) && !isset($_GET['dt']) && isset($_GET['mss'])) {

        require '../components/monthlylorrysummary.php';
    }
    ?>
    </div>

    </div>