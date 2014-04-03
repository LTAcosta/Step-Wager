<?php
include_once('../../private/stepwager_config.php');

$query = 'UPDATE users SET session_id = NULL WHERE id = ' . $_SESSION['user']['id'] . ' LIMIT 1';
mysql_query($query);
unset($_SESSION['user']);
header('Location: index.php');
?>