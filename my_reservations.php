
<?php
session_start();
date_default_timezone_set('Europe/Prague');
require 'processing/db.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['offset'])) {
    $offset = (int) $_GET['offset'];
} else {
    $offset = 0;
}

$count = $db->query("SELECT COUNT(id) FROM reservations WHERE (`user_id`=" . $user_id . ") ");
$count_reserv = $count->fetchColumn();


$stmt = $db->prepare("SELECT * FROM rooms
LEFT JOIN reservations ON reservations.room=rooms.number WHERE (`user_id`=?)
ORDER BY `date_from` DESC LIMIT 10 OFFSET ? ");

$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchALL(PDO::FETCH_ASSOC);
//var_dump($reservations);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>My reservations</title>
        <?php include 'includes/links.php' ?>
    </head>
    <body>
        <div class="container">
            <?php include 'includes/header.php' ?>
            <div class="reservations">
                <h2> My reservations</h2>
                <table class=" table table-hover table-striped table-bordered">
                    <tr>
                        <th>Room number</th>
                        <th>Room type</th>
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
                            <td><?= $row['number'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $from_dmY ?></td>
                            <td><?= $to_dmY ?></td>
                            <td><?= $interval_days_str ?></td>
                            <td><?= $cost ?> $</td>
                        </tr>
                    <?php } ?>
                </table>
                <br/>
                <div class="pagination">
                    <?php for ($i = 1; $i <= ceil($count_reserv / 10); $i++) { ?>
                        <!--ceil округление в большую сторону      expression ? true_value : false_value-->
                        <a class="<?= $offset / 10 + 1 == $i ? "active" : "" ?>" href="my_reservations.php?offset=<?= ($i - 1) * 10 ?>"><?= $i ?></a>

                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
