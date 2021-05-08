function save(){
  $('#save_settings_message').html('Saving...');
  var dataString = 'm='+$('#notification_offline_minutes').val()+'&off='+$('#offline_addresses').val()+'&on='+$('#online_addresses').val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/settings/ajax_save",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
		$('#save_settings_message').html('ok, saved.');
      }
      else{
		$('#save_settings_message').html('ERROR, tryagain');
      }
    }
  });
  return false;
}

$(document).ready(function(event) {
  $('#save_settings_button').click(function(e){ 
	  e.preventDefault(); 
	  save(); 
  });

});
