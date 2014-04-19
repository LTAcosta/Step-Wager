<?php
include_once 'functions.php';
include 'header.php';

if($isUserLoggedIn){
    header('Location: index.php');
    echo "<script>window.location = 'index.php'</script>";
    exit;
}

// Reset errors and success messages
$errors = array();

// Register attempt
if(isset($_POST['registerSubmit']) && $_POST['registerSubmit'] == 'true'){
    $registerName = trim($_POST['username']);
    $registerEmail = trim($_POST['email']);
    $registerPassword = trim($_POST['password']);
    $registerConfirmPassword    = trim($_POST['confirmPassword']);

    if(strlen($registerName) < 6 || strlen($registerName) > 12)
        $errors['registerName'] = 'Your user name must be between 6-12 characters.';

    if(!ctype_alnum ($registerName))
        $errors['registerName'] = 'Your user name must only contain letters and numbers.';

    if (!validEmail($registerEmail))
        $errors['registerEmail'] = 'Your email address is invalid.';

    if(strlen($registerPassword) < 6 || strlen($registerPassword) > 12)
        $errors['registerPassword'] = 'Your password must be between 6-12 characters.';

    if($registerPassword != $registerConfirmPassword)
        $errors['registerConfirmPassword'] = 'Your passwords did not match.';

    // Check to see if we have a user registered with this name address already
    $query = 'SELECT * FROM users WHERE username = "' . mysqli_real_escape_string($dbLink, $registerName) . '" LIMIT 1';
    $result = mysqli_query($dbLink, $query);
    if(mysqli_num_rows($result) >= 1)
        $errors['registerName'] = 'This user name address already exists.';

    // Check to see if we have a user registered with this email address already
    $query = 'SELECT * FROM users WHERE email = "' . mysqli_real_escape_string($dbLink, $registerEmail) . '" LIMIT 1';
    $result = mysqli_query($dbLink, $query);
    if(mysqli_num_rows($result) >= 1)
        $errors['registerEmail'] = 'This email address already exists.';

    if(!$errors){
        $query = 'INSERT INTO users SET username = "' . mysqli_real_escape_string($dbLink, $registerName) . '",
                                                                        email = "' . mysqli_real_escape_string($dbLink, $registerEmail) . '",
                                                                        password = MD5("' . mysqli_real_escape_string($dbLink, $registerPassword) . '"),
                                                                        date_registered = "' . date('Y-m-d H:i:s') . '"';

        if(!mysqli_query($dbLink, $query)){
            $errors['register'] = 'There was a problem registering you. Please check your details and try again.';
        }else{
            $query  = 'SELECT * FROM users WHERE email = "' . mysqli_real_escape_string($dbLink, $registerEmail) . '" AND password = MD5("' . $registerPassword . '") LIMIT 1';
            $result = mysqli_query($dbLink, $query);
            if(mysqli_num_rows($result) == 1){
                $user = mysqli_fetch_assoc($result);
                $query = 'UPDATE users SET session_id = "' . session_id() . '" WHERE id = ' . $user['id'] . ' LIMIT 1';
                mysqli_query($dbLink, $query);
            }

            header('Location: index.php');
            echo "<script>window.location = 'index.php'</script>";
            exit;
        }
    }

}

?>

<div class="content">

  <div class="row jumbotron">
    <div class="col-md-6" id="welcome">
      <div>
        <center>Getting set up is fast and free!</center>
      </div>
      <div class="row regsteps">
	    <div class="col-xs-4 regstepsimg">
	      <img src="images/icons/clipboard.svg" alt="">
	    </div>
	    <div class="col-xs-8">
          <b>Step 1</b><br>
	      Make an Account.
	    </div>
	  </div>
      <div class="row regsteps">
	    <div class="col-xs-4 regstepsimg">
	      <img src="images/icons/converse.svg" alt="">
	    </div>
	    <div class="col-xs-8">
          <b>Step 2</b><br>
	      Link to Fitbit.
	    </div>
	  </div>
      <div class="row regsteps">
	    <div class="col-xs-4 regstepsimg">
	      <img src="images/icons/money.svg" alt="">
	    </div>
	    <div class="col-xs-8">
          <b>Step 3</b><br>
	      Make a wager!
	    </div>
	  </div>
    </div>
    <div class="col-md-6">
      <div class="content login">
        <h4>Make an Account</h4>
        <?php if($errors['register']) print '<div class="invalid">' . $errors['register'] . '</div>'; ?>
        <form accept-charset="UTF-8" role="form" name="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <fieldset>
            <div class="form-group <?php if($errors['registerName']) echo 'has-error'; ?>">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-user"></i></span>
                <input class="form-control" placeholder="Username" name="username" type="text" value="<?php echo htmlspecialchars($registerName); ?>">
              </div>
              <?php if($errors['registerName']) print '<div class="invalid">' . $errors['registerName'] . '</div>'; ?>
            </div>
            <div class="form-group <?php if($errors['registerEmail']) echo 'has-error'; ?>">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-mail"></i></span>
                <input class="form-control" placeholder="E-mail" name="email" type="text" value="<?php echo htmlspecialchars($registerEmail); ?>">
              </div>
              <?php if($errors['registerEmail']) print '<div class="invalid">' . $errors['registerEmail'] . '</div>'; ?>
            </div>
            <div class="form-group <?php if($errors['registerPassword']) echo 'has-error'; ?>">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-lock"></i></span>
                <input class="form-control" placeholder="Password" name="password" type="password" value="">
              </div>
              <?php if($errors['registerPassword']) print '<div class="invalid">' . $errors['registerPassword'] . '</div>'; ?>
            </div>
            <div class="form-group <?php if($errors['registerConfirmPassword']) echo 'has-error'; ?>">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-lock"></i></span>
                <input class="form-control" placeholder="Confirm Password" name="confirmPassword" type="password" value="">
              </div>
              <?php if($errors['registerConfirmPassword']) print '<div class="invalid">' . $errors['registerConfirmPassword'] . '</div>'; ?>
            </div>
            <input type="hidden" name="registerSubmit" id="registerSubmit" value="true" />
            <input class="btn btn-lg btn-block btn-danger" type="submit" value="Register">
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php' ?>