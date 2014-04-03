<?php
include_once 'functions.php';
include 'header.php';

// Reset errors and success messages
$errors = array();
$success = array();

// Login attempt
if(isset($_POST['loginSubmit']) && $_POST['loginSubmit'] == 'true'){
    $loginEmail = trim($_POST['email']);
    $loginPassword  = trim($_POST['password']);

    if (!validEmail($loginEmail))
        $errors['loginEmail'] = 'Your email address is invalid.';

    if(strlen($loginPassword) < 6 || strlen($loginPassword) > 12)
        $errors['loginPassword'] = 'Your password must be between 6-12 characters.';

    if(!$errors){
        $query  = 'SELECT * FROM users WHERE email = "' . mysql_real_escape_string($loginEmail) . '" AND password = MD5("' . $loginPassword . '") LIMIT 1';
        $result = mysql_query($query);
        if(mysql_num_rows($result) == 1){
            $user = mysql_fetch_assoc($result);
            $query = 'UPDATE users SET session_id = "' . session_id() . '" WHERE id = ' . $user['id'] . ' LIMIT 1';
            mysql_query($query);
            header('Location: index.php');
            echo "<script>window.location = 'index.php'</script>";
            exit;
        }else{
            $errors['login'] = 'No user was found with the details provided.';
        }
    }
}

if($isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}

?>

    <div class="content">

        <div class="row jumbotron">
            <div class="col-md-6" id="welcome">
                <center><h3>Welcome!</h3></center>
                Welcome to Step Wager! <br><br> Step wager is place where you can challenge friends and strangers to step battles!
            </div>
            <div class="col-md-6">
                <div class="content login">
                    <h4>Please login or <a href="register.php">sign up</a></h4>
                    <?php if($errors['login']) print '<div class="invalid">' . $errors['login'] . '</div>'; ?>
                    <form accept-charset="UTF-8" role="form"  name="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <fieldset>
                            <div class="form-group <?php if($errors['loginEmail']) echo 'has-error'; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fui-mail"></i></span>
                                    <input class="form-control" placeholder="E-mail" name="email" type="text" value="<?php echo htmlspecialchars($loginEmail); ?>">
                                </div>
                            </div>
                            <?php if($errors['loginEmail']) print '<div class="invalid">' . $errors['loginEmail'] . '</div>'; ?>
                            <div class="form-group <?php if($errors['loginPassword']) echo 'has-error'; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fui-lock"></i></span>
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                            </div>
                            <?php if($errors['loginPassword']) print '<div class="invalid">' . $errors['loginPassword'] . '</div>'; ?>
                            <input type="hidden" name="loginSubmit" id="loginSubmit" value="true" />
                            <input class="btn btn-lg btn-block btn-primary" type="submit" value="Login">
                            <!--
                            <div class="col-xs-6">
                                <label class="checkbox" for="checkbox1">
                                    <input name="remember" type="checkbox" value="" id="checkbox1" data-toggle="checkbox"> Remember Me
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <a href="" class="checkbox" id="forgotpass">Forgot Password?</a>
                            </div>
                            -->
                        </fieldset>
                    </form>
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