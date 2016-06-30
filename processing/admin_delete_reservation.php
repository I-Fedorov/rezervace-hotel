
<?php

session_start();
date_default_timezone_set('Europe/Prague');
require 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $onDelete = $_POST["id"];

    foreach ($onDelete as $value) {
        $stmt = $db->prepare("DELETE  FROM reservations WHERE id=?");
        $stmt->execute(array($value));
    }
}
?>



