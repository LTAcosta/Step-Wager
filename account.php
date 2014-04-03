<?php include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

<div class="content">
  Account page goes here. <br><br>
  
  When logged in, this will show the user's account preferences, and allow them to make changes.

</div>

<?php include 'footer.php' ?>