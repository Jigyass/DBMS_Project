<?php
include "header.php";

$msg = "";
$error = 0;
if(isset($_POST['save']))
{
    $guest_id = $_POST['guest_id'];
    $guest_name = $_POST['guest_name'];
    $guest = getSingleRow("SELECT * FROM `guests` where guest_name='$guest_name' and guest_id != '$guest_id'");
    if(isset($guest['guest_id']))
    {
        $error = 1;
        $msg = "guest name already exists";
    }
    else
    {
        if($guest_id)
        {
            ins_upd_del("UPDATE `guests` SET `guest_name`='$guest_name' WHERE guest_id = '$guest_id'");
        }
        else
        {
            ins_upd_del("INSERT INTO `guests`(`guest_name`) VALUES ('$guest_name')");
        }
        $msg = "Record save";
    }

}

if(isset($_POST['editGuest']))
{
    $guest_id = $_POST['guest_id'];
    $editGuest = getSingleRow("SELECT * FROM `guests` where guest_id='$guest_id'");
}

if(isset($_POST['deleteGuest']))
{
    $guest_id = $_POST['guest_id'];
    ins_upd_del("DELETE FROM `guests` where guest_id='$guest_id'");
    $msg = "Record Deleted";
}
?>
<h3>Add guest</h3><br>
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
            <input type="hidden" name="guest_id" value="<?php echo isset($editGuest['guest_id']) ? $editGuest['guest_id'] : "" ?>">
            <div class="form-group">
                <label for="guest_name">Guest Name:</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" value="<?php echo isset($editGuest['guest_name']) ? $editGuest['guest_name'] : "" ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="save">Submit</button>
        </form>
    </div>

    <div class="col-12">
        <h2>List of guests</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>S.No</th>
                    <th>Guest Name</th>
                    <th>-</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    // Replace with your PHP code to fetch the data from the database
                    $guests = getMultipleRows("SELECT * FROM `guests`");

                    $sno = 0;
                    foreach ($guests as $guest) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $sno . '</td>';
                            echo '<td>' . $guest['guest_name'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="guest_id" value="'.$guest['guest_id'].'">
                                        <input type="submit" value="Edit" name="editGuest">
                                        <input type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteGuest">
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