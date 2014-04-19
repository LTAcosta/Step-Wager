<?php
include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}

if(!$isFitbitSetup){
    header('Location: wagers.php');
    echo "<script>window.location = 'wagers.php'</script>";
    exit;
}

// Clean up the input values
foreach($_REQUEST as $key => $value) {
    if(ini_get('magic_quotes_gpc'))
        $_REQUEST[$key] = stripslashes($_REQUEST[$key]);

    $_REQUEST[$key] = htmlspecialchars(strip_tags($_REQUEST[$key]));
}

// Assign the input value to a variable for easy reference
$wagerId = $_REQUEST["w"];
$accepted = isset($_POST['accepted']) && $_POST['accepted'] == 'true';

if(!$wagerId){
    header('Location: wagers.php');
    echo "<script>window.location = 'wagers.php'</script>";
    exit;
}

$wager = null;
$query = 'SELECT * FROM wagers WHERE wager_id = "' . mysqli_real_escape_string($dbLink, $wagerId) . '" LIMIT 1';
$result = mysqli_query($dbLink, $query);
if(mysqli_num_rows($result) == 1){
    $wager = mysqli_fetch_assoc($result);
}

if(!$wager ||
   ($wager['creator_id'] != $_SESSION['user']['fitbit_id'] &&
    $wager['opponent_id'] != $_SESSION['user']['fitbit_id'])){
    header('Location: wagers.php');
    echo "<script>window.location = 'wagers.php'</script>";
    exit;
}

if ($accepted && $wager && $wager['opponent_id'] == $_SESSION['user']['fitbit_id'] && $wager['accepted'] == '0') {
    $query = 'UPDATE wagers SET accepted="1" WHERE wager_id = "' . mysqli_real_escape_string($dbLink, $wagerId) . '"';
    mysqli_query($dbLink, $query);
}

$query = 'SELECT * FROM wagers WHERE wager_id = "' . mysqli_real_escape_string($dbLink, $wagerId) . '" LIMIT 1';
$result = mysqli_query($dbLink, $query);
if(mysqli_num_rows($result) == 1){
    $wager = mysqli_fetch_assoc($result);
}

if($wager['accepted'])
{
    $accepted = 'Yes';
} else {
    $accepted = 'No';
}

if($wager['wager_type'] == 'min')
{
    $wagerType = 'Get at least '. $wager['step_goal'] .' steps!';
} else {
    $wagerType = 'Most Steps Wins!';
}

$query      = 'SELECT * FROM users WHERE fitbit_id = "' . mysqli_real_escape_string($dbLink, $wager['creator_id']) . '" LIMIT 1';
$userResult     = mysqli_query($dbLink, $query);
if(mysqli_num_rows($userResult) == 1){
    $row = mysqli_fetch_assoc($userResult);
    $creator = $row['username'] . ' (' . $wager['creator_id'] . ')';
} else {
    $creator = $wager['creator_id'];
}

$query      = 'SELECT * FROM users WHERE fitbit_id = "' . mysqli_real_escape_string($dbLink, $wager['opponent_id']) . '" LIMIT 1';
$userResult     = mysqli_query($dbLink, $query);
if(mysqli_num_rows($userResult) == 1){
    $row = mysqli_fetch_assoc($userResult);
    $opponent = $row['username'] . ' (' . $wager['opponent_id'] . ')';
} else {
    $opponent = $wager['opponent_id'];
}

?>

<div class="content">
    <h4>Wager</h4>

    <div class="center-block">
    <dl class="dl-horizontal">
        <?php
        echo '<dt>Wager Name</dt><dd>', $wager['name'], '</dd>';
        echo '<dt>Creator</dt><dd>', $creator, '</dd>';
        echo '<dt>Opponent</dt><dd>', $opponent, '</dd>';
        echo '<dt>Accepted</dt><dd>', $accepted, '</dd>';
        echo '<dt>Wager Type</dt><dd>', $wagerType, '</dd>';
        echo '<dt>Start Date</dt><dd>', $wager['start_date'], '</dd>';
        echo '<dt>End Date</dt><dd>', $wager['end_date'], '</dd>';
        echo '<dt>Message</dt><dd>', $wager['message'], '</dd>';
        echo '<dt>Creator Steps</dt><dd>', $wager['creator_steps'], '</dd>';
        echo '<dt>Opponent Steps</dt><dd>', $wager['opponent_steps'], '</dd>';
        echo '<dt>Winner</dt><dd>', $wager['winner'], '</dd>';
        ?>
    </dl>
    </div>

    <?php
    if ($wager['opponent_id'] == $_SESSION['user']['fitbit_id'] && $wager['accepted'] == '0'){

        echo '<div class="center-block">';
        echo '<form accept-charset="UTF-8" role="form" name="wagerForm" action="', $_SERVER['PHP_SELF'], '" method="post">';
        echo '<input type="hidden" name="accepted" value="true" />';
        echo '<input type="hidden" name="w" value="', $wagerId, '" />';
        echo '<input class="btn btn-hg btn-danger"  type="submit" value="Accept the Wager!">';
        echo '</form>';
        echo '</div>';

    }
    ?>

</div>

<?php include 'footer.php' ?>