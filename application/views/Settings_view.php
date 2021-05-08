<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<H3>Notifications</H3>
		</div>
	</div>
	
	<div class="form-group">
		<div class="row">
			<div class="col-lg-12">
				<div style="display:inline-block;">
					[ok] When probe offline for longer than
				</div>
				<div style="display:inline-block;">
					<select class="form-control" id="notification_offline_minutes">
						<option value="1">1 minute</option>
						<?php for($i = 0; $i <= 15; $i += 5):?>
							<?php if($i != 0):?>
								<option <?php if($settings && $settings->notification_offline_minutes == $i) echo 'selected'?> value="<?php echo $i;?>"><?php echo $i;?> minutes</option>
							<?php endif;?>
						<?php endfor;?>
					</select>
				</div>
				<div style="display:inline-block;">
					notify the following addresses
				</div>
				<div style="display:inline-block;">
					<input type="text" value="<?php if($settings) echo $settings->offline_addresses;?>" class="form-control" id="offline_addresses">
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:10px;">
			<div class="col-lg-12">
				<div style="display:inline-block;">
					[ok] Notify the following addresses when probe is back online
				</div>
				<div style="display:inline-block;">
					<input type="text" value="<?php if($settings) echo $settings->online_addresses;?>" class="form-control" id="online_addresses">
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:10px;">
			<div class="col-lg-12">
				<div style="display:inline-block;">
					[not yet] When upload speed test below
				</div>
				<div style="display:inline-block;">
					<select class="form-control">
						<option>5 Mbps</option>
					</select>
				</div>
				<div style="display:inline-block;">
					after 3 consecutive tests, notify the following addresses
				</div>
				<div style="display:inline-block;">
					<input type="text" value="" class="form-control">
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:10px;">
			<div class="col-lg-12">
				<div style="display:inline-block;">
					[not yet] When ping speed test below
				</div>
				<div style="display:inline-block;">
					<select class="form-control">
						<option>400ms</option>
					</select>
				</div>
				<div style="display:inline-block;">
					after 3 consecutive tests, notify the following addresses
				</div>
				<div style="display:inline-block;">
					<input type="text" value="" class="form-control">
				</div>
			</div>
		</div>
		<hr/>
		<div class="row text-center">
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary" id="save_settings_button">Save</button><br />
				<span id="save_settings_message">&nbsp;</span>
			</div>
		</div>
	</div>
	
</div>