<?php
//$isFitbitRequired = true;
include 'header.php';
include_once 'functions.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}
?>

<div class="content">
  <h4>Wagers</h4>
  <div class="description">
      This is where all the action happen! Challenge your friends and track your progress as you walk yourself to fame and glory!
  </div>

    <div id="wagers" class="row jumbotron">
        <div class="col-md-4">
            <div class="content login wager-group">
                <ul class="nav nav-list">
                    <li class="nav-header">Pending Wagers</li>
                </ul>
                <ul class="nav nav-list wager-list">
                    <?php
                    $query  = 'SELECT * FROM wagers WHERE (creator_id = "'.$_SESSION['user']['fitbit_id'].'" OR opponent_id = "'.$_SESSION['user']['fitbit_id'].'")'
                             .'AND finished = 0 AND accepted = 0 AND DATEDIFF(start_date, CURDATE()) >= 1 ORDER BY start_date ASC';
                    $result = mysqli_query($dbLink, $query);

                    if($result && mysqli_num_rows($result) > 0){
                        while ($wager = mysqli_fetch_array($result)){
                            echo '<li><a href="wager.php?w=', $wager['wager_id'], '"';

                            if ($wager['creator_id'] == $_SESSION['user']['fitbit_id'])
                                echo ' class="text-warning"';
                            else if ($wager['opponent_id'] == $_SESSION['user']['fitbit_id'])
                                echo ' class="text-info"';

                            echo '>', $wager['name'];

                            if ($wager['creator_id'] == $_SESSION['user']['fitbit_id'])
                                echo ' <span class="fui-time wager-ico"></span>';
                            else if ($wager['opponent_id'] == $_SESSION['user']['fitbit_id'])
                                echo ' <span class="fui-info wager-ico"></span>';

                            echo '</a></li>';
                        }

                    } else {
                        echo '<div class="no-wagers"><small>No pending wagers.<br>Start a new one below!</small></div>';
                    }
                    ?>
                    <!--
                    <li><a class="text-info" href="wager.php">Wager 1 <span class="fui-info wager-ico"></span></a></li>
                    <li><a href="#fakelink">Wager 2</a></li> -->
                </ul>
            </div>
        </div>
        <div class="col-md-4">
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
            </div>
        </div>
        <div class="col-md-4">
            <div class="content login wager-group bottom-group">
                <ul class="nav nav-list">
                    <li class="nav-header">Completed Wagers</li>
                </ul>
                <ul class="nav nav-list wager-list">
                    <?php
                    $query  = 'SELECT * FROM wagers WHERE (creator_id = "'.$_SESSION['user']['fitbit_id'].'" OR opponent_id = "'.$_SESSION['user']['fitbit_id'].'")'
                        .'AND (finished = 1 OR DATEDIFF(end_date, CURDATE()) < 0 OR (accepted = 0 AND DATEDIFF(start_date, CURDATE()) <= 0)) ORDER BY end_date DESC';
                    $result = mysqli_query($dbLink, $query);

                    if($result && mysqli_num_rows($result) > 0){
                        while ($wager = mysqli_fetch_array($result)){
                            $notAccepted = $wager['accepted'] == 0;
                            $pending = !$notAccepted && $wager['winner'] == null;
                            $isWinner = $wager['winner'] == $_SESSION['user']['fitbit_id'] || $wager['winner'] == 'both';
                            $loser = !$isWinner && $wager['winner'] != null;

                            if ($pending){
                                // Attempt to calculate a winner.
                                $creator_factory = Get_Fitbit_Ids_Factory($wager['creator_id']);
                                $opponent_factory = Get_Fitbit_Ids_Factory($wager['opponent_id']);

                                if ($creator_factory != null && $opponent_factory != null){
                                    $fragment = 'activities/steps/date';
                                    $creator_gateway = $creator_factory->getActivityTimeSeriesGateway();
                                    $opponent_gateway = $opponent_factory->getActivityTimeSeriesGateway();

                                    $creator_series = $creator_gateway->get($fragment, true, $wager['start_date'], null, $wager['end_date']);
                                    $opponent_series = $opponent_gateway->get($fragment, true, $wager['start_date'], null, $wager['end_date']);

                                    $creator_series = (Array)$creator_series;
                                    $opponent_series = (Array)$opponent_series;

                                    if (count($creator_series["activities-tracker-steps"]) != 0 || count($opponent_series["activities-tracker-steps"]) != 0){
                                        $creator_steps = 0;
                                        $opponent_steps = 0;
                                        $winner = 'none';

                                        foreach ($creator_series["activities-tracker-steps"] as $date){
                                            $creator_steps += $date->value;
                                        }

                                        foreach ($opponent_series["activities-tracker-steps"] as $date){
                                            $opponent_steps += $date->value;
                                        }

                                        if ($wager['wager_type'] == 'most') {
                                            if ($creator_steps > $opponent_steps) {
                                                $winner = $wager['creator_id'];
                                            } else if ($creator_steps < $opponent_steps) {
                                                $winner = $wager['opponent_id'];
                                            } else {
                                                $winner = 'both';
                                            }

                                        } else if ($wager['wager_type'] == 'min') {
                                            $step_goal = intval($wager['step_goal']);
                                            if ($creator_steps > $step_goal && $creator_steps > $step_goal) {
                                                $winner = 'both';
                                            } else if ($creator_steps > $step_goal) {
                                                $winner = $wager['creator_id'];
                                            } else if ($opponent_steps > $step_goal) {
                                                $winner = $wager['opponent_id'];
                                            } else {
                                                $winner = 'none';
                                            }
                                        }

                                        // Update the wager
                                        $query = 'UPDATE wagers SET finished="1", creator_steps="'. mysqli_real_escape_string($dbLink, $creator_steps) .'",
                                                                                  opponent_steps="'. mysqli_real_escape_string($dbLink, $opponent_steps) .'",
                                                                                  winner="'. mysqli_real_escape_string($dbLink, $winner) .'" WHERE wager_id = "' . mysqli_real_escape_string($dbLink, $wager['wager_id']) . '"';
                                        mysqli_query($dbLink, $query);

                                        // Update status fields
                                        $pending = false;
                                        $isWinner = $winner == $_SESSION['user']['fitbit_id'] || $winner == 'both';
                                        $loser = !$isWinner;

                                        // Recalculate the wins/losses
                                        Calculate_Wins($wager['creator_id']);
                                        Calculate_Wins($wager['opponent_id']);
                                    }
                                }
                            }

                            echo '<li><a href="wager.php?w=', $wager['wager_id'], '"';

                            if ($notAccepted)
                                echo ' class="text-muted"';
                            else if ($pending)
                                echo ' class="text-warning"';
                            else if ($isWinner)
                                echo ' class="text-success"';
                            else if ($loser)
                                echo ' class="text-danger"';

                            echo '>', $wager['name'];

                            if ($pending)
                                echo ' <span class="fui-time wager-ico"></span>';
                            else if ($isWinner)
                                echo ' <span class="fui-check wager-ico"></span>';
                            else if ($loser)
                                echo ' <span class="fui-cross wager-ico"></span>';

                            echo '</a></li>';
                        }

                    } else {
                        echo '<div class="no-wagers"><small>No completed wagers.<br>Start a new one below!</small></div>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div id="new-wager">
        <a class="btn btn-hg btn-danger <?php if(!$isFitbitSetup) {echo 'disabled" href="#"';} else {echo '" href="new.php"';}?>>
            Start a New Wager!
        </a>
    </div>

</div>

<?php include 'footer.php' ?>