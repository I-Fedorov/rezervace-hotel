
<?php
session_start();
date_default_timezone_set('Europe/Prague');
require 'processing/is_admin.php';
require 'processing/db.php';
require_once 'processing/functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    setcookie('edit_id', $id, time() + 3600, '/');
} else {
    $id = $_COOKIE['edit_id'];
}


$stmt = $db->prepare("SELECT reservations.id,reservations.room, reservations.date_from,
    reservations.date_to, rooms.number, rooms.type, rooms.price, users.name, users.email
    FROM reservations
LEFT JOIN rooms ON reservations.room=rooms.number
LEFT JOIN users ON reservations.user_id=users.id
        WHERE reservations.id=? ");

$stmt->execute(array($id));
$reserv = $stmt->fetch(PDO::FETCH_ASSOC);

//var_dump($reserv);

$input_from = $reserv["date_from"];
$input_to = $reserv["date_to"];
//начальные даты которые потом меняются ( для добавления текущей комнаты в случае если новые даты позволяют)
$initial_from = $reserv["date_from"];
$initial_to = $reserv["date_to"];
$initial_number = $reserv["number"];
$initial_type = $reserv["type"];
//}
//после выбора новой даты находит свободные комнаты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_date'])) {

    require 'processing/find_avail_rooms.php';

// if right date choosen to>from
    if ($response['status'] == 'ok') {
        $input_from = $from;
        $input_to = $to;
        setcookie('edit_date_from', $from, time() + 3600, '/');
        setcookie('edit_date_to', $to, time() + 3600, '/');
    }
}
// после выбора номера комнаты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_room'])) {
    $input_room = $_POST['room'];
    $input_from = $_COOKIE['edit_date_from'];
    $input_to = $_COOKIE['edit_date_to'];

    $stmt = $db->prepare(" UPDATE reservations
            SET date_from = ?, date_to = ? ,  room=?
            WHERE id = ?;");
    $query = $stmt->execute(array($input_from, $input_to, $input_room, $id));

    if ($query) {
        $response['status'] = "edited";
        header('Location: admin.php');
    } else {
        $response['status'] = "Something was wrong";
    }
}
?>



<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text / html;
              charset = UTF-8">
        <title>Edit reservation</title>
        <?php include 'includes/links.php' ?>
    </head>
    <body>
        <div class="container">
            <?php include 'includes/header.php' ?>
            <h2> Edit reservation</h2>
            <div class="edit-field-wrap ">

                <form class="edit_form" method="POST" action='admin_edit_reservation.php?id=<?= $id ?>'>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" > Reservation ID:</span>
                        <input class="form-control"  type="text" name="id" readonly value="<?= $reserv['id'] ?>">
                    </div>
                    <div class=" edit-field input-group err_msg"><?php
                        if (isset($response) && $response['status'] != 'ok') {

                            echo "" . $response['status'] . "";
                        }
                        ?></div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" > User name::</span>
                        <input class="form-control"  type="text" name="id" readonly value="<?= $reserv['name'] ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" > User email:</span>
                        <input class="form-control"  type="text" name="id" readonly value="<?= $reserv['email'] ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" >  From:</span>
                        <input class="form-control"  type="date" name="from" value="<?= $input_from ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" >  To:</span>
                        <input class="form-control"  type="date" name="to" value="<?= $input_to ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" > Room number:</span>
                        <input class="form-control"  type="text" name="id" readonly value="<?= $reserv['number'] ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" >  Room type:</span>
                        <input class="form-control"  type="text" name="id" readonly value="<?= $reserv['type'] ?>">
                    </div>
                    <input id='edit-date' class=" btn btn-success" name="submit_date" type="submit" value="Choose new date"/>
                    <div> Than choose from availble rooms</div>
                </form>

                <hr>

                <form class="" method="POST" action='admin_edit_reservation.php?id=<?= $id ?>'>
                    <?php
                    if (isset($_POST['submit_date']) && ($response['status'] == 'ok')) {
                        echo ' <select  class=" select-room form-control" name="room">';
                        // условие чтобы добавлялась текущая комната в выбор если дата подходит
                        if ($input_from >= $initial_from && $input_to <= $initial_to) {
                            echo"<option  value='$initial_number' ?>room #$initial_number  -  $initial_type</option>";
                        }
                        // проход двумерного массива $all_rooms чтоьбы достать тип свободных комнат
                        foreach ($all_rooms as $value) {
                            foreach ($avail_rooms_num as $num) {
                                if ($num === $value["number"]) {
                                    $number = $value["number"];
                                    $type = $value["type"];
                                    echo"<option  value='$number' >room #$number  -  $type</option>";
                                }
                            }
                        }
                        echo '  </select>';
                        echo'<div><input id="edit-room" class="btn btn-success" name="submit_room" type="submit" value="Save changes"/></div>';
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>
