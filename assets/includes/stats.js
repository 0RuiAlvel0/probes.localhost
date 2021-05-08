var chart0;

function charts(){
  $('#chart_message').html('Generating...');
  var dataString = 'p_id='+$('#probe_id').val()+'&d='+$('#chart_date').val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/stats/ajax_get_chart_data",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
		  
		//START CHART
		var tests_per_hour_array = [];
		var tests_backgroundcolor = [];
		var tests_bordercolor = [];
		var labels = [];
		for(var i = 0; i < 24; i++){
			tests_per_hour_array.push(data['internet_contacts_per_hour'][i]);
			if(data['internet_contacts_per_hour'][i] >= 56){
				//paint bar green
				tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			labels.push(i);
		}
		var config = {
			type: 'bar',
			data: {
				datasets: [{
					label: '# of internet tests',
					data: tests_per_hour_array,
					backgroundColor: tests_backgroundcolor,
					borderColor: tests_bordercolor,
					borderWidth: 1
				}], 
				labels: labels,
          },
          options: {responsive: true}
        };
		var ctx = document.getElementById('chart-area').getContext('2d');
		chart = new Chart(ctx, config);
		//END CHART
		
		//START CHART0
		var tests_per_hour_array = [];
		var tests_backgroundcolor = [];
		var tests_bordercolor = [];
		var labels = [];
		for(var i = 0; i < 24; i++){
			tests_per_hour_array.push(data['server_contacts_per_hour'][i]);
			if(data['server_contacts_per_hour'][i] >= 56){
				//paint bar green
				tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			labels.push(i);
		}
		var config = {
			type: 'bar',
			data: {
				datasets: [{
					label: '# of server contact tests',
					data: tests_per_hour_array,
					backgroundColor: tests_backgroundcolor,
					borderColor: tests_bordercolor,
					borderWidth: 1
				}], 
				labels: labels,
          },
          options: {responsive: true}
        };
		var ctx = document.getElementById('chart0-area').getContext('2d');
		chart0 = new Chart(ctx, config);
		//END CHART0
		
		//START CHART1
		var min_time_per_hour_array = [];
		var min_time_tests_backgroundcolor = [];
		var min_time_tests_bordercolor = [];
		var average_time_per_hour_array = [];
		var average_time_tests_backgroundcolor = [];
		var average_time_tests_bordercolor = [];
		var max_time_per_hour_array = [];
		var max_time_tests_backgroundcolor = [];
		var max_time_tests_bordercolor = [];
		var labels = [];
		for(var i = 0; i < 24; i++){
			min_time_per_hour_array.push(data['ping_tests'][i]['min_time']);
			if(data['ping_tests'][i]['min_time'] <= 50){
				//paint bar green
				min_time_tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				min_time_tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				min_time_tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				min_time_tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			average_time_per_hour_array.push(data['ping_tests'][i]['average_time']);
			if(data['ping_tests'][i]['average_time'] <= 50){
				//paint bar green
				average_time_tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				average_time_tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				average_time_tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				average_time_tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			max_time_per_hour_array.push(data['ping_tests'][i]['max_time']);
			if(data['ping_tests'][i]['max_time'] <= 50){
				//paint bar green
				max_time_tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				max_time_tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				max_time_tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				max_time_tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			labels.push(i);
		}
		var config = {
			type: 'line',
			data: {
				datasets: [
					{
						label: 'ping min time',
						data: min_time_per_hour_array,
						backgroundColor: min_time_tests_backgroundcolor,
						borderColor: min_time_tests_bordercolor,
						borderWidth: 1
					},
					{
						label: 'ping average time',
						data: average_time_per_hour_array,
						backgroundColor: average_time_tests_backgroundcolor,
						borderColor: average_time_tests_bordercolor,
						borderWidth: 1
					},
					{
						label: 'ping max time',
						data: max_time_per_hour_array,
						backgroundColor: max_time_tests_backgroundcolor,
						borderColor: max_time_tests_bordercolor,
						borderWidth: 1
					}
				], 
				labels: labels,
          },
          options: {responsive: true}
        };
		var ctx = document.getElementById('chart1-area').getContext('2d');
		chart1 = new Chart(ctx, config);
		//END CHART1
		
		//START CHART2
		var tests_per_hour_array = [];
		var tests_backgroundcolor = [];
		var tests_bordercolor = [];
		var labels = [];
		for(var i = 0; i < 24; i++){
			tests_per_hour_array.push(data['ping_tests'][i]['packet_loss']);
			if(data['ping_tests'][i]['packet_loss'] <= 0){
				//paint bar green
				tests_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				tests_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				tests_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				tests_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			labels.push(i);
		}
		var config = {
			type: 'bar',
			data: {
				datasets: [{
					label: 'packet loss',
					data: tests_per_hour_array,
					backgroundColor: tests_backgroundcolor,
					borderColor: tests_bordercolor,
					borderWidth: 1
				}], 
				labels: labels,
          },
          options: {responsive: true}
        };
		var ctx = document.getElementById('chart2-area').getContext('2d');
		chart2 = new Chart(ctx, config);
		//END CHART 2
		
		//START CHART3
		var upload_per_hour_array = [];
		var upload_backgroundcolor = [];
		var upload_bordercolor = [];
		var download_per_hour_array = [];
		var download_backgroundcolor = [];
		var download_bordercolor = [];
		var labels = [];
		for(var i = 0; i < 24; i++){
			upload_per_hour_array.push(data['speed_tests'][i]['average_upload']);
			if(data['speed_tests'][i]['average_upload'] <= 1){
				//paint bar green
				upload_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				upload_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				upload_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				upload_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			download_per_hour_array.push(data['speed_tests'][i]['average_download']);
			if(data['speed_tests'][i]['average_download'] <= 1){
				//paint bar green
				download_backgroundcolor.push('rgba(75, 192, 192, 0.2)');
				download_bordercolor.push('rgba(75, 192, 192, 1)');
			}
			else{
				//paint bar red 
				download_backgroundcolor.push('rgba(255, 99, 132, 0.2)');
				download_bordercolor.push('rgba(255, 99, 132, 1)');
			}
			labels.push(i);
		}
		var config = {
			type: 'bar',
			data: {
				datasets: [{
						label: 'upload average speeds',
						data: upload_per_hour_array,
						backgroundColor: upload_backgroundcolor,
						borderColor: upload_bordercolor,
						borderWidth: 1
					},
					{
						label: 'download average speeds',
						data: download_per_hour_array,
						backgroundColor: download_backgroundcolor,
						borderColor: download_bordercolor,
						borderWidth: 1
					}
				], 
				labels: labels,
          },
          options: {responsive: true}
        };
		var ctx = document.getElementById('chart3-area').getContext('2d');
		chart3 = new Chart(ctx, config);
		
		$('#chart_message').html('Done');

      }
      else
        $('#chart_message').html(data['error_message']);
    }
  });
}

$(document).ready(function(event) {
	
	$('#go_button').click(function(e){
		e.preventDefault();
		if (typeof chart != 'undefined') {
			chart.destroy();
		}
		if (typeof chart0 != 'undefined') {
			chart0.destroy();
		}
		if (typeof chart1 != 'undefined') {
			chart1.destroy();
		}
		if (typeof chart2 != 'undefined') {
			chart2.destroy();
		}
		if (typeof chart3 != 'undefined') {
			chart3.destroy();
		}
		charts();
	});
});
