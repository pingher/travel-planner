<?php
require_once '../connection.php';

session_start();
$user_id = $_SESSION['user_info']['id'];
$id = $_POST['id'];

// Delete the item from the TOGO list
$query = "DELETE FROM togolist WHERE user_id = $user_id AND id = $id";
$result = mysqli_query($cn, $query);

if ($result) {
    // Item deleted successfully
    echo "<script>
            alert('Item deleted successfully.');
            window.location.href = '/views/pages/lists.php';
          </script>";
} else {
    // Error occurred during deletion
    echo "<script>
            alert('Failed to delete item.');
            window.location.href = '/views/pages/lists.php';
          </script>";
}

mysqli_close($cn);
?>
