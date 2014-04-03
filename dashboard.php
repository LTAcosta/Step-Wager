<?php include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

    <div class="content">
        Dashboard page goes here. <br><br>

        When logged in, this will be the home page. It will show updates and the current standings of their wagers.

    </div>

<?php include 'footer.php' ?>