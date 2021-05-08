$(document).ready(function(event) {

  $('#loginform').submit(function(){
    event.preventDefault();
    $('#submit_button').trigger('click');
  });

  $('#signupform').submit(function(){
    event.preventDefault();
    $('#recover_button').trigger('click');
  });

  $('#recover_form').submit(function(){
    event.preventDefault();
    $('#signup_button').trigger('click');
  });

  $('#recover_button').click(function(e){
    e.preventDefault();
    $('#recover_button').html('<i class="glyphicon glyphicon-repeat gly-spin"></i>');
    $('#recover_message').html('&nbsp;');
    e.preventDefault();
    var captcha = '';
		$.ajax({
			type: 'POST',
			url: '/login/ajax_recover',
			data: 'u='+$('#recover_email').val(),
			dataType: 'json',
			success: function(data){
        if(!data['error']){
          $('#recover_message').html('All good. Check your email.');
          $('#recover_button').hide();
        }
        else{
          $('#recover_message').html(data['error_description']);
          $('#recover_button').hide();
          $('#recover_button').html('Go');
          setTimeout('$("#recover_message").html("&nbsp;");$("#recover_button").show()',1500);
        }
			}
		});
		return false;
  });

  $('#login_button').on('click', function(e) {
    $('#login_button').html('<i class="glyphicon glyphicon-repeat gly-spin"></i>');
    e.preventDefault();
    var captcha = '';
		$.ajax({
			type: 'POST',
			url: '/login/validate',
			data: 'u='+$('#email').val()+'&p='+$.md5($('#password').val()),
			dataType: 'json',
			success: function(data){
        if(!data['error']){
          if(data['was_invited'] == '0')
            window.location.replace('dashboard');
          else
            if(data['case'] == '2')
              window.location.replace('users/settings');
            else
              window.location.replace('dashboard');
        }
        else{
          $('#captcha_image').html(data['captcha']);
          $('#error_message').html(data['error_description']);
          $('#login_button').hide();
          $('#error_message').show();
          $('#login_button').html('Login');
          setTimeout('$("#error_message").hide();$("#login_button").show()',1500);
        }
			}
		});
		return false;
	});

  $('#signup_button').on('click', function(e) {
    e.preventDefault();
    $('#captcha_image').html('<img src="/assets/images/white_background.png"/>');
    $('#signup_button').html('<i class="glyphicon glyphicon-repeat gly-spin"></i>');
		$.ajax({
			type: 'POST',
			url: '/login/register',
			data: 'u='+$('#register_email').val()+'&p='+$('#register_password').val()+'&captcha='+$('#captcha_text').val(),
			dataType: 'json',
			success: function(data){
        if(!data['error']){
          $("#signup_button").hide();
          $("#signup_error_message").html('All good! Enter your credentials on the login page');
          $('#signup_error_message').show();
          setTimeout(function(){window.location.replace('login')},1000);
        }
        else{
          $('#signup_error_message').html(data['error_description']);
          $('#signup_button').hide();
          $('#signup_error_message').show();
          $('#signup_button').html('Save');
          $('#captcha_image').html(data['captcha']);
          $('#captcha_text').val('');
          setTimeout('$("#signup_error_message").hide();$("#signup_button").show()',1500);
        }
			}
		});
		return false;
	});

});
