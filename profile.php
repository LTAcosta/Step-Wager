<?php include 'header.php' ?>

<div class="content">
  <?php
  // Clean up the input values
  foreach($_REQUEST as $key => $value) {
      if(ini_get('magic_quotes_gpc'))
          $_REQUEST[$key] = stripslashes($_REQUEST[$key]);

      $_REQUEST[$key] = htmlspecialchars(strip_tags($_REQUEST[$key]));
  }

  // Assign the input value to a variable for easy reference
  $username = $_REQUEST["u"];
  $user = null;

  if ($username){
      $query      = 'SELECT * FROM users WHERE username = "' . mysqli_real_escape_string($dbLink, $username) . '" LIMIT 1';
      $userResult     = mysqli_query($dbLink, $query);
      if(mysqli_num_rows($userResult) == 1){
          $user = mysqli_fetch_assoc($userResult);
      }
  } elseif ($isUserLoggedIn) {
      $user = $_SESSION['user'];
  }

  $self = $isUserLoggedIn && $user['username'] == $_SESSION['user']['username'];
  $noFitbit = $user['fitbit_id'] == null;

  if ($user){
      echo '<h4>', $user['username'], '</h4>';
  } else {
      echo '<h4>User Not Found!</h4>';
  }

  echo '<div class="media">';

  if ($user['image'])
      echo '<img class="media-object pull-left" src="', $user['image'], '" width="150px" height="150px">';
  else
      echo '<img class="media-object pull-left" src="holder.js/150x150">';


  ?>

        <div class="media-body">
            <dl class="dl-horizontal">
                <?php
                echo '<dt>Wins</dt><dd>', $user['wins'], '</dd>';
                echo '<dt>Ties</dt><dd>', $user['ties'], '</dd>';
                echo '<dt>Losses</dt><dd>', $user['losses'], '</dd>';
                ?>
            </dl>
        </div>
    </div>

<?php
    if(!$self){
        echo '<div id="new-wager">';
        if ($noFitbit){
            echo '<div style="display:inline;" data-tooltip-style="light" data-toggle="tooltip" data-placement="top" title="', $user['username'],' needs to connect to Fitbit!">';
        }

        echo '<a class="btn btn-hg btn-danger ';

        if ($noFitbit){
            echo 'disabled';
        }

        echo '" href="';

        if ($noFitbit){
            echo '#';
        } else {
            echo 'new.php?o=', $user['username'];
        }

        echo '">Start a New Wager!</a></div>';

        if ($noFitbit){
            echo '</div>';
        }
    }
?>

</div>

<?php include 'footer.php' ?>