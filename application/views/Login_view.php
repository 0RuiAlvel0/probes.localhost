<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Open NEMO</title>
    <meta name="description" content="">
    <meta name="author" content="Open NEMO">
    <link rel="stylesheet" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="/assets/css/glyphicon_rotate.css" />
    <script type="text/javascript" src="/assets/js/jquery-3.2.0.min.js"></script>
    <script type="text/javascript" src="/assets/includes/login.js"></script>
    <script type="text/javascript" src="/assets/includes/md5.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      img.expand { width: 25em; };
    </style>
    <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 controls text-center" style="padding-top:30px">
          <img src="/assets/images/nemologo.png" class="expand"/>
        </div>
      </div>
      <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="panel-title">Sign In</div>
            <div style="float:right; font-size: 80%; position: relative; top:-10px">
              <a href="#" onClick="$('#loginbox').hide(); $('#forgot_pass').show()">Forgot password?</a>
            </div>
          </div>
          <div style="padding-top:30px" class="panel-body">
            <form id="loginform" class="form-horizontal" role="form">
              <div style="margin-bottom: 25px" class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" class="form-control" id="email" name="email" value="" placeholder="email">
              </div>
              <div style="margin-bottom: 25px" class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="password">
              </div>
              <!--<div class="input-group">
                <div class="checkbox">
                  <label><input id="login-remember" type="checkbox" name="remember" value="1"> Remember me</label>
                </div>
              </div>-->
              <div style="margin-top:10px" class="form-group">
                <div class="col-sm-12 controls text-center">
                  <button type="submit" class="btn btn-success" id="login_button">Login</button>
                  <button type="submit" class="btn btn-danger" id="error_message" style="display:none;"></button>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12 control text-center">
                  <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                    Don't have an account? <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()" id="signup_link">Sign Up Here</a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div id="forgot_pass" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="panel-title">Perform WIFI - Password recovery</div>
            <div style="float:right; font-size: 85%; position: relative; top:-10px">
              <a href="#" onclick="$('#forgot_pass').hide(); $('#loginbox').show()">Sign In</a>
            </div>
          </div>
          <form id="recover_form" class="form-horizontal" role="form">
            <div class="panel-body" >
              <div class="form-group">
                <div class="col-md-12">
                  <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input type="text" class="form-control" id="recover_email" value="" placeholder="email">
                  </div>
                </div>
              </div>
              <div class="text-center">
                <span id="recover_message">&nbsp;</span>
                <button type="submit" class="btn btn-success" id="recover_button">Go</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="panel-title">Perform WIFI - Sign up</div>
            <div style="float:right; font-size: 85%; position: relative; top:-10px">
              <a href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a>
            </div>
          </div>
          <div class="panel-body" >
            <form id="signupform" class="form-horizontal" role="form">
              <div class="form-group">
                <label for="email" class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="register_email" placeholder="Email Address">
                </div>
              </div>
              <div class="form-group">
                <label for="password" class="col-md-3 control-label">Password</label>
                <div class="col-md-9">
                  <input type="password" class="form-control" id="register_password" placeholder="8 or more chars, 1 uppercase, 1 number">
                </div>
              </div>
              <div class="form-group">
                <img src="/assets/images/white_background.png" style="display:none;"/>
                <label for="captcha" class="col-md-3 control-label"><span id="captcha_image"><?php //echo $captcha;?></span></label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="captcha_text" placeholder="Enter text exactly as on picture">
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-success" id="signup_button">Submit</button>
                <button type="submit" class="btn btn-danger" id="signup_error_message" style="display:none;"></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
