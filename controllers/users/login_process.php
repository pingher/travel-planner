<?php 
require_once '../connection.php';
$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = '$username'";
$user = mysqli_fetch_assoc(mysqli_query($cn, $query));

//if user exist
if($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_info'] = $user;
    mysqli_query($cn, $query);
    mysqli_close($cn);
    echo "<script>
    alert('Log In successful.');
    window.location.href = '/';
    </script>";
} else {
    mysqli_close($cn);
    echo "<script>
    alert('Log In Failed. Try again.');
    window.location.href= '/views/pages/login.php';
    </script>";
}