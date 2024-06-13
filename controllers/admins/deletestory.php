<?php
require_once '../connection.php';

    $id = $_POST['id'];

    $query = "UPDATE stories SET deleted = 1 WHERE id = $id";
    $result = mysqli_query($cn, $query);

    if ($result) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error updating record: " . mysqli_error($cn);
    }

    mysqli_close($cn);
?>
