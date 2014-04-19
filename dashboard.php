<?php include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

    <div class="content">
        <h4>Dashboard</h4>

        <div class="row jumbotron">
            <div class="col-md-6" id="welcome">
                <center><h4>Welcome back <?php echo $_SESSION['user']['username']?>!</h4></center>
                <div class="center-block">
                    <dl class="dl-horizontal">
                        <?php
                        echo '<dt>Wins</dt><dd>', $_SESSION['user']['wins'], '</dd>';
                        echo '<dt>Ties</dt><dd>', $_SESSION['user']['ties'], '</dd>';
                        echo '<dt>Losses</dt><dd>', $_SESSION['user']['losses'], '</dd>';
                        ?>
                    </dl>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content login wager-group">
                    <ul class="nav nav-list">
                        <li class="nav-header">Current Wagers</li>
                    </ul>
                    <ul class="nav nav-list wager-list">
                        <?php
                        $query  = 'SELECT * FROM wagers WHERE (creator_id = "'.$_SESSION['user']['fitbit_id'].'" OR opponent_id = "'.$_SESSION['user']['fitbit_id'].'")'
                            .'AND finished = 0 AND accepted = 1 AND DATEDIFF(end_date, CURDATE()) >= 0 ORDER BY end_date ASC';
                        $result = mysqli_query($dbLink, $query);

                        if($result && mysqli_num_rows($result) > 0){
                            while ($wager = mysqli_fetch_array($result)){
                                $notStarted = date_diff(date_create($wager['start_date']), date_create(), false)->days >= 1;
                                echo '<li><a href="wager.php?w=', $wager['wager_id'], '"';
                                if (!$notStarted)
                                    echo ' class="text-warning"';
                                echo '>', $wager['name'];
                                if (!$notStarted)
                                    echo ' <span class="fui-time wager-ico"></span>';
                                echo '</a></li>';
                            }

                        } else {
                            echo '<div class="no-wagers"><small>No current wagers.<br>Start a new one below!</small></div>';
                        }
                        ?>
                    </ul>
                        <a class="btn btn-lg btn-block btn-danger <?php if(!$isFitbitSetup){echo 'disabled';}?>" href="<?php if(!$isFitbitSetup){echo '#';}else{echo 'new.php';}?>">
                            Start a New Wager!
                        </a>
                </div>
            </div>
        </div>

    </div>

<?php include 'footer.php' ?>