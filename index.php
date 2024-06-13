<?php 
    $title = "Homepage";
    function get_content(){
        require_once 'controllers/connection.php';
?>

    

<div class="container-fluid m-0 p-0 homepage d-flex justify-content-center align-items-center overflow-hidden">
    <h2 class="raleway display-1 text-white title">Travel Planner</h2>
    <h5 class="subtitle text-white raleway"><b>"Find the Best Spots, Craft the Perfect Journey.~"</b></h5>

</div>

<?php
    }
require_once 'views/templates/layout.php';
?>
