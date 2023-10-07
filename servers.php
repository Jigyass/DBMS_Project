<?php
include "header.php";

$msg = "";
$error = 0;
if(isset($_POST['save']))
{
    $server_id = $_POST['server_id'];
    $server_name = $_POST['server_name'];
    $zone_id = $_POST['zone_id'];

    $server = getSingleRow("SELECT * FROM `server` where server_name='$server_name' and server_id != '$server_id'");
    if(isset($server['server_id']))
    {
        $error = 1;
        $msg = "server name already exists";
    }
    else
    {
        if($server_id)
        {
            ins_upd_del("UPDATE `server` SET `server_name`='$server_name', `zone_id`='$zone_id' WHERE server_id = '$server_id'");
        }
        else
        {
            ins_upd_del("INSERT INTO `server`(`server_name`,`zone_id`) VALUES ('$server_name','$zone_id')");
        }
        $msg = "Record save";
    }

}

if(isset($_POST['editServer']))
{
    $server_id = $_POST['server_id'];
    $editServer = getSingleRow("SELECT * FROM `server` where server_id='$server_id'");
}

if(isset($_POST['deleteServer']))
{
    $server_id = $_POST['server_id'];
    ins_upd_del("DELETE FROM `server` where server_id='$server_id'");
    $msg = "Record Deleted";
}
?>
<h3>Add server</h3><br>
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
            <input type="hidden" name="server_id"
                value="<?php echo isset($editServer['server_id']) ? $editServer['server_id'] : "" ?>">
            <div class="form-group">
                <label for="server_name">Server Name:</label>
                <input type="text" class="form-control" id="server_name" name="server_name"
                    value="<?php echo isset($editServer['server_name']) ? $editServer['server_name'] : "" ?>" required>
            </div>
            <div class="form-group">
                <label for="zone_id">Zone:</label>
                <select class="form-control" name="zone_id" required>
                    <?php 
                    $zones = getMultipleRows("SELECT * FROM zone");
                    foreach($zones as $zone)
                    {
                        $sel = "";
                        if(isset($editServer['zone_id']))
                        {
                            if($editServer['zone_id'] == $zone['zone_id'])
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
        <h2>List of servers</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Server Name</th>
                        <th>Zone</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Replace with your PHP code to fetch the data from the database
                    $servers = getMultipleRows("SELECT * FROM `server` s join zone z on s.zone_id = z.zone_id");
                    $sno = 0;
                    foreach ($servers as $server) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $sno . '</td>';
                            echo '<td>' . $server['server_name'] . '</td>';
                            echo '<td>' . $server['zone_name'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="server_id" value="'.$server['server_id'].'">
                                        <input type="submit" value="Edit" name="editServer">
                                        <input type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteServer">
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