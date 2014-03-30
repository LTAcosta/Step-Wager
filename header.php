<?php include_once('../../stepwager_config.php'); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Step Wager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="css/flat-ui.css" rel="stylesheet">
    
    <link href="css/style.css" rel="stylesheet">

    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="main" class="container">
      <div class="header row">
	    <div class="col-md-5">
		  <h1>Step Wager</h1>
		</div>
	    <div class="col-md-7">
          <ul class="nav nav-pills nav-justified">
            <li><a href="index.php">Home</a></li>
            <?php if($isUserLoggedIn) print '<li><a href="wagers.php">Wagers</a></li>'; ?>
            <li><a href="leaderboard.php">Leaderboards</a></li>
            <?php
            if($isUserLoggedIn){
                print '<li class="dropdown">';
                print '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$_SESSION['user']['username'].' <span class="caret"></span></a>';
                print '<ul class="dropdown-menu">';
                print '<li><a href="account.php">Profile</a></li>';
                print '<li class="divider"></li>';
                print '<li><a href="logout.php">Logout</a></li>';
                print '</ul></li>';
            } else {
                print '<li><a href="register.php">Sign Up</a></li>';
            }
            ?>
          </ul>
		</div>
      </div>