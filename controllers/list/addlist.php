<?php
require_once '../connection.php';

session_start();
$user_id = $_SESSION['user_info']['id'];
$spot_id = $_POST['spot_id'];
$description = mysqli_real_escape_string($cn, $_POST['description']);
$title = mysqli_real_escape_string($cn, $_POST['title']);

$created_at = date("Y-m-d H:i:s");

$checkingquery = "SELECT * FROM togolist WHERE user_id = $user_id AND location_id = $spot_id;";
$checkingresult = mysqli_query($cn, $checkingquery);
if (mysqli_num_rows($checkingresult) > 0) {
    // have added once
    echo "<script>
                alert('It is already in your TOGO list.');
                window.location.href = '/views/pages/locations.php';
        </script>";
} else {
    $query = "INSERT INTO togolist (location_id, description, created_at, user_id, title) VALUES ('$spot_id', '$description', '$created_at', '$user_id', '$title');";
    $result = mysqli_query($cn, $query);
    echo "<script>
            alert('Added to your TOGO list successfully.');
            window.location.href = '/views/pages/locations.php';
          </script>";
}


mysqli_close($cn);

?>
