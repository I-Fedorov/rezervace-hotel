
<?php
require 'processing/db.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Hotel reservation</title>
        <?php include 'includes/links.php' ?>


    </head>

    <body>
        <div class=" container">

            <?php include 'includes/header.php' ?>

            <div class="booking">
                <h2> Booking</h2>
                <h3> Choose date</h3>

                <div class="center-block input-group">
                    <form class="booking_form ">

                        <div>
                            From:
                        </div>
                        <div>
                            <input id="from" type="date" >
                        </div>
                        <div>
                            To:
                        </div>
                        <div>
                            <input id="to" type="date" >
                        </div>
                        <div>
                            <input id="show" class="btn btn-success" type="button" value="Show results">
                        </div>
                    </form>
                </div>
            </div>

            <section class="rooms">
                <h2> <span>Our rooms</span></h2>
                <hr>
                <!--                standart room-->
                <div class="room row">
                    <div class="col-md-4 room_img ">
                        <div class="thumbnail">
                            <img  src=" img/standart.jpg" alt="standart room" />
                        </div>
                    </div>

                    <div class="col-md-5 room_discr ">
                        <h3> Standart</h3>
                        <div class="items">
                            <p> Room <b>Standart</b> will give you comfort that is provided by elegant furniture and necessary equipment. Interior items:
                                distinguished lamps, coffee table, cosy armchairs combined with beautiful curtains and cable tv that can satisfy needs of the most
                                demanding guests. Parking place, breakfast and dinner are included in price.
                            </p>
                            <ul>
                                <li>  Size 25 sq.m</li>
                                <li>  Max. 2 guests</li>
                            </ul>
                        </div>
                    </div>
                    <div class= "col-md-3 results">
                        <div class="standart_price"></div>
                        <div class="date"></div>
                        <div class="standart_avail"></div>
                        <input class="reserve_standart btn btn-info" type="button" value="Reserve">
                    </div>
                </div>
                <hr>
                <!--                luxe room-->
                <div class="room row">
                    <div class="col-md-4 room_img ">
                        <div class="thumbnail">
                            <img  src=" img/luxe.jpg" alt="standart room" />
                        </div>
                    </div>

                    <div class="col-md-5 room_discr ">
                        <h3> Luxe</h3>
                        <div class="items">
                            <p > Room <b>Luxe</b> is harmonious combination of design and interior considered to the detail. Separated work , rest and guest zones
                                guarantee you total relax as well as possibility to focus on your work. Soft and convenient furniture and modern equipment will help you to
                                spend time with comfort. Parking place, breakfast and dinner are included in price. </p>
                            <ul>
                                <li>  Size 35 sq.m</li>
                                <li>  Max. 2 guests</li>
                            </ul>
                        </div>
                    </div>
                    <div class= "col-md-3 results">
                        <div class="luxe_price"></div>
                        <div class="date"></div>
                        <div class="luxe_avail"></div>
                        <input class="reserve_luxe btn btn-info" type="button" value="Reserve">
                    </div>
                </div>
                <hr>
                <!--                family room-->
                <div class="room row">
                    <div class="col-md-4 room_img ">
                        <div class="thumbnail">
                            <img  src=" img/family.jpg" alt="standart room" />
                        </div>
                    </div>

                    <div class="col-md-5 room_discr ">
                        <h3> Family</h3>
                        <div class="items">
                            <p > Room <b>Family</b>  is ideal variant for spending time over a continuous period. Double room number consists of bedroom, living room
                                and mini kitchen. You can work there or just relax in the atmosphere of home coziness. Perfect style and esthetics of this number give you the
                                feeling of absolute peace and serenity. Parking place, breakfast and dinner are included in price. </p>
                            <ul>
                                <li>  Size 65 sq.m</li>
                                <li>  Max. 3 guests</li>
                            </ul>
                        </div>
                    </div>
                    <div class= "col-md-3 results">
                        <div class="family_price"></div>
                        <div class="date"></div>
                        <div class="family_avail"></div>
                        <input class="reserve_family btn btn-info" type="button" value="Reserve">
                    </div>
                </div>
                <hr>

            </section>

        </div>

    </body>
</html>
