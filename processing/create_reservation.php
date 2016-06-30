<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    date_default_timezone_set('Europe/Prague');
    require_once 'functions.php';
    require 'db.php';
    $user_id = $_SESSION['user_id'];

    $from = $_COOKIE['from'];
    $from = date("Y-m-d", strtotime($from));
    $to = $_COOKIE['to'];
    $to = date("Y-m-d", strtotime($to));
    $type = $_COOKIE['type'];

//$user_id = "1";
//$from = "2016-05-11";
//$to = "2016-05-15";
//$type = "luxe";



    $stmt = $db->prepare("SELECT number,type FROM rooms LEFT
        JOIN reservations ON reservations.room=rooms.number
        WHERE (type=?) AND
        ((?>=`date_from` and ?<`date_to`)
        OR (?<`date_from` and ?>`date_from` ))");


    $stmt->execute(array($type, $from, $from, $from, $to));
    $reserved_rooms_type = $stmt->fetchALL(PDO::FETCH_ASSOC);
//    echo "reserved_type";
//    var_dump($reserved_rooms_type);

    $stmt = $db->prepare("SELECT number,type FROM rooms
        WHERE (type=?)");
    $stmt->execute(array($type));
    $all_rooms_type = $stmt->fetchALL(PDO::FETCH_ASSOC);

//    echo "all_type";
//    var_dump($all_rooms_type);

    $reserved_room_type_num = array();
    $all_room_type_num = array();

    foreach ($all_rooms_type as $value) {
        array_push($all_room_type_num, $value["number"]);
    }
    foreach ($reserved_rooms_type as $value) {
        array_push($reserved_room_type_num, $value["number"]);
    }
    $avail_rooms_type_num = array_diff($all_room_type_num, $reserved_room_type_num);
    $avail_room_type = array();

//    echo "free number of_type";
//    var_dump($avail_rooms_type_num);

    $rand_key = array_rand($avail_rooms_type_num, 1);
    $reserv_room = $avail_rooms_type_num[$rand_key];

//    echo "$reserv_room";
//    $stmt = $db->prepare("INSERT INTO reservations(room, date_from, date_to,user_id) VALUES (?, ?, ?,?)");
//    $query = $stmt->execute(array($reserv_room, $from, $to, $user_id));
//
//    if ($query) {
//        echo "saved";
//        die();
//    }
    $isSaved = create_reservation($reserv_room, $from, $to, $user_id);
}
?>

<?php echo $isSaved; ?>
