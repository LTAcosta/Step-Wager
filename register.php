<?php include 'header.php' ?>

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
        <form accept-charset="UTF-8" role="form">
          <fieldset>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-user"></i></span>
                <input class="form-control" placeholder="Username" name="username" type="text">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-mail"></i></span>
                <input class="form-control" placeholder="E-mail" name="email" type="text">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-lock"></i></span>
                <input class="form-control" placeholder="Password" name="password" type="password" value="">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fui-lock"></i></span>
                <input class="form-control" placeholder="Confirm Password" name="confirmpassword" type="password" value="">
              </div>
            </div>
            <input class="btn btn-lg btn-block btn-danger" type="submit" value="Register">
            <div class="col-xs-6">
			  <label class="checkbox" for="checkbox1">
                <input name="remember" type="checkbox" value="" id="checkbox1" data-toggle="checkbox"> Remember Me
              </label>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

</div>

<script>
  $(':checkbox').checkbox();
</script>

<?php include 'footer.php' ?>