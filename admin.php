
<?php
session_start();
date_default_timezone_set('Europe/Prague');
require 'processing/is_admin.php';
require 'processing/db.php';
$user_id = $_SESSION['user_id'];


// for ordering
if (!isset($_COOKIE["order"]) && !isset($_COOKIE["direction"])) {
    $order = 'reservations.id';
    $direction = "DESC";
} else {
    $order = $_COOKIE["order"];
    $direction = $_COOKIE["direction"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// выбор только из возможных вариантов для избежания sql injection
    $orders = array("reservations.id", "reservations.room", "reservations.date_from", "reservations.date_to", "rooms.number", "users.name", "users.email", "rooms.type");
    $key_o = array_search($_POST['orderBy'], $orders);
    $order = $orders[$key_o];
    // direction whitelistinig
    $directions = array("DESC", "ASC");
    $key_d = array_search($_POST['direction'], $directions);
    $direction = $directions[$key_d];
    setcookie("order", $order, time() + 3600, '/');
    setcookie("direction", $direction, time() + 3600, '/');
}


if (isset($_GET['offset'])) {
    $offset = (int) $_GET['offset'];
} else {
    $offset = 0;
}

// !!!!!!!!       The  only thing you can prepare in PDO prepared statements are the field values, not the fields names
//$stmt->bindValue(1, $order, PDO::PARAM_STR);
//$stmt->bindValue(1, $offset, PDO::PARAM_STR);
//
//$stmt->execute();

@$offset_safe = mysql_real_escape_string($offset);
$stmt = $db->query("SELECT reservations.id,reservations.room, reservations.date_from,
    reservations.date_to, rooms.number, rooms.type, rooms.price, users.name, users.email
   FROM reservations
LEFT JOIN rooms ON reservations.room=rooms.number
LEFT JOIN users ON reservations.user_id=users.id
ORDER BY $order $direction LIMIT 10 OFFSET $offset_safe");

$reservations = $stmt->fetchALL(PDO::FETCH_ASSOC);
//var_dump($reservations);
$count = $db->query("SELECT COUNT(id) FROM reservations ");
$count_reserv = $count->fetchColumn();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>Admin page</title>

        <?php include 'includes/links.php' ?>


    </head>

    <body>
        <div class="container">

            <?php include 'includes/header.php' ?>

            <div class="all_reserv">
                <h2> Reservations</h2>

                <form class="order" method="POST" action="admin.php">
                    <input class="btn btn-success"type="submit" value="Order by"/>
                    <select class="ordering form-control" name="orderBy">
                        <option value="reservations.id" <?= $order == 'reservations.id' ? 'selected' : ''; ?>>Reservation_id</option>
                        <option value="users.name" <?= $order == "users.name" ? "selected" : "" ?>>Client's Name</option>
                        <option value="users.email" <?= $order == "users.email" ? "selected" : "" ?>>Client's Email</option>
                        <option value="rooms.number" <?= $order == "rooms.number" ? "selected" : "" ?>>Room number</option>
                        <option value="rooms.type" <?= $order == "rooms.type" ? "selected" : "" ?>>Room type</option>
                        <option value="reservations.date_from" <?= $order == "reservations.date_from" ? "selected" : "" ?>>Date From</option>
                        <option value="reservations.date_to"<?= $order == "reservations.date_to" ? "selected" : "" ?> >Date To</option>
                    </select>
                    <select class="ordering form-control" name="direction">
                        <option value="DESC" <?= $direction == "DESC" ? "selected" : "" ?>>Descending</option>
                        <option value="ASC" <?= $direction == "ASC" ? "selected" : "" ?>>Ascending</option>

                    </select>

                </form>

                <table class="table table-hover table-striped table-bordered">
                    <tr>
                        <th></th>
                        <th>Reservation ID</th>
                        <th>Client's Name</th>
                        <th>Client's Email</th>
                        <th>Room number</th>
                        <th>Room type</th>
                        <th>Price for a day</th>
                        <th>Date From</th>
                        <th>Date To</th>
                        <th>Number of days</th>
                        <th>Cost</th>
                    </tr>

                    <?php
                    foreach ($reservations as $row) {
                        $from_dmY = date("d.m.Y", strtotime($row['date_from']));
                        $to_dmY = date("d.m.Y", strtotime($row['date_to']));
                        $date_from = new DateTime($row['date_from']);
                        $date_to = new DateTime($row['date_to']);
                        $interval_days = date_diff($date_from, $date_to);
                        $interval_days_str = $interval_days->format('%a');
                        $cost = $interval_days_str * $row['price'];
                        ?>


                        <tr>
                            <td><input class="for-delete" type="checkbox" name="check" value="<?= $row['id'] ?>"></td>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['number'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $from_dmY ?></td>
                            <td><?= $to_dmY ?></td>
                            <td><?= $interval_days_str ?></td>
                            <td><?= $cost ?> $</td>
                        </tr>

                    <?php } ?>

                </table>
                <a id="add-btn" class="btn btn-info" href = "admin_add_reservation.php"  >Add new reservation</a>
                <input id="edit-btn" class="btn btn-success" type="submit" value="Edit selected"/>
                <input id="delete-btn" class="btn btn-danger" type="submit" value="Delete selected"/>
                <br/>

                <div class="pagination">
                    <?php for ($i = 1; $i <= ceil($count_reserv / 10); $i++) { ?>
                        <!--ceil округление в большую сторону    тернанрный оператор ( укороченная форма если) expression ? true_value : false_value-->
                        <a class="<?= $offset / 10 + 1 == $i ? "active" : "" ?>" href="admin.php?offset=<?= ($i - 1) * 10 ?>"><?= $i ?></a>

                    <?php } ?>
                </div>

            </div>

        </div>


    </body>
</html>
