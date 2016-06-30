
<?php
session_start();
date_default_timezone_set('Europe/Prague');

require 'processing/db.php';


$type = $_GET["type"];

setcookie("type", $type, time() + 3600, '/');
$from = '';
$from = $_COOKIE['from'];
$to = $_COOKIE['to'];
$avail = $_COOKIE["avail_$type"];
$price_day = $_COOKIE["price_$type"];
$user_id = $_SESSION['user_id'];


$from_DMY_str = date("d.m.Y", strtotime($from));
$to_DMY_str = date("d.m.Y", strtotime($to));
$date_from = new DateTime($from);
$date_to = new DateTime($to);

$interval_days = date_diff($date_from, $date_to);
$interval_days_str = $interval_days->format('%a');

$date_now = new DateTime();
$date_now = date_format($date_now, 'h');


$type_uppfirst = ucfirst($type);
$price_whole = $interval_days_str * $price_day;


$img = '<div class="thumbnail">
                            <img  src=" img/' . $type . '.jpg" alt="' . $type . ' room" />
                        </div>'
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Reservation</title>
        <?php include 'includes/links.php' ?>
    </head>
    <body>
        <div class="container">
            <?php include 'includes/header.php' ?>
            <section class="details">
                <h2> Details of reservation</h2>
                <!--                standart room-->
                <div class="room row">
                    <div class="col-md-6 room_img ">
                        <?php echo "$img"; ?>
                    </div>
                    <h3>  <?php echo $type_uppfirst; ?></h3>
                    <div class="col-md-6 room_discr ">
                        <div class="items">
                            <ul>
                                <li>  Size 25 sq.m</li>
                                <li>  Max. 2 guests</li>
                            </ul>
                        </div>
                        <div  class="reserv_data">
                            <div>Choosen date</div>
                            <div>From: <?php echo $from_DMY_str; ?> To: <?php echo $to_DMY_str; ?></div>
                            <div>Price for day: <?php echo $price_day; ?></div>
                            <div><?php echo "Your price for:" . $interval_days_str . " days is " . $price_whole . "$"; ?></div>
                        </div>
                        <!--<a  class="btn btn-success" href="processing/create_reservation.php" role="button" >Confirm Reservation</a>-->
                        <div class="reserv_form">
                            <input id='confirm' class="btn btn-success" type="button" value="Confirm Reservation">
                            <a  class="btn btn-info" href="index.php" role="button" >Choose other date/room</a>
                        </div>
                    </div>
            </section>
        </div>
    </body>
</html>

