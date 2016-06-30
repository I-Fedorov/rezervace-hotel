<?php

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] == 0) {
    echo 'Access Denied';
    die;
}


