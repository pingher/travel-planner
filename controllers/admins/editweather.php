<?php
require_once '../connection.php';

$id = $_POST['id'];  
$weather = $_POST['weather'];
$temperature = $_POST['temperature'];

$query = "SELECT location_state FROM weather WHERE id='$id'";
$result = mysqli_query($cn, $query);
$row = mysqli_fetch_assoc($result);
$state = $row['location_state'];

$query = "UPDATE weather SET weather='$weather', temperature='$temperature' WHERE location_state='$state'";
$result = mysqli_query($cn, $query);

header('Location: /views/pages/admins/weather.php');
exit;
?>
