function get_network_data(){
  $('#dashboard_error_message').html('');
  var dataString = '';
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "/probes/ajax_get_probes_stats",
    data: dataString,
    cache: false,
    success: function(data){
      if(!data['error']){
        var config = {
          type: 'pie',
          data: {
            datasets: [{
            data: [
              data['percentage_offline'],
              data['percentage_online'],
			  data['percentage_wait'],
            ],
            backgroundColor: [
              'rgb(188, 35, 35)',
              'rgb(66, 175, 85)',
			  'rgb(250,250,210)',
            ],
            label: 'Dataset 1'
            }],
            labels: [
              'Offline',
              'Online',
			  'Not synced',
            ]
          },
          options: {
            responsive: true
          }
        };

        var ctx = document.getElementById('chart-area').getContext('2d');
        window.myPie = new Chart(ctx, config);

        $('#total_probes').html(data['total_probes']);
        if(data['num_offline'] != 0){
          $('#num_offline').html(data['num_offline']);
          $('#alarm_div').show();
        }
        else{
          $('#num_offline').html(data['num_offline']);
          $('#alarm_div').hide();
        }

      }
      else{
        $('#dashboard_error_message').html('<span class="glyphicon glyphicon-ban-circle" aria-hidden="true" style="color:red;"></span> <span style="color:red;">'+data['error_description']+'</span>');
      }
    }
  });
}

function date_time(id)
{
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
}

$(document).ready(function(event) {
  $('#alarm_div').hide();
  get_network_data();
  //get total number of probes on group
  //get number and percentage Online
  //get number and percentage offline
  //if there are probes offline, show the danger label and sound alarm

  window.onload = date_time('date_time');

  setInterval(function() {
    get_network_data();
  }, 60000);

});
