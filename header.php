<?php include_once('../../private/stepwager_config.php'); ?>

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
		  <h1><a href="index.php">Step Wager</a></h1>
		</div>
	    <div class="col-md-7">
          <ul id="nav" class="nav nav-pills nav-justified">
            <li><a href="index.php">Home</a></li>
            <?php
            if($isUserLoggedIn){
                print '<li><a href="wagers.php">Wagers';

                $query  = 'SELECT * FROM wagers WHERE opponent_id = "'.$_SESSION['user']['fitbit_id'].'"'
                         .' AND finished = 0 AND accepted = 0 AND DATEDIFF(start_date, CURDATE()) >= 1';

                $result = mysqli_query($dbLink, $query);
                if($result && mysqli_num_rows($result) > 0){
                    echo '<span class="navbar-new">', mysqli_num_rows($result), '</span>';
                }

                print '</a></li>';
            }
            ?>
            <li><a href="leaderboard.php">Leaderboards</a></li>
            <?php
            if($isUserLoggedIn){
                print '<li class="dropdown">';
                print '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$_SESSION['user']['username'].' <span class="caret"></span></a>';
                print '<i class="dropdown-arrow"></i>';
                print '<ul class="dropdown-menu">';
                print '<li><a href="profile.php">Profile</a></li>';
                print '<li><a href="account.php">Account</a></li>';
                print '<li class="divider"></li>';
                print '<li><a href="logout.php">Logout</a></li>';
                print '</ul></li>';
            } else {
                if(strpos(basename($_SERVER['PHP_SELF']), 'register') !== false){
                    print '<li><a href="index.php">Login</a></li>';
                } else {
                    print '<li><a href="register.php">Sign Up</a></li>';
                }
            }
            ?>
          </ul>
		</div>
      </div>
      <?php
      if($isUserLoggedIn && (!$isFitbitSetup || $isDuplicateFitbit || ($isFitbitRequired && !$isFitbitConnected))){
          print '<div class="dialog dialog-danger">';
          if($isDuplicateFitbit){
              print 'It appears there\'s already a Step Wager account linked to that Fitbit account. Please <a href="index.php?connect=true">try a different Fitbit account!</a>';
          } else {
              print 'Before you can make a wager, you need to <a href="index.php?connect=true">link Step Wager to Fitbit!</a>';
          }
          print '</div>';
      }
      ?>
