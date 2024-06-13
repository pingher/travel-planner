<?php
require_once '../connection.php';

session_start();
$user_id = $_SESSION['user_info']['id'];
$togo_id = $_POST['togo_id'];
$description = mysqli_real_escape_string($cn, $_POST['description']);

$query = "INSERT INTO notes (user_id, togo_id, notedescription) VALUES ('$user_id', '$togo_id', '$description');";
$result = mysqli_query($cn, $query);

mysqli_close($cn);


header("Location: " . $_SERVER['HTTP_REFERER']);  

?>
