<?php
$isFitbitRequired = true;
include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

<div class="content">
  Wagers page goes here. This will be where a logged in user can start a new wager, view their wager invites, view their current wagers, or view their past wagers.

</div>

<?php include 'footer.php' ?>