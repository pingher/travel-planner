<?php
require_once '../connection.php';
session_start();


$user_id = $_SESSION['user_info']['id'];

//IMAGE HANDLING
if (isset($_FILES['image'])) {
    $img_name = $_FILES['image']['name'];
    $img_size = $_FILES['image']['size'];
    $img_tmpname = $_FILES['image']['tmp_name'];
    $img_type = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $img_path = "/public/" . time() . "-" . $img_name; 

    $extensions = ['jpg', 'jpeg', 'png', 'svg', 'gif'];

    if (in_array($img_type, $extensions) && $img_size > 0) {
        $query = "UPDATE users SET pic = '$img_path' WHERE id = $user_id";

        if (mysqli_query($cn, $query)) {
            move_uploaded_file($img_tmpname, "../.." . $img_path);
            mysqli_close($cn);
            header("Location: $_SERVER[HTTP_REFERER]");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($cn);
        }
    } else {
        echo "<script>
        alert('Please upload a valid image file.');
        window.location.href = '/views/pages/stories.php';
        </script>";
    }
}
?>
