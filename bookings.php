<?php
include "header.php";

$msg = "";
$error = 0;
if(isset($_POST['save']))
{
    $booking_id = $_POST['booking_id'];
    $guest_id = $_POST['guest_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $table_id = $_POST['table_id'];
    $items = $_POST['items'];
    $price = $_POST['price'];
    $is_vacant = 0;

    $booking = getSingleRow("SELECT * FROM `booking` WHERE `table_id` = '$table_id' and is_vacant = 0 and booking_id != '$booking_id'");
    if(isset($booking['booking_id']))
    {
        $error = 1;
        $msg = "Booking already exists";
    }
    else
    {
        if($booking_id)
        {
            ins_upd_del("UPDATE `booking` SET `guest_id`='$guest_id',`date`='$date',`time`='$time',`table_id`='$table_id',
                                        `items`='$items',`price`='$price',`is_vacant`='$is_vacant' WHERE booking_id = '$booking_id'");
        }
        else
        {
            ins_upd_del("INSERT INTO `booking`(`guest_id`, `date`, `time`, `table_id`, `items`, `price`, `is_vacant`) 
                                VALUES ('$guest_id', '$date', '$time', '$table_id', '$items', '$price', '$is_vacant')");
        }
        $msg = "Record save";
    }

}

if(isset($_POST['editBooking']))
{
    $booking_id = $_POST['booking_id'];
    $editBooking = getSingleRow("SELECT * FROM `booking` where booking_id='$booking_id'");
}

if(isset($_POST['deleteBooking']))
{
    $booking_id = $_POST['booking_id'];
    ins_upd_del("DELETE FROM `booking` where booking_id='$booking_id'");
    $msg = "Record Deleted";
}

if(isset($_POST['closeBooking']))
{
    $booking_id = $_POST['booking_id'];
    ins_upd_del("UPDATE `booking` SET `is_vacant`='1' WHERE booking_id='$booking_id'");
}


?>
<h3>New Booking</h3><br>
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
            <input type="hidden" name="booking_id"
                value="<?php echo isset($editBooking['booking_id']) ? $editBooking['booking_id'] : "" ?>">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="<?php echo isset($editBooking['date']) ? $editBooking['date'] : date("Y-m-d") ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" class="form-control" id="time" name="time"
                    value="<?php echo isset($editBooking['time']) ? $editBooking['time'] : "" ?>" required>
            </div>
            <div class="form-group">
                <label for="guest_id">Guest:</label>
                <select class="form-control" name="guest_id" required>
                    <?php 
                    $guests = getMultipleRows("SELECT * FROM guests");
                    foreach($guests as $guest)
                    {
                        $sel = "";
                        if(isset($editBooking['guest_id']))
                        {
                            if($editBooking['guest_id'] == $guest['guest_id'])
                            {
                                $sel = " selected ";
                            }
                        }
                        echo '<option value="'.$guest['guest_id'].'" '.$sel.'>'.$guest['guest_name'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="table_id">Table:</label>
                <select class="form-control" name="table_id" required>
                    <?php 

                    $where = "";
                    if(isset($editBooking['table_id']))
                    {
                        $where = " and table_id != '".$editBooking['table_id']."'";
                    }

                    $tables = getMultipleRows("SELECT * FROM `table` WHERE table_id not in (SELECT `table_id` FROM `booking` WHERE `is_vacant` = 0 $where)");
                    foreach($tables as $table)
                    {
                        $sel = "";
                        if(isset($editBooking['table_id']))
                        {
                            if($editBooking['table_id'] == $table['table_id'])
                            {
                                $sel = " selected ";
                            }
                        }
                        echo '<option value="'.$table['table_id'].'" '.$sel.'>'.$table['table_no'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="items">Items:</label>
                <textarea class="form-control" id="items" name="items"><?php echo isset($editBooking['items']) ? $editBooking['items'] : "" ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Total Price:</label>
                <input type="number" step="0.01" min="1" class="form-control" id="price" name="price"
                    value="<?php echo isset($editBooking['price']) ? $editBooking['price'] : "" ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="save">Submit</button>
        </form>
    </div>

    <?php
    $filter_date = date("Y-m-d");
    if(isset($_POST['filter_date']))
    {
        $filter_date = $_POST['filter_date'];
    }
    ?>

    <div class="col-12">
        <hr>
        <form action="" method="post">
            <label for="filter_date">Filter by Date:</label>
            <input type="date" name="filter_date" value="<?php echo $filter_date ?>">
            <input type="submit" value="Search">
        </form>
        <hr>
    </div>
    <div class="col-12">
        <h2>List of Bookings (Occupied)</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Table</th>
                        <th>Items</th>
                        <th>Price</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Replace with your PHP code to fetch the data from the database
                    $bookings = getMultipleRows("SELECT * FROM `booking` b 
                                                    join `table` t on b.table_id = t.table_id
                                                    join `guests` g on b.guest_id = g.guest_id
                                                    WHERE b.date = '$filter_date' and is_vacant = 0");
                    $sno = 0;
                    foreach ($bookings as $booking) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $booking['guest_name'] . '</td>';
                            echo '<td>' . $booking['date'] . '</td>';
                            echo '<td>' . $booking['time'] . '</td>';
                            echo '<td>' . $booking['table_no'] . '</td>';
                            echo '<td>' . $booking['items'] . '</td>';
                            echo '<td>' . $booking['price'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="booking_id" value="'.$booking['booking_id'].'">
                                        <input type="submit" value="Edit" name="editBooking">
                                        <input type="submit" value="Close" name="closeBooking">
                                        <input type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteBooking">
                                    </form>
                                  </td>';
                        echo '</tr>';
                    }
                ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="col-12">
        <h2>List of Bookings</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Table</th>
                        <th>Items</th>
                        <th>Price</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Replace with your PHP code to fetch the data from the database
                    $bookings = getMultipleRows("SELECT * FROM `booking` b 
                                                    join `table` t on b.table_id = t.table_id
                                                    join `guests` g on b.guest_id = g.guest_id
                                                    WHERE b.date = '$filter_date' and is_vacant = 1");
                    $sno = 0;
                    foreach ($bookings as $booking) 
                    {
                        $sno++;
                        echo '<tr>';
                            echo '<td>' . $booking['guest_name'] . '</td>';
                            echo '<td>' . $booking['date'] . '</td>';
                            echo '<td>' . $booking['time'] . '</td>';
                            echo '<td>' . $booking['table_no'] . '</td>';
                            echo '<td>' . $booking['items'] . '</td>';
                            echo '<td>' . $booking['price'] . '</td>';
                            echo '<td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="booking_id" value="'.$booking['booking_id'].'">
                                        <a class="btn btn-info" href="bill.php?booking_id='.$booking['booking_id'].'" target="_blank">Print Bill</a>
                                        <input class="btn btn-danger" type="submit" value="Delete" onclick="return confirm(\' Do you want to delete this record?\')" name="deleteBooking">
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