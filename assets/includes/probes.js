function perform_search(){
  $.blockUI({ css: { border: 'none',padding: '15px',backgroundColor: '#000','-webkit-border-radius': '10px','-moz-border-radius': '10px',opacity: .5, color: '#fff' } });
  var dataString = '';
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/probes/ajax_load_probe_list",
    data: dataString,
    cache: false,
    success: function(data){
      $('#probes_table').find("tr:gt(0)").remove();
      if(!data['error']){
        if(data['num_results'] != 0){
          var counter = 0;
          $.each(data, function(e,v) {
            if(e != 'error' && e != 'error_description' && e != 'num_results')
              $('#probes_table tr:last').after('<tr>'+
              '<td>'+data[counter]['name']+'</td>'+
              '<td>'+data[counter]['type']+'</td>'+
              '<td>'+data[counter]['cat']+'</td>'+
              '<td><div class="text-center">'+data[counter]['hw_status']+'</div></td>'+
			  '<td><div class="text-center">'+data[counter]['internet_status']+'</div></td>'+
			  '<td><div class="text-center"><img src="images/wifi40_small.jpg" /><br /><small>CEM-GUEST<br />24 Mbps | 20%</small></div></td>'+
			  '<td><div class="text-center"><img src="images/wifinope_small.jpg" /><br /><small>CEM-GUEST<br />24 Mbps | 70%</small></div></td>'+
              '<td><button class="btn btn-primary btn-xs row_edit_button" data-title="Edit" data-toggle="modal" data-target="#probe_config_modal" id="'+data[counter]['id']+'"><span class="glyphicon glyphicon-cog"></span></button> '+
              '<button class="btn btn-warning btn-xs last_test_button" data-toggle="modal" data-target="#probe_details_modal" id="testbutton_'+data[counter]['id']+'"><span class="glyphicon glyphicon-signal"></span></button></td>'+
              '</tr><tr><td colspan="10"><small>'+data[counter]['last_hw_test_data']+' '+data[counter]['last_ping_test_data']+' '+data[counter]['last_speed_test_data']+'</span></td></tr>');
              counter++;
          });
        }
        else{
          $('#probes_table tr:last').after('<tr><td colspan="6"><div class="alert alert-danger text-center" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> There\'s nothing here</div></td></tr>');
        }
      }
      else{
         $('#probes_table tr:last').after('<tr><td colspan="6"><div class="alert alert-danger text-center" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> '+data['error_description']+'</div></td></tr>');
      }
      $.unblockUI();
    }
  });
  return false;
}

function add_probe(){
  $('#add_new_probe_message').html('&nbsp;');
  $('#add_new_probe_button').html('<i class="glyphicon glyphicon-repeat gly-spin"></i>');
  var dataString = 'w_mac='+$('#w_mac_address').val()+'&wl_mac='+$('#wl_mac_address').val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/probes/ajax_add_probe",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
        $('#add_new_probe_message').html('<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span> <span style="color:green;">Success</span> Your wired write key is '+data['w_write_key']+' Your wireless write key is '+data['wl_write_key']);
      }
      else{
        $('#add_new_probe_message').html('<span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="color:red;"></span> <span style="color:red;">'+data['error_description']+'</span>');
      }
      $('#add_new_probe_button').html('Save');
      perform_search();
    }
  });
  return false;
}

function get_probe_data(id){
  $.blockUI({ css: { border: 'none',padding: '15px',backgroundColor: '#000','-webkit-border-radius': '10px','-moz-border-radius': '10px',opacity: .5, color: '#fff' } });
  $('#probe_config_message').html('&nbsp;');
  $('.jq_val').val('Loading...');
  $('.jq_html').html('Loading...');
  var dataString = 'id='+id;
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/probes/ajax_get_probe_data",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
		//Probe configuration
        $('#name').val(data['name']);
        $('#w_mac').html(data['w_mac']);
        $('#wl_mac').html(data['wl_mac']);
        $('#w_write_key').html(data['w_write_key']);
        $('#wl_write_key').html(data['wl_write_key']);
		
		//configuration server data
        $('#server_1').val(data['server_1']);
		
		//ping test data
        $('#ping_server').val(data['ping_server']);
		if(data['ping_test_freq'] == 0){
			$('#enable_ping_test').prop('checked', false);
			$('.ping_test').prop('disabled', true);
		}
		else{
			$('#enable_ping_test').prop('checked', true);
			$('.ping_test').prop('disabled', false);
		}
		$('#ping_test_freq').val(data['ping_test_freq']);
		$('#num_ping_tests').val(data['num_ping_tests']);
		
		//speed test data
		$('#speed_test_server').val(data['speed_test_server']);
		if(data['speed_test_freq'] == 0){
			$('#enable_speed_test').prop('checked', false);
			$('.speed_test').prop('disabled', true);
		}
		else{
			$('#enable_speed_test').prop('checked', true);
			$('.speed_test').prop('disabled', false);
		}
		$('#speed_test_freq').val(data['speed_test_freq']);
		
		//channel test data
		if(data['test_2'])
			$("input[name=channel_type_radio][value=2only]").prop('checked', true);
		else if(data['test_5'])
			$("input[name=channel_type_radio][value=5only]").prop('checked', true);
		else
			$("input[name=channel_type_radio][value=both]").prop('checked', true);

		if(data['channel_test_freq'] == 0){
			$('#enable_channel_test').prop('checked', false);
			$('.channel_test').prop('disabled', true);
		}
		else{
			$('#enable_channel_test').prop('checked', true);
			$('.channel_test').prop('disabled', false);
		}
		$('#channel_test_freq').val(data['channel_test_freq']);
		
        $('#wifiap').val(data['wifiap']);
        $('#wifiun').val(data['wifiun']);
        $('#wifipw').val(data['wifipw']);

        $('#probe_edit_config_id').val(data['config_id']);
        $('#probe_id').val(data['id']);
      }
      else{
        $('#probe_config_message').html('<span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="color:red;"></span> <span style="color:red;">'+data['error_description']+'</span>');
      }
      $.unblockUI();
    }
  });
  return false;
}

function edit_probe(){
	$('#probe_config_message').html('&nbsp;');
	var ping_test_enabled = false;
	if($('#enable_ping_test').prop('checked'))
		ping_test_enabled = true;
	var speed_test_enabled = false;
	  if($('#enable_speed_test').prop('checked'))
		speed_test_enabled = true;
	var channel_test_enabled = false;
	  if($('#enable_channel_test').prop('checked'))
		channel_test_enabled = true;

  var dataString = 'server_1='+$('#server_1').val()+'&name='+$('#name').val()+
                    '&wifiap='+$('#wifiap').val()+'&ping_test_freq='+$('#ping_test_freq').val()+'&probe_id='+$('#probe_id').val()+
                    '&speed_test_freq='+$('#speed_test_freq').val()+'&ping_server='+$('#ping_server').val()+'&speed_test_server='+$('#speed_test_server').val()+
                    '&wifiun='+$('#wifiun').val()+'&num_ping_tests='+$('#num_ping_tests').val()+
                    '&wifipw='+$('#wifipw').val()+'&config_id='+$('#probe_edit_config_id').val()+'&p_en='+ping_test_enabled+
					'&s_en='+speed_test_enabled+'&wifi='+$('input[name=channel_type_radio]:checked').val()+
					'&c_f='+$('#channel_test_freq').val()+'&c_en='+channel_test_enabled;
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/probes/ajax_edit_probe",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
        $('#probe_config_message').html('<span class="glyphicon glyphicon-ok" aria-hidden="true" style="color:green;"></span> <span style="color:green;">Changed successfully.</span>')
        $('#probe_edit_config_id').val(data['config_id']);
        $('#probe_id').val(data['id']);
        perform_search();
      }
      else{
        $('#probe_config_message').html('<span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="color:red;"></span> <span style="color:red;">'+data['error_description']+'</span>');
      }
    }
  });
  return false;
}

function get_last_test(id){
	$('.test_result').html('Loading...');
	var dataString = 'id='+id;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "/probes/ajax_get_last_test",
		data: dataString,
		cache: false,
		success: function(data){
			if(!data['error']){
				$('#ping_time').html(data['ping_time']);
				$('#packets_sent').html(data['packets_sent']);
				$('#packets_received').html(data['packets_received']);
				$('#average_latency').html(data['average_latency']);
				$('#packet_loss').html(data['packet_loss']);
				$('#max_time').html(data['max_time']);
				$('#min_time').html(data['min_time']);
				$('#average_time').html(data['average_time']);
				$('#ping_server_').html(data['ping_server']);
				$('#ping_test_status').html(data['ping_test_status']);
				
				$('#speed_time').html(data['speed_time']);
				$('#speed_download_span').html(data['speed_download_span']);
				$('#speed_upload_span').html(data['speed_upload_span']);
				$('#speed_server_span').html(data['speed_server_span']);
				$('#speed_test_status').html(data['speed_test_status']);
			}
			else{

			}
		}
	});
	return false;
}

$(document).ready(function(event) {
  perform_search();
  $('#add_new_probe_button').click(function(){
    add_probe();
  });
  $('#table_refresh').click(function(){
    perform_search();
  });

  $('#probes_table').on('click', '.row_edit_button', function() {
    get_probe_data(this.id);
  });
  
  $('#probes_table').on('click', '.last_test_button', function() {
	id = this.id;
	id = id.split('_');
	get_last_test(id[1]);
  });

  $('#probe_config_save_button').click(function(){
    edit_probe();
  });
  
  $('#enable_ping_test').click(function(){
	if($('#enable_ping_test').prop('checked')){
		$('#ping_test_freq').val('5');
		$('.ping_test').prop('disabled', false);
	}
	else{
		$('#ping_test_freq').val('0');
		$('.ping_test').prop('disabled', true);
	}
  });
  
  $('#enable_speed_test').click(function(){
	if($('#enable_speed_test').prop('checked')){
		$('#speed_test_freq').val('10');
		$('.speed_test').prop('disabled', false);
	}
	else{
		$('#speed_test_freq').val('0');
		$('.speed_test').prop('disabled', true);
	}
  });
  
  $('#enable_channel_test').click(function(){
	if($('#enable_channel_test').prop('checked')){
		$('#channel_test_freq').val('15');
		$('.channel_test').prop('disabled', false);
		$('input[name=channel_type_radio]').val('2only');
	}
	else{
		$('#channel_test_freq').val('0');
		$('.channel_test').prop('disabled', true);
	}
  });

  setInterval(function() {
    perform_search();
  }, 60000);

});
