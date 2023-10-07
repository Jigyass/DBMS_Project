<?php
include "header.php";

$msg = "";
$error = 0;
if(isset($_POST['save']))
{
    $table_id = $_POST['table_id'];
    $table_no = $_POST['table_no'];
    $zone_id = $_POST['zone_id'];

    $table = getSingleRow("SELECT * FROM `table` where table_no='$table_no' and table_id != '$table_id'");
    if(isset($table['table_id']))
    {
        $error = 1;
        $msg = "Table no already exists";
    }
    else
    {
        if($table_id)
        {
            ins_upd_del("UPDATE `table` SET `table_no`='$table_no', `zone_id`='$zone_id' WHERE table_id = '$table_id'");
        }
        else
        {
            ins_upd_del("INSERT INTO `table`(`table_no`,`zone_id`) VALUES ('$table_no','$zone_id')");
        }
        $msg = "Record save";
    }

}

if(isset($_POST['editTable']))
{
    $table_id = $_POST['table_id'];
    $editTable = getSingleRow("SELECT * FROM `table` where table_id='$table_id'");
}

if(isset($_POST['deleteTable']))
{
    $table_id = $_POST['table_id'];
    ins_upd_del("DELETE FROM `table` where table_id='$table_id'");
    $msg = "Record Deleted";
}
?>
<h3>Add table</h3><br>
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
            <input type="hidden" name="table_id" value="<?php echo isset($editTable['table_id']) ? $editTable['table_id'] : "" ?>">
            <div class="form-group">
                <label for="table_no">Table No:</label>
                <input type="text" class="form-control" id="table_no" name="table_no" value="<?php echo isset($editTable['table_no']) ? $editTable['table_no'] : "" ?>" required>
            </div>
            <div class="form-group">
                <label for="zone_id">Zone:</label>
                <select class="form-control" name="zone_id" required>
                    <?php 
                    $zones = getMultipleRows("SELECT * FROM zone");
                    foreach($zones as $zone)
                    {
                        $sel = "";
                        if(isset($editTable['table_no']))
                        {
                            if($editTable['zone_id'] == $zone['zone_id'])
                            {
                                $sel = " selected ";
                            }
                        }
                        echo '<option value="'.$zone['zone_id'].'" '.$sel.'>'.$zone['zone_name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="save">Submit</button>
        </form>
    </div>

    <div class="col-12">
        <h2>List of Tables</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th>Table No</th>
                    <th>Zone</th>
                    <th>-</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    // Replace with your PHP code to fetch the data from the database
                    $tables = getMultipleRows("SELECT * FROM `table` t join zone z on t.zone_id = z.zone_id");
                    $sno = 0;
                    foreach ($tables as $table) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $sno . '</td>';
                            echo '<td>' . $table['table_no'] . '</td>';
                            echo '<td>' . $table['zone_name'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="table_id" value="'.$table['table_id'].'">
                                        <input type="submit" value="Edit" name="editTable">
                                        <input type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteTable">
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