<?php include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

<div class="content">
  <h4>Account</h4>

    <div class="center-block">
        <dl class="dl-horizontal">
            <?php
            echo '<dt>Username</dt><dd>', $_SESSION['user']['username'], '</dd>';
            echo '<dt>Email</dt><dd>', $_SESSION['user']['email'], '</dd>';
            echo '<dt>Date Registered</dt><dd>', $_SESSION['user']['date_registered'], '</dd>';
            echo '<dt>Fitbit ID</dt><dd>', $_SESSION['user']['fitbit_id'], '</dd>';
            echo '<dt>Profile Image URL</dt><dd>', $_SESSION['user']['image'], '</dd>';
            ?>
        </dl>
    </div>

</div>

<?php include 'footer.php' ?>