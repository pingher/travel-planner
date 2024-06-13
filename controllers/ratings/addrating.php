<?php
require_once '../connection.php';

session_start();
$user_id = $_SESSION['user_info']['id'];
$location_id = $_POST['spot_id'];
$rating = $_POST['rating'];

// needs to check weather the same user rated the same location
// if yes, then edit if user change the rating update
// if no then insert
$check_query = "SELECT * FROM ratings WHERE user_id = '$user_id' AND location_id = '$location_id'";
$check_result = mysqli_query($cn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // have rated once
    $query = "UPDATE ratings SET ratings = '$rating' WHERE user_id = '$user_id' AND location_id = '$location_id'";
    $result = mysqli_query($cn, $query);
} else {
    // no rating before
    $query = "INSERT INTO ratings (user_id, location_id, ratings) VALUES ('$user_id', '$location_id', '$rating')";
    $result = mysqli_query($cn, $query);
}

mysqli_close($cn);

?>
