<?php

//pripojeni do db na serveru eso.vse.cz
$db = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '775907');

//vyhazuje vyjimky v pripade neplatneho SQL vyrazu
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)
?>