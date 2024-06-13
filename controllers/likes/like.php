<?php
require_once '../connection.php';
session_start();

$id = $_GET['id'];
$user_id = $_SESSION['user_info']['id'];

// Check if the user has already liked the post
$query = "SELECT * FROM likes WHERE story_id = $id AND user_id = $user_id;";
$result = mysqli_query($cn, $query);
$likes = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($likes)) {
    // If the user hasn't liked the post, insert a new like
    $query = "INSERT INTO likes (user_id, story_id) VALUES ('$user_id', '$id');";
} else {
    // If the user has already liked the post, remove the like
    $query = "DELETE FROM likes WHERE story_id = $id AND user_id = $user_id;";
}

mysqli_query($cn, $query);
mysqli_close($cn);

header("Location: " . $_SERVER['HTTP_REFERER']);  // Redirect back to the previous page
exit;
?>

