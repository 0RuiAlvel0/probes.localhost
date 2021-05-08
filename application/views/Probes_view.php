<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 text-left">
      <button style="margin-bottom:10px;" class="btn btn-primary" data-toggle="modal" data-target="#add_probe_modal" id="add_probe">+Probe</button>
      <button style="margin-bottom:10px;" class="btn btn-primary" data-toggle="modal" data-target="#add_category_modal" id="add_category">Groups</button>
    </div>
    <div class="col-lg-4 text-center">
      <button style="margin-bottom:10px;" class="btn btn-primary" id="table_refresh"><span class="glyphicon glyphicon-refresh"></span></button>
      <a href="#" ></a>
    </div>
    <div class="col-lg-4 text-right">
      <button style="margin-bottom:10px;" class="btn btn-primary" id="table_filters"><span class="glyphicon glyphicon-filter"></span></button>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <table class="table table-hover" width="100%" id="probes_table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Group</th>
            <th><div class="text-center"><span class="glyphicon glyphicon-arrow-right"></span>Server</div></th>
			<th><div class="text-center"><span class="glyphicon glyphicon-arrow-right"></span>Internet</div></th>
			<th><div class="text-center">2.4Ghz</div></th>
			<th><div class="text-center">5GHz</div></th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal 1 - add new probe-->
<div class="modal fade" id="add_probe_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add new probe</h3>
      </div>
      <div class="modal-body">
        <div>
          <ul>
            <li>Enter MAC addresses in the following format 0A:14:EE:01:23:45</li>
            <li>Make a note of the write keys the system will generate</li>
            <li>Find the IP of your probe and use a browser to connect to that address</li>
            <li>Enter the write keys you just generated</li>
            <li>After about a minute the probe will automatically appear on the list of probes</li>
            <li>You can now install the probe on any network with internet access and start monitoring</li>
          </ul>
        </div>
        <div>
          <label>Enter probe wired MAC address </label>
          <input type="text" id="w_mac_address">
          <label>Enter probe wireless MAC address </label>
          <input type="text" id="wl_mac_address">
        </div>
      </div>
      <div class="modal-footer">
        <div class="text-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="add_new_probe_button">Add</button>
          <div class="text-center"><span id="add_new_probe_message">&nbsp;</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal 1 - add new probe-->

<!-- Modal 2 - Change probe configuration-->
<div class="modal fade" id="probe_config_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
		<div class="row">
			<div class="col-lg-12">
				<H3>** Probe configuration **</H3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				Probe name<br />
				<input type="text" id="name" class="jq_val" class="form-control">
			</div>
			<div class="col-lg-4">
				<H4>Wireless <span id="wl_mac" class="jq_html"></span> <br /><span id="wl_write_key" class="jq_html"></span></h4>
			</div>
			<div class="col-lg-4">
				<h4>Wired <span id="w_mac" class="jq_html"></span> <br /><span id="w_write_key" class="jq_html"></span></H4>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<H3>** Servers configuration **</H3>
			</div>
		</div>
        <div class="row">
          <div class="col-lg-4">
            Configuration server
          </div>
          <div class="col-lg-4">
            <input type="text" id="server_1" class="jq_val">
          </div>
		  <div class="col-lg-4">
            &nbsp;
          </div>
        </div>

		<div class="row">
			<div class="col-lg-10">
				<H3>** Ping test configuration **</H3>
			</div>
			<div class="col-lg-2 text-center">
				<BR />
				<label> Enabled <input type="checkbox" id="enable_ping_test"></label>
			</div>
		</div>
        <div class="row">
          <div class="col-lg-4">
            Ping URL or IP<br/>
            <input type="text" id="ping_server" class="jq_val ping_test">
          </div>
          <div class="col-lg-4">
            Number of ping tests<br/>
            <input type="text" id="num_ping_tests" class="jq_val ping_test">
          </div>
		  <div class="col-lg-4">
            Ping test freq (2 to 60m)<br/>
            <input type="text" id="ping_test_freq" class="jq_val ping_test">
          </div>
        </div>
		<div class="row">
			<div class="col-lg-12">
				<small>note: Ping server can be IP or URL. No protocol (ie www.google.com instead of https://www.google.com)</small>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-10">
				<H3>** Speed test configuration **</H3>
			</div>
			<div class="col-lg-2 text-center">
				<BR />
				<label> Enabled <input type="checkbox" id="enable_speed_test"></label>
			</div>
		</div>
        <div class="row">
          <div class="col-lg-6">
			<a href="https://www.speedtest.net/speedtest-servers-static.php" target="_blank">Speed test server</a> (MO:1849 | NO: 11786)<br/>
            <input type="text" id="speed_test_server" class="jq_val speed_test">
          </div>
          <div class="col-lg-6">
            Speed test freq (5 to 60m)<br/>
            <input type="text" id="speed_test_freq" class="jq_val speed_test">
          </div>
        </div>
		
		<div class="row">
			<div class="col-lg-10">
				<H3>** Wifi channel test configuration **</H3>
			</div>
			<div class="col-lg-2 text-center">
				<BR />
				<label> Enabled <input type="checkbox" id="enable_channel_test"></label>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				If on wireless adaptor perform tests on the following channels
			</div>
		</div>
		<div class="row">
          <div class="col-lg-3">
            <label><input type="radio" name="channel_type_radio" value="both" class="channel_test"> 2.4 and 5GHz</label>
          </div>
          <div class="col-lg-3">
            <label><input type="radio" name="channel_type_radio" value="2only" class="channel_test"> 2.4GHz</label>
          </div>
          <div class="col-lg-2">
            <label><input type="radio" name="channel_type_radio" value="5only" class="channel_test"> 5GHz</label>
          </div>
		  <div class="col-lg-4">
            Channel test frequency<br/>
            <input type="text" id="channel_test_freq" class="jq_val channel_test">
          </div>
        </div>
		
		<div class="row">
			<div class="col-lg-12">
				<H3>** Other configuration options **</H3>
			</div>
		</div>
        <div class="row">
          <div class="col-lg-4">
            AP Name<br/>
            <input type="text" id="wifiap" class="jq_val">
          </div>
          <div class="col-lg-4">
            Wifi username<br/>
            <input type="text" id="wifiun" class="jq_val">
          </div>
          <div class="col-lg-4">
            Wifi password<br/>
            <input type="password" id="wifipw" class="jq_val">
          </div>
        </div>
		<div class="row">
			<div class="col-lg-12">
				<H3>** Notes **</H3>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 text-center">
				<small>Server connectivity test run every 1 minute. Internet connectivity test runs every 1 minute. New client connection simulation
				is done at the interval defined on the channel test frequency (NIC up, down, NIC up).</small>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <div class="text-center">
          <input type="hidden" id="probe_edit_config_id">
          <input type="hidden" id="probe_id">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="probe_config_save_button">Save</button>
		  <button type="button" class="btn btn-danger" id="probe_config_delete_button"><span class="glyphicon glyphicon-trash"></span></button>
          <div class="text-center"><span id="probe_config_message">&nbsp;</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal 1 - add new probe-->

<!-- Modal 3 - Probe data-->
<div class="modal fade" id="probe_details_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Probe tests</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <h4>Last ping test results</H4>
			<strong>Status:</strong> <span class="test_result" id="ping_test_status"></span> <br />
			<strong>Time:</strong> <span class="test_result" id="ping_time"></span> <br />
            <strong>Ping packets sent:</strong> <span class="test_result" id="packets_sent"></span> <br />
            <strong>Ping packets received:</strong> <span class="test_result" id="packets_received"></span> <br />
            <strong>Packet loss: </strong> <span class="test_result" id="packet_loss"></span><br />
			<strong>Packet latency:</strong> <span class="test_result" id="average_latency"></span> <br />
            <strong>Ping max time:</strong> <span class="test_result" id="max_time"></span> <br />
            <strong>Ping min time:</strong> <span class="test_result" id="min_time"></span> <br />
            <strong>Ping average time:</strong> <span class="test_result" id="average_time"></span><br />
            <strong>Ping server:</strong> <span class="test_result" id="ping_server_"></span> <br />

          </div>
          <div class="col-lg-6">
            <H4>Last speed test results</H4>
			<strong>Status:</strong> <span class="test_result" id="speed_test_status"></span> <br />
			<strong>Time:</strong> <span id="speed_time" class="test_result"></span><br />
            <strong>Download speed:</strong> <span id="speed_download_span" class="test_result"></span><br />
            <strong>Upload speed:</strong> <span id="speed_upload_span" class="test_result"></span><br />
            <strong>Server used:</strong> <span id="speed_server_span" class="test_result"></span><br />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="text-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div class="text-center"><span id="add_new_probe_message">&nbsp;</span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal 3 - probe data-->
