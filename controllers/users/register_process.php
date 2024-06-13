
<?php
require_once '../connection.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmedPassword = $_POST['password2'];

$errors = 0; 

// Check if username exists
$query = "SELECT username FROM users WHERE username = '$username'";
$result = mysqli_query($cn, $query);
if(mysqli_fetch_assoc($result)) {
    $errors++;
    echo "<script>alert('Username is already taken');</script>";
}

// Check password length
if(strlen($password) < 8) {
    $errors++;
    echo "<script>alert('Password must have at least 8 characters.');</script>";
}

// Check password match
if($password != $confirmedPassword) {
    $errors++;
    echo "<script>alert('Password and Confirm Password should Match.');</script>";
}

// Check email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors++;
    echo "<script>alert('It is not a valid email address.');</script>";
}


// Redirect back to register page if errors exist
if($errors > 0) {
    echo "<script>window.location.href = '/views/pages/register.php';</script>";
    mysqli_close($cn);
    exit;
}

// Proceed with registration if there are no errors
if($errors === 0){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    mysqli_query($cn, $query);
    mysqli_close($cn);
    
    echo "<script>
    alert('Registration successful. You can now log in.');
    window.location.href = '/views/pages/login.php';
    </script>";
}
?>
