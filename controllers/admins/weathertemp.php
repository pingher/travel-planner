<?php
require_once '../connection.php';

$id = $_POST['weatherid'];
$temperature = $_POST['temperature'];

// Update query to change temperature for the given weather ID
$query = "UPDATE weather SET temperature='$temperature' WHERE id='$id'";
$result = mysqli_query($cn, $query);

header('Location: /views/pages/admins/weather.php');
exit;
?>
