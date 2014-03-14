      <div class="bottom-menu">
        <div class="container">
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6"><p><center>&copy; Step Wager 2014</center></p></div>
            <div class="col-md-3"></div>
          </div>
        </div>
      </div>

    </div><!-- /.container -->

    <!-- Load JS here for greater good =============================-->
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/flatui-checkbox.js"></script>
    <script src="js/flatui-radio.js"></script>
    <!-- <script src="js/holder.js"></script> -->
    <script src="js/flatui-fileinput.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <!-- <script src="js/typeahead.js"></script> -->
    <!-- <script src="js/application.js"></script> -->
    
    <script>
	  var url = window.location;
	  // Will only work if string in href matches with location
	  $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
	  
	  // Will also work for relative and absolute hrefs
	  $('ul.nav a').filter(function() {
	    return this.href == url;
	  }).parent().addClass('active');
	</script>
    
  </body>
</html>