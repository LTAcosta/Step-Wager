<?php
include_once('../../private/stepwager_config.php');

$query = 'UPDATE users SET session_id = NULL WHERE id = ' . $_SESSION['user']['id'] . ' LIMIT 1';
mysqli_query($dbLink, $query);
unset($_SESSION['user']);
header('Location: index.php');
?>