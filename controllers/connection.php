<?php

//mysqli('hostname','dbUSername','dbPassword','dbName');
// cn connection
$cn = mysqli_connect('localhost','root','','travel_planner');

//check connection
//if connection failed, it will show this msg
if(mysqli_connect_errno()){
    echo "Failed to connect MySQL: ". mysqli_connect_error();
    die();
};

?>

