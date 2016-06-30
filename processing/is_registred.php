<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';

    session_start();

    if (!isset($_SESSION["user_id"])) {
        echo "no";
        die();
    }
    echo "yes";
}
?>
