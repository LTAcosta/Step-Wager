<?php
$isFitbitRequired = true;
include 'header.php';

if(!$isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}

if(!$isFitbitSetup || !$isFitbitConnected){
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
$opponent = $_REQUEST["o"];
$opponentId = null;
$stepCount = '5000';

if ($opponent){
    $query      = 'SELECT * FROM users WHERE username = "' . mysqli_real_escape_string($dbLink, $opponent) . '" LIMIT 1';
    $userResult     = mysqli_query($dbLink, $query);
    if(mysqli_num_rows($userResult) == 1){
        $user = mysqli_fetch_assoc($userResult);
        $opponentId = $user['fitbit_id'];
    }
}

if ($opponentId == $_SESSION['user']['fitbit_id'])
    $opponentId = null;

// Reset errors and success messages
$errors = array();

// Register attempt
if(isset($_POST['wagerSubmit']) && $_POST['wagerSubmit'] == 'true'){
    $wagerName = trim($_POST['wagerName']);
    $opponentId = trim($_POST['opponentId']);
    $wagerType = trim($_POST['wagerType']);
    $stepCount = trim($_POST['stepCount']);
    $startDate = trim($_POST['startDate']);
    $endDate = trim($_POST['endDate']);
    $message = trim($_POST['message']);

    if(strlen($wagerName) < 5 || strlen($wagerName) > 30)
        $errors['wagerName'] = 'Your wager\'s name must be between 5-30 characters.';

    if(strlen($opponentId) == 0 || !ctype_alnum ($opponentId))
        $errors['opponentId'] = 'Something\'s wrong with this opponent. Please try again or select someone else.';

    if ($wagerType != 'min' && $wagerType != 'most')
        $errors['wagerType'] = 'Please select a valid wager type.';

    if ($wagerType != 'min')
        $stepCount = null;
    else if(!is_numeric($stepCount) || intval($stepCount) <= 0 || intval($stepCount) >= 4294967295)
        $errors['wagerType'] = 'Your step goal must be between 0 and 4 billion. (Go for 4 billion!)';

    $start = null;
    try {
        $start = date_create($startDate);
    } catch (Exception $e) {
        $errors['startDate'] = 'Please enter a valid start date.';
    }

    if ($start != null && date_diff($start, date_create()) <= 0)
        $errors['startDate'] = 'Your start date must be after today.';

    $end = null;
    try {
        $end = date_create($endDate);
    } catch (Exception $e) {
        $errors['endDate'] = 'Please enter a valid end date.';
    }

    if ($end != null && $start != null && date_diff($end, $start) <= 0)
        $errors['endDate'] = 'Your end date must be after the start date.';

    if(strlen($message) > 1000)
        $errors['message'] = 'Your message must be under 1000 characters.';

    if(!$errors){
        $query = 'INSERT INTO wagers SET name = "' . mysqli_real_escape_string($dbLink, $wagerName) . '",
                                         creator_id = "' . mysqli_real_escape_string($dbLink, $_SESSION['user']['fitbit_id']) . '",
                                         opponent_id = "' . mysqli_real_escape_string($dbLink, $opponentId) . '",
                                         start_date = "' . mysqli_real_escape_string($dbLink, date('Y-m-d', strtotime($startDate))) . '",
                                         end_date = "' . mysqli_real_escape_string($dbLink, date('Y-m-d', strtotime($endDate))) . '",
                                         wager_type = "' . mysqli_real_escape_string($dbLink, $wagerType) . '",
                                         step_goal = "' . mysqli_real_escape_string($dbLink, $stepCount) . '",
                                         message = "' . mysqli_real_escape_string($dbLink, $message) . '"';

        if(!mysqli_query($dbLink, $query)){
            $errors['wager'] = 'There was a problem creating your wager. Please check your details and try again.';
        }else{
            $query  = 'SELECT * FROM wagers WHERE creator_id = "' . mysqli_real_escape_string($dbLink, $_SESSION['user']['fitbit_id']) . '"
                                              AND opponent_id = "' . $opponentId . '" ORDER BY wager_id DESC LIMIT 1';
            $result = mysqli_query($dbLink, $query);
            if(mysqli_num_rows($result) == 1){
                $wager = mysqli_fetch_assoc($result);
                header('Location: index.php');
                echo '<script>window.location = "wager.php?w=', $wager['wager_id'], '"</script>';
                exit;

            } else {
                header('Location: wagers.php');
                echo "<script>window.location = 'wagers.php'</script>";
                exit;
            }
        }
    }
}

?>

    <div class="content">

        <div id="new-wager-form" class="row jumbotron">
            <div class="content login">
                <div class="col-md-6"></div>
                <h4>New Wager</h4>
                <?php if($errors['wager']) print '<div class="invalid">' . $errors['wager'] . '</div>'; ?>
                <form accept-charset="UTF-8" role="form" name="wagerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <fieldset>
                        <div class="form-group <?php if($errors['wagerName']) echo 'has-error'; ?>">
                            <div class="input-group">
                                <span class="input-group-btn"><button class="btn" type="button"><span class="fui-tag"></span></button></span>
                                <input class="form-control" placeholder="Wager Name" name="wagerName" type="text" value="<?php echo htmlspecialchars($wagerName); ?>">
                            </div>
                            <?php if($errors['wagerName']) print '<div class="invalid">' . $errors['wagerName'] . '</div>'; ?>
                        </div>
                        <div class="row">
                        <div class="col-md-6 form-group <?php if($errors['opponentId']) echo 'has-error'; ?>">
                            <select id="friend-picker" class="show-tick" title="Opponent" data-width="100%" name="opponentId" data-live-search="true">
                                <optgroup label="Opponent">
                                    <?php
                                    $registered = array();
                                    $notregistered = array();

                                    if ($opponentId && $opponent)
                                        $registered[$opponentId] = $opponent;

                                    $user_gateway = $users_factory->getUserGateway();
                                    $friends = $user_gateway->getFriends()->friends;

                                    foreach($friends as $friend){
                                        $user = $friend->user;
                                        $id = $user->encodedId;
                                        $name = $user->displayName;

                                        $query = 'SELECT * FROM users WHERE fitbit_id = "' . mysqli_real_escape_string($dbLink, $id) . '" LIMIT 1';
                                        $userResult = mysqli_query($dbLink, $query);

                                        if(mysqli_num_rows($userResult) == 1){
                                            $stepuser = mysqli_fetch_assoc($userResult);
                                            $stepname = $stepuser['username'];
                                            $registered[$id] = $name . ' (' . $stepname . ')';
                                        } else {
                                            $notregistered[$id] = $name;
                                        }
                                    }

                                    asort($registered);
                                    foreach($registered as $id => $name){
                                        echo '<option value="', $id, '">', $name, '</option>';
                                    }

                                    asort($notregistered);
                                    foreach($notregistered as $id => $name){
                                        echo '<option value="', $id, '">', $name, '</option>';
                                    }

                                    if ($opponentId)
                                        echo '<script>window.selectedOppId="', $opponentId, '";</script>';

                                    if (count($registered) == 0 && count($notregistered) == 0)
                                        echo '<script>window.noFriends=true;</script>';
                                    ?>
                            </select>
                            <?php if($errors['opponentId']) print '<div class="invalid">' . $errors['opponentId'] . '</div>'; ?>
                        </div>
                        <div class="col-md-6 form-group <?php if($errors['wagerType']) echo 'has-error'; ?>">
                            <div class="row">
                                <div class="col-xs-<?php if($wagerType == 'min') echo 7; else echo 12;?>" id="type-column">
                                    <select title="Wager Type" data-width="100%" onChange="typeChanged(this)" name="wagerType">
                                        <optgroup label="Wager Type">
                                            <option value="most" <?php if($wagerType == 'most') echo 'selected="selected"'?>>Most Steps Wins!</option>
                                            <option value="min" <?php if($wagerType == 'min') echo 'selected="selected"'?>>Get at least __ steps!</option>
                                    </select>
                                </div>
                                <div class="col-xs-5" id="spinner-column" style="display:<?php if($wagerType == 'min') echo 'visible'; else echo 'none';?>">
                                    <input type="text" id="step-counter" value="<?php echo htmlspecialchars($stepCount); ?>" class="form-control spinner" name="stepCount"/>
                                </div>
                            </div>
                            <?php if($errors['wagerType']) print '<div class="invalid">' . $errors['wagerType'] . '</div>'; ?>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6 form-group <?php if($errors['startDate']) echo 'has-error'; ?>">
                            <div class="input-group">
                                <span class="input-group-btn"><button class="btn" type="button"><span class="fui-calendar"></span></button></span>
                                <input type="text" class="form-control" placeholder="Start Date" id="startdate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>"/>
                            </div>
                            <?php if($errors['startDate']) print '<div class="invalid">' . $errors['startDate'] . '</div>'; ?>
                        </div>
                        <div class="col-md-6 form-group <?php if($errors['endDate']) echo 'has-error'; ?>">
                            <div class="input-group">
                                <span class="input-group-btn"><button class="btn" type="button"><span class="fui-calendar"></span></button></span>
                                <input type="text" class="form-control" placeholder="End Date" id="enddate" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>"/>
                            </div>
                            <?php if($errors['endDate']) print '<div class="invalid">' . $errors['endDate'] . '</div>'; ?>
                        </div>
                        </div>
                        <div class="form-group <?php if($errors['message']) echo 'has-error'; ?>">
                            <textarea rows="3" placeholder="Add a message..." class="form-control" name="message"><?php echo htmlspecialchars($message); ?></textarea>
                            <?php if($errors['message']) print '<div class="invalid">' . $errors['message'] . '</div>'; ?>
                        </div>
                        <input type="hidden" name="wagerSubmit" id="wagerSubmit" value="true" />
                        <input class="btn btn-lg btn-block btn-danger" type="submit" value="Start Wager!">
                    </fieldset>
                </form>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="friend-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">No Friends!</h4>
                </div>
                <div class="modal-body">
                    Oh No! It looks like you don't have any Fitbit friends to compete with. Try adding people to your friends list or finding someone new from the leaderboard.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Go Back To Wagers</button>
                    <a href="https://www.fitbit.com/friends" class="btn btn-primary">Go To Friends List</a>
                    <a href="leaderboard.php" class="btn btn-primary">Go To Leaderboard</a>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php' ?>