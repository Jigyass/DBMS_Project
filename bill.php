<?php
session_start();
include "database.php";
if(!isset($_SESSION['restaurant_user_id']))
{
    header("location: login.php");
    return;
}

if(!isset($_GET['booking_id']))
{
    header("location: bookings.php");
    return;
}

$booking_id = $_GET['booking_id'];

$booking = getSingleRow("SELECT * FROM `booking` b 
                              join `table` t on b.table_id = t.table_id
                              join `guests` g on b.guest_id = g.guest_id
                              WHERE b.booking_id = '$booking_id'");


if(!isset($booking['booking_id']))
{
    header("location: bookings.php");
    return;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    .bill {
        padding: 10px;
    }
    table{
      margin: 0 auto;
      border: 1px solid;
    }

    .bill th,
    .bill td {
        border: 1px solid black;
        padding: 5px;
    }

    h1{
      text-align: center;
    }
    </style>

    <title>Bill</title>
</head>

<body>

    <div class="bill">
        <h1>Bill</h1>
        <table>
            <tr>
                <th>Guest Name:</th>
                <td><?php echo $booking['guest_name'] ?></td>
            </tr>
            <tr>
                <th>Date:</th>
                <td><?php echo $booking['date'] ?></td>
            </tr>
            <tr>
                <th>Time:</th>
                <td><?php echo $booking['time'] ?></td>
            </tr>
            <tr>
                <th>Table No:</th>
                <td><?php echo $booking['table_no'] ?></td>
            </tr>
            <tr>
                <th>Items:</th>
                <td><?php echo $booking['items'] ?></td>
            </tr>
            <tr>
                <th>Price:</th>
                <td><?php echo $booking['price'] ?></td>
            </tr>
        </table>
    </div>

</body>

</html>