<?php
include "header.php";

$msg = "";
$error = 0;
if(isset($_POST['save']))
{
    $zone_id = $_POST['zone_id'];
    $zone_name = $_POST['zone_name'];
    $zone = getSingleRow("SELECT * FROM `zone` where zone_name='$zone_name' and zone_id != '$zone_id'");
    if(isset($zone['zone_id']))
    {
        $error = 1;
        $msg = "Zone name already exists";
    }
    else
    {
        if($zone_id)
        {
            ins_upd_del("UPDATE `zone` SET `zone_name`='$zone_name' WHERE zone_id = '$zone_id'");
        }
        else
        {
            ins_upd_del("INSERT INTO `zone`(`zone_name`) VALUES ('$zone_name')");
        }
        $msg = "Record save";
    }

}

if(isset($_POST['editZone']))
{
    $zone_id = $_POST['zone_id'];
    $editZone = getSingleRow("SELECT * FROM `zone` where zone_id='$zone_id'");
}

if(isset($_POST['deleteZone']))
{
    $zone_id = $_POST['zone_id'];
    ins_upd_del("DELETE FROM `zone` where zone_id='$zone_id'");
    $msg = "Record Deleted";
}
?>
<h3>Add Zone</h3><br>
<div class="row">
    <div class="col-12">
        <?php
        if($msg != "")
        {
            $gb_color = "green";
            if($error == 1)
            {
                $gb_color = "red";
            }
            echo "
            <div style='background: ".$gb_color."; color: white; padding: 12px; margin: 12px 0px;'>
                ".$msg."
            </div>";
        }
        ?>
        <form action="" method="post">
            <input type="hidden" name="zone_id" value="<?php echo isset($editZone['zone_id']) ? $editZone['zone_id'] : "" ?>">
            <div class="form-group">
                <label for="zone_name">Zone Name:</label>
                <input type="text" class="form-control" id="zone_name" name="zone_name" value="<?php echo isset($editZone['zone_name']) ? $editZone['zone_name'] : "" ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="save">Submit</button>
        </form>
    </div>

    <div class="col-12">
        <h2>List of Zones</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th>Zone Name</th>
                    <th>-</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    // Replace with your PHP code to fetch the data from the database
                    $zones = getMultipleRows("SELECT * FROM `zone`");

                    $sno = 0;
                    foreach ($zones as $zone) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $sno . '</td>';
                            echo '<td>' . $zone['zone_name'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="zone_id" value="'.$zone['zone_id'].'">
                                        <input type="submit" value="Edit" name="editZone">
                                        <input type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteZone">
                                    </form>
                                  </td>';
                        echo '</tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>


<?php
include "footer.php";
?>