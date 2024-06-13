<?php
require_once '../connection.php';

$date = $_POST['date'];
$weathers = $_POST['weather'];

if (isset($weathers) && !empty($weathers)) {
    foreach ($weathers as $weather => $states) {
        foreach ($states as $state) {
            // Insert data into the database
            $query = "INSERT INTO weather (date, weather, location_state) VALUES ('$date', '$weather', '$state')";
            $result = mysqli_query($cn, $query);

            if (!$result) {
                die('Query Failed: ' . mysqli_error($connection));
            }
        }
    }
}

header("Location: $_SERVER[HTTP_REFERER]");
exit;
?>
