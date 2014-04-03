<?php
include_once('../../private/stepwager_config.php');

if ($isUserLoggedIn){
    include 'dashboard.php';
} else {
    include 'login.php';
}

 ?>