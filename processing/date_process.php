
<?php

session_start();
require 'db.php';
date_default_timezone_set('Europe/Prague');


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    require 'find_avail_rooms.php';


    echo json_encode($response);
//
}
?>



