<?php
global $conn;

$conn = @mysqli_connect('localhost','root','', 'restaurant');
if (!$conn) {
	die('Could not connect: ' . mysqli_error());
}


function sanitizeData($data)
{
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);

	global $conn;

	$data = mysqli_real_escape_string($conn, $data);
    return $data;
}

function ins_upd_del($query)
{
	global $conn;
    mysqli_query($conn, $query);
	$result = mysqli_insert_id($conn);
	return $result;
}


function delete($query)
{
	global $conn;
    return mysqli_query($conn, $query);
}

function getSingleRow($query)
{
	global $conn;
	$re = mysqli_query($conn, $query);
	$re = mysqli_fetch_assoc($re);
	return $re;
}

function getMultipleRows($query)
{
	global $conn;
	$re = mysqli_query($conn, $query);
	$result = array();
	while($row = mysqli_fetch_assoc($re))
	{
		array_push($result, $row);
	}
	
	return $result;
}

function no_of_rows($query)
{
	global $conn;
	$re = mysqli_query($conn, $query);
	$rows = mysqli_num_rows($re);
	return $rows;
}
?>

