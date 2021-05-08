<div class="container-fluid">
	<div class="row form-group">
		<div class="col-lg-2">
			<select class="form-control" id="probe_id">
				<?php if($probes):?>
					<option value="0">Select probe...</option>
					<?php foreach($probes as $key => $val):?>
						<option value="<?php echo $key;?>"><?php echo $val;?></option>
					<?php endforeach;?>
				<?php else:?>
					<option value="-1">No probes on system yet</option>
				<?php endif;?>
			<select>
		</div>
		<div class="col-lg-2">
			<input type="text" placeholder="start date" class="form-control" id="start_date">
		</div>
		<div class="col-lg-2">
			<input type="text" placeholder="end date" class="form-control" id="end_date">
		</div>
		<div class="col-lg-6"> 
			<button type="submit" class="btn btn-primary" id="">Export ping data</button>
			<button type="submit" class="btn btn-primary" id="">Export speed data</button>
		</div>
	</div>
	
	<div class="row form-group">
		<div class="col-lg-2">
			&nbsp;
		</div>
		<div class="col-lg-2">
			<input type="text" placeholder="enter date" class="form-control" id="chart_date">
		</div>
		<div class="col-lg-2">
			&nbsp;
		</div>
		<div class="col-lg-6">
			<button type="submit" class="btn btn-primary" id="go_button">Show charts</button> <span id="chart_message"></span>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			Internet connectivity tests (every 1 minute)
			<div id="canvas-holder" style="width:100%;">
				<canvas id="chart-area" height="50"></canvas>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			Server connectivity tests (every 1 minute)
			<div id="canvas-holder" style="width:100%;">
				<canvas id="chart0-area" height="50"></canvas>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			Ping test results (min time/ max time/ average time)
			<div id="canvas-holder" style="width:100%;">
				<canvas id="chart1-area" height="50"></canvas>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			Ping test results (packet loss)
			<div id="canvas-holder" style="width:100%;">
				<canvas id="chart2-area" height="50"></canvas>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			Speed test results (every 5 minutes)
			<div id="canvas-holder" style="width:100%;">
				<canvas id="chart3-area" height="50"></canvas>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<P>&nbsp;</P><P>&nbsp;</P><P>&nbsp;</P>
		</div>
	</div>
	
</div>