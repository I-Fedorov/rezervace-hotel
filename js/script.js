$(document).ready(function () {

/// index.php

// Set min date for from and to
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    today = yyyy + '-' + mm + '-' + dd;

    var tommorow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    var ddt = tommorow.getDate();
    var mmt = tommorow.getMonth() + 1;
    var yyyyt = tommorow.getFullYear();

    if (ddt < 10) {
        ddt = '0' + ddt
    }
    if (mmt < 10) {
        mmt = '0' + mmt
    }
    tommorow = yyyyt + '-' + mmt + '-' + ddt;
    console.log(today);
    console.log(tommorow);

    $('#from').attr('min', today);
    $('#to').attr('min', tommorow);


// alert  - wrong date
    function jAlert_wrong_date() {
        $.jAlert({
            'title': '<div class="center"> Warning</div>',
            'content': '<div class="center">Date is incorrect </div>',
            'closeOnClick': true
        });
    }

//gets via ajax info about free rooms and show results
    $('#show').on("click", function () {
        var $date = {
            from: $('#from').val(),
            to: $('#to').val()
        };

        $.ajax({
            type: "POST",
            url: "processing/date_process.php",
            data: $date,
            success: function (response) {
                var response = jQuery.parseJSON(response);
                var status = response.status;
                console.log(status);
                if (status === "wrong date") {
                    console.log("true");
                    jAlert_wrong_date();
                } else {
                    console.log(response);
                    show_result(response);
                }

            }
        });
    });

    function show_result(resp) {
        $(".date").text("From: " + resp.from + " To: " + resp.to + "");
        $(".standart_price").text("Price: " + resp.price_standart + " $ per day");
        $(".standart_avail").text("available: " + resp.avail_standart + " rooms");
        $(".luxe_price").text("Price: " + resp.price_luxe + "$ per day");
        $(".luxe_avail").text("available: " + resp.avail_luxe + " rooms");
        $(".family_price").text("Price: " + resp.price_family + "$ per day");
        $(".family_avail").text("available: " + resp.avail_family + " rooms");
        $(".room input[value='Reserve']").css({"opacity": "1"});
    }


// check if user is registred for resirvation
    function reserve_type(type) {
        console.log("pressed");
        $.ajax({
            type: "POST",
            url: "processing/is_registred.php",
            data: "request",
            success: function (response) {

                if (response == "no") {
                    $.jAlert({
                        'title': '<div class="center"> Warning</div>  ',
                        'content': '<div class="center"> For reservation please sign in</div> <br /> \n\
<div  jalert-btn class=" jalert-btn center"><a class="btn btn-primary" href="signin.php">Sign In</a></div>',
                    });
                } else {
                    window.location.href = "reservation.php?type=" + type.data.name + "";
                }
            }
        });
    }

//передает параметры name: "standart" в функцию берет type.data.name
    $('section').on("click", '.reserve_standart', {name: "standart"}, reserve_type);
    $('section').on("click", '.reserve_luxe', {name: "luxe"}, reserve_type);
    $('section').on("click", '.reserve_family', {name: "family"}, reserve_type);

//end index.php


///start reservation.php
    $('.details').on("click", '#confirm', function () {

        $.ajax({
            type: "POST",
            url: "processing/create_reservation.php",
            data: "request",
            success: function (response) {
                $.trim(response);
                console.log(response);
                //??????  response лишняя пустая строка в POST response поэтому не равно
                console.log(response.search('saved')); // возвращает позицию совпадения или -1 в случае несовпадения

//                if (response == "saved") {
                if (response.search('saved') !== -1) {

                    $.jAlert({
                        'title': '<div class="center">Reservation was created</div>',
                        'content': '<div class=" jalert-btn center"><a class="btn btn-primary" href="index.php">Go to main</a></div>',
                        'closeOnClick': false,
                        'closeOnEsc': false,
                        'closeBtn': false
                    });
                } else {
                    console.log(response);
                    $.jAlert({
                        'title': '<div class="center">Smth is wrong</div>',
                        'content': '<div class="center"> Reservation is not saved </div> \n\
                                 <div class=" jalert-btn center"><a class="btn btn-primary" href="index.php">Go to main</a></div>',
                        'closeOnClick': false
                    });
                }
            }
        });
    });

    ///end reservation.php


//start admin.php
    function find_selected() {
        id_onDel = [];
        $.each($('.for-delete'), function () {
            if (this.checked) {
                var value = $(this).attr("value");
                id_onDel.push(value);
            }
        })
    }


    $("#delete-btn").on("click", function () {
        find_selected();

        var post_data = {
            id: id_onDel
        }

        console.log(post_data);
        $.ajax({
            type: "POST",
            url: "processing/admin_delete_reservation.php",
            data: post_data,
        });
        location.href = 'admin.php';
    });



    $("#edit-btn").on("click", function () {
        find_selected();

        if (id_onDel.length != 1) {
            $.jAlert({
                'title': '<div class="center">Warning</div>',
                'content': '<div class="center"> Choose 1 reservation for editing</div>',
                'closeOnClick': true
            });
            console.log("non 1");


        } else {
            console.log("1");
            console.log(id_onDel[0]);
            var id_onedit = id_onDel[0];
            location.href = 'admin_edit_reservation.php?id=' + id_onedit + '';
        }

//        console.log(post_data);
    });

    //end admin.php


})