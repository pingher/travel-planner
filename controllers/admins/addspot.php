<?php
require_once '../connection.php';


$title = mysqli_real_escape_string($cn, $_POST['title']);
$description = mysqli_real_escape_string($cn, $_POST['description']);
$state = $_POST['state'];
$weather = $_POST['weather'];
$price = $_POST['price'];

$image = "";

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
                window.location.href = '/views/pages/admins/locations.php';
                </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Invalid image file.');
            window.location.href = '/views/pages/admins/locations.php';
            </script>";
        exit;
    }
}

$query = "INSERT INTO spot_recommendations (title,description,state,weather,price,image) 
          VALUES ('$title', '$description', '$state', '$weather','$price', '$image')";


if (mysqli_query($cn, $query)) {
    header('Location: /views/pages/admins/locations.php');
    exit;
} else {
    echo "<script>
        alert('Error: ');
        window.location.href = '/views/pages/admins/locations.php';
        </script>";
    exit;
}

header('Location: /views/pages/admins/locations.php');
exit;
?>