	<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 text-center">
      <H1><span id="date_time"></span></H1>
      <a href="#">14th Floor</a> | <a href="#">Offices</a> | <a href="#">Service</a>
      <br/><span id="dashboard_error_message"></span>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-6">
      <div class="text-center">
        <H1>Network status</H1>
        <div id="canvas-holder" style="width:100%">
          <canvas id="chart-area"></canvas>
        </div>
        <script src="https://www.chartjs.org/samples/latest/utils.js"></script>
      </div>
    </div>

    <div class="col-lg-6 text-center">
      <H1>Details</H1>
      <div class="alert alert-success" role="alert">Monitoring <strong><span id="total_probes"></span></strong> probes in the 14th Floor group - <a href="#">View all</a></div>
      <div id="alarm_div">
        <div class="alert alert-danger" role="alert">
          <H1><span class="glyphicon glyphicon-alert" aria-hidden="true"></span></H1>
          <strong><span id="num_offline"></span></strong> probes in this group are offline - <a href="#">View</a>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 form-group text-center">

    </div>
  </div>
</div>
