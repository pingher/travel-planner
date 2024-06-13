<?php
require_once '../connection.php';
session_start();

$user_id = $_SESSION['user_info']['id'];

// to avoid error if put '
$title = mysqli_real_escape_string($cn, $_POST['title']);
$description = mysqli_real_escape_string($cn, $_POST['description']);


$image = "";

// Created date
date_default_timezone_set('Asia/Kuala_Lumpur');
$date = date("Y-m-d H:i:s");

// Check if an image is uploaded
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $img_name = $_FILES['image']['name'];
    $img_tmpname = $_FILES['image']['tmp_name'];
    $img_type = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $img_path = "/public/" . time() . "-" . $img_name;

    $extensions = ['jpg', 'jpeg', 'png', 'svg', 'gif'];

    if (in_array($img_type, $extensions)) {
        // Move the uploaded file
        if (move_uploaded_file($img_tmpname, "../.." . $img_path)) {
            $image = $img_path;
        } else {
            echo "<script>
                alert('Error uploading the image.');
                window.location.href = '/views/pages/stories.php';
                </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Invalid image file.');
            window.location.href = '/views/pages/stories.php';
            </script>";
        exit;
    }
}


$query = "INSERT INTO stories (user_id, title, description, photo, created_at) 
          VALUES ('$user_id', '$title', '$description', '$image', '$date')";
echo $query;
if (mysqli_query($cn, $query)) {
    echo "<script>
        alert('Story added successfully.');
        window.location.href = '/views/pages/stories.php';
        </script>";
} else {
    echo "<script>
        alert('Error adding story.');
        window.location.href = '/views/pages/stories.php';
        </script>";
}


mysqli_close($cn);
?>
