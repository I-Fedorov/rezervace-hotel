
<?php
session_start();
date_default_timezone_set('Europe/Prague');
require 'processing/is_admin.php';
require 'processing/db.php';
require_once 'processing/functions.php';

$all_users = find_all_users();
$selected = "";
$email_selected = '';

//var_dump($all_users);
//после выбора даты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_date'])) {
    $email_selected = "";
    $from = $_POST['from'];
    $to = $_POST['to'];
    $email = $_POST['user_email'];

    //add to input fields
    $input_from = $from;
    $input_to = $to;
    $email_selected = $email;

    foreach ($all_users as $value) {
        if ($value['email'] == $email) {
            $id = $value['id'];
        }
    }
    require 'processing/find_avail_rooms.php';

// if right date choosen to>from
    if ($response['status'] == 'ok') {
        setcookie('add_date_from', $from, time() + 3600, '/');
        setcookie('add_date_to', $to, time() + 3600, '/');
        setcookie('add_id', $id, time() + 3600, '/');
    }
}

// после выбора номера комнаты
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_reservation'])) {
    $add_room = $_POST['room'];
    $add_from = $_COOKIE['add_date_from'];
    $add_to = $_COOKIE['add_date_to'];
    $add_id = $_COOKIE['add_id'];
    create_reservation($add_room, $add_from, $add_to, $add_id);
}
?>



<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text / html;
              charset = UTF-8">

        <title>Add reservation</title>
        <?php include 'includes/links.php' ?>
    </head>
    <body>
        <div class="container">
            <?php include 'includes/header.php' ?>
            <h2> Add reservation</h2>
            <div class="edit-field-wrap ">

                <form class="edit_form" method="POST" action='admin_add_reservation.php'>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" > User Email:</span>
                        <?php
                        echo '<select  class=" select-room form-control" name="user_email">';
                        // чтобы в select оставался выбранный email
                        foreach ($all_users as $value) {
                            $email = $value['email'];
                            if ($email == $email_selected) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                            echo"<option  value='$email' $selected>$email</option>";
                        }
                        echo '  </select>';
                        ?>
                    </div>
                    <div class=" edit-field input-group err_msg"><?php
                        if (isset($response) && $response['status'] != 'ok') {

                            echo "" . $response['status'] . "";
                        }
                        ?></div>

                    <div class="edit-field input-group">
                        <span class="input-group-addon" >  From:</span>
                        <input class="form-control"  type="date" name="from" value="<?= isset($input_from) ? $input_from : '' ?>">
                    </div>
                    <div class="edit-field input-group">
                        <span class="input-group-addon" >  To:</span>
                        <input class="form-control"  type="date" name="to" value="<?= isset($input_to) ? $input_to : '' ?>">
                    </div>

                    <input id='add-date' class=" btn btn-success" name="add_date" type="submit" value="Choose date"/>
                </form>

                <hr>

                <form class="" method="POST" action=''>

                    <?php
                    if (isset($_POST['add_date']) && ($response['status'] == 'ok')) {
                        echo ' <div>  Choose from availble rooms</div> '
                        . '<select  class=" select-room form-control" name="room">';
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
                        echo'<div><input id="add-reservation" class="btn btn-success" name="add_reservation" type="submit" value="Save reservation"/></div>';
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>
