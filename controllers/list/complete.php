<?php
require_once '../connection.php';

session_start();
$user_id = $_SESSION['user_info']['id'];
$id = $_POST['id'];

// checking the completion status is 1 or 0
$checking = "SELECT * FROM togolist WHERE user_id=$user_id AND id = $id AND completion_status = 1";
$result = mysqli_query($cn, $checking);

if (mysqli_num_rows($result) > 0) {
    // checked and found out got completed one.
    echo "<script>
        alert('Task is already completed.');
        window.location.href = '/views/pages/lists.php';
    </script>";
} else {
    // not completed, Update the completion status 
    $query = "UPDATE togolist SET completion_status = 1 WHERE user_id = $user_id AND id = $id";
    $result = mysqli_query($cn, $query);
    echo"<script>window.location.href = '/views/pages/lists.php';</script>";
}

mysqli_close($cn);
?>
