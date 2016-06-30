
<?php

require 'db.php';
require_once 'functions.php';
//require_once (dirname(__FILE__)) . '/functions.php';

$from = $_POST['from'];
$from_dmY = date("d.m.Y", strtotime($from));
$from = date("Y-m-d", strtotime($from));
$to = $_POST['to'];
$to_dmY = date("d.m.Y", strtotime($to));
$to = date("Y-m-d", strtotime($to));


$date_from = new DateTime($from);
$date_to = new DateTime($to);
$interval_days = date_diff($date_from, $date_to);
$interval_days_str = $interval_days->format('%R%a');
$interval_days_float = floatval($interval_days_str);

$response = [];
if ($interval_days_float < 1) {
    $response = [
        'status' => "wrong date",
    ];
} else {


    setcookie("from", $from, time() + 3600, '/');
    setcookie("to", $to, time() + 3600, '/');



    $reserved_rooms = find_reserved_rooms($from, $to);
//    var_dump($reserved_rooms);

    $all_rooms = find_all_rooms();

//    var_dump($all_rooms);



    $reserved_room_num = array();
    $all_room_num = array();

    foreach ($all_rooms as $value) {
        array_push($all_room_num, $value["number"]);
    }
//    var_dump($all_room_num);

    foreach ($reserved_rooms as $value) {
        array_push($reserved_room_num, $value["number"]);
    }

//    var_dump($reserved_room_num);

    $avail_rooms_num = array_diff($all_room_num, $reserved_room_num);


    // make array of available room types
    $avail_room_type = array();
    foreach ($all_rooms as $value) {
        foreach ($avail_rooms_num as $num) {
            if ($num === $value["number"]) {
                array_push($avail_room_type, $value["type"]);
            }
        }
    }

// count availale each type
    $avail_standart = 0;
    $avail_luxe = 0;
    $avail_family = 0;
    foreach ($avail_room_type as $value) {
        if ($value === "standart") {
            $avail_standart++;
        }
        if ($value === "luxe") {
            $avail_luxe++;
        }
        if ($value === "family") {
            $avail_family++;
        }
    }




    setcookie("avail_family", $avail_family, time() + 3600, '/');
    setcookie("avail_luxe", $avail_luxe, time() + 3600, '/');
    setcookie("avail_standart", $avail_standart, time() + 3600, '/');



    $type_array = array("standart", "luxe", "family");

    foreach ($type_array as $value) {
        $stmt = $db->prepare("SELECT * FROM rooms WHERE type=?");
        $stmt->execute(array($value));
        $avail_rooms = $stmt->fetchALL();
        ${"price_$value"} = $avail_rooms[0]["price"];
        setcookie("price_" . "$value", ${"price_$value"}, time() + 3600, '/');
    }


    $response = [
        'status' => "ok",
        'from' => "$from_dmY",
        'to' => "$to_dmY",
        'avail_standart' => "$avail_standart",
        'avail_luxe' => "$avail_luxe",
        'avail_family' => "$avail_family",
        'price_standart' => "$price_standart",
        'price_luxe' => "$price_luxe",
        'price_family' => "$price_family",
    ];
}
?>



