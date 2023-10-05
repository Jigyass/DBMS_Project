<?php
include "header.php";
?>
<h3>What We Do</h3><br>
<div class="row">
    <div class="col-sm-4">
        <div class="well text-center">
            <h3>No of Zones</h3>
            <h2><?php echo no_of_rows("SELECT * FROM `zone`") ?></h2>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="well text-center">
            <h3>No of Tables</h3>
            <h2>
                <?php 
                $tables = no_of_rows("SELECT * FROM `table`");
                echo $tables; 
                ?></h2>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="well text-center">
            <h3>No of Servers</h3>
            <h2><?php echo no_of_rows("SELECT * FROM `server`") ?></h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="well text-center">
            <h3>Tables Booked</h3>
            <h2>
                <?php 
                $booked = no_of_rows("SELECT * FROM `booking` WHERE `is_vacant` = 0");
                echo $booked 
                ?>
            </h2>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well text-center">
            <h3>Tables Available</h3>
            <h2><?php echo $tables - $booked ?></h2>
        </div>
    </div>
</div>


<?php
include "footer.php";
?>