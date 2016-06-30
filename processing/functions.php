<?php

//  require '../includes/functions.php';
//  require 'includes/functions.php';
//  require 'processing/functions.php';


function find_reserved_rooms($from, $to) {
    global $db;
    $stmt = $db->prepare("SELECT number,type FROM rooms
LEFT JOIN reservations ON reservations.room=rooms.number WHERE
(?>=`date_from` and ?<`date_to`)
OR  (?<`date_from` and ?>`date_from` );");

    $stmt->execute(array($from, $from, $from, $to));
    $reserved_rooms = $stmt->fetchALL(PDO::FETCH_ASSOC);
    return $reserved_rooms;
}

function find_all_rooms() {
    global $db;
    $stmt = $db->prepare("SELECT number,type FROM rooms;");
    $stmt->execute(array());
    $all_rooms = $stmt->fetchALL(PDO::FETCH_ASSOC);
    return $all_rooms;
}

function find_all_users() {
    global $db;
    $stmt = $db->prepare("SELECT email, id FROM users;");
    $stmt->execute(array());
    $all_users = $stmt->fetchALL(PDO::FETCH_ASSOC);
    return $all_users;
}

function create_reservation($reserv_room, $from, $to, $user_id) {
    global $db;
    $stmt = $db->prepare("INSERT INTO reservations(room, date_from, date_to,user_id) VALUES (?, ?, ?,?)");
    $query = $stmt->execute(array($reserv_room, $from, $to, $user_id));

    if ($query) {
        $response = 'saved';
    }
    return $response;
}
?>

