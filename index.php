<?php include 'header.php' ?>

<div class="content">

  <div class="row jumbotron">
    <div class="col-md-6" id="welcome">
      <center><h3>Welcome!</h3></center>
	  Welcome to Step Wager! <br><br> Step wager is place where you can challenge friends and strangers to step battles!
    </div>
    <div class="col-md-6">
      <div class="content login">
        <h4>Please login or <a href="register.php">sign up</a></h4>
        <?php include 'loginform.php' ?>
      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-sm-6 features">
	  <div class="col-xs-4 featureimg">
	    <img src="images/icons/converse.svg" alt="">
	  </div>
	  <div class="col-xs-8">
        <b>Motivation</b><br>
	    Motivated to walk.
	  </div>
	</div>
	<div class="col-sm-6 features">
	  <div class="col-xs-4 featureimg">
	    <img src="images/icons/medal.svg" alt="">
	  </div>
	  <div class="col-xs-8">
        <b>Rankings</b><br>
	    Leaderboard.
	  </div>
	</div>
  </div>
  <div class="row">
    <div class="col-sm-6 features">
	  <div class="col-xs-4 featureimg">
	    <img src="images/icons/money.svg" alt="">
	  </div>
	  <div class="col-xs-8">
        <b>Money. Money. Money.</b><br>
	    Wagers.
	  </div>
	</div>
	<div class="col-sm-6 features">
	  <div class="col-xs-4 featureimg">
	    <img src="images/icons/chat.svg" alt="">
	  </div>
	  <div class="col-xs-8">
        <b>Bragging Rights</b><br>
	    Tell friends. Bragging rights.
	  </div>
	</div>
  </div>

  Homepage goes here. There will be 2 versions of this page depending on whether or not the user is logged in.<br><br>
  
  When logged out: List the purpose and features of this site. Show a login/register button.<br><br>
  
  When logged in, show a dashboard with an overview of their wagers and ranking.

</div>

<script>
  $(':checkbox').checkbox();
</script>

<?php include 'footer.php' ?>