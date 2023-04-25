<?php
	   
	 $booking = fah_get_booking();
?>
<form id="ajaxcontactform" action="<?php echo admin_url('admin-ajax.php') ?>" method="post" >
<div class="fah-booking-container fah-location-detail">
	<div class="fah-row">
		<span id="message_err"></span>
		<div class="fah-col-half">
			<div class="fah-form-group">
				<label>Booking Type</label>
				<div class="fah-checkbox-group fah-booking-type">
					<label>
						<input type="radio" name="booking_data[booking_type]" id="booking_type" value="one way" checked="checked" /> One way
					</label>
					<label>
						<input type="radio" name="booking_data[booking_type]" id="booking_type" value="daily" /> Daily
					</label>
					<label>
						<input type="radio" name="booking_data[booking_type]" id="booking_type" value="return" /> Return
					</label>
				</div>
			</div>
			<div class="fah-form-group fah-booking-type-dependent">
				<div class="fah-row">
					<div class="fah-col-full">
						<label><?php _e('No. of Days') ?><span class="fah-req">*</span></label>
						<div class="fah-input">
							<select class="fah-form-control" name="booking_data[no_of_days]">
								<?php foreach(range(1,20) as $no_of_day): 
									if(isset($booking->no_of_days) && $booking->no_of_days == $no_of_day){ ?>
										<option value="<?php echo sprintf("%02d", $no_of_day) ?>" selected>
										<?php echo sprintf("%02d", $no_of_day) ?>
									</option>
								<?php }
								else{ ?>
										<option value="<?php echo sprintf("%02d", $no_of_day) ?>">
										<?php echo sprintf("%02d", $no_of_day) ?>
									</option>
									<?php }
									?>
									
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>

		<div class="fah-form-group fah-return-dependent">
			<div class="fah-row">
				<div class="fah-col-half">
					<div class="fah-form-group">
							<label>Return Time</label>
							<div class="fah-row">
								<div class="fah-col-full">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[return_time_hour]">
											<?php foreach(range(1, 24) as $hour): 
												if(isset($booking->return_time_hour) && ($booking->return_time_hour == $hour))
												{ ?>
												<option value="<?php echo $hour ?>" selected><?php echo sprintf("%02d", $hour) ?></option>
												<?php }else{ ?>
													<option value="<?php echo $hour ?>"><?php echo sprintf("%02d", $hour) ?></option>
												<?php } ?>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="fah-col-full">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[return_time_min]">
											<?php foreach(array("00", "15", "30", "45") as $min):
												if(isset($booking->return_time_min) && ($booking->return_time_min == $min))
												{ ?>
												<option value="<?php echo $min ?>" selected><?php echo $min ?></option>
											<?php }
											else{ ?>
												<option value="<?php echo $min ?>"><?php echo $min ?></option>
											<?php }?>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<span id="bookingtype_return_err"></span>
							</div>
					</div>
				</div>
			</div>
		</div>
			<!-- <div class="fah-form-group fah-return-dependent">
					<div class="fah-row">
						<div class="fah-col-full">
							<label><?php _e('Wating Time') ?><span class="fah-req">*</span></label>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[waiting_time_hour]">
										<?php foreach(range(1, 24) as $hour):
										if(isset($booking->waiting_time_hour) && $booking->waiting_time_hour == $hour){ ?>
											<option value="<?php echo $hour ?>" selected><?php echo sprintf("%02d", $hour) ?></option>
												<?php }else{ ?>
													<option value="<?php echo $hour ?>"><?php echo sprintf("%02d", $hour) ?></option>
												<?php }
												endforeach; ?>
										</select>
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[waiting_time_min]">
											<?php foreach(array("00", "15", "30", "45") as $min): 
											if(isset($booking->waiting_time_min) && $booking->waiting_time_min == $min){ ?>
													<option value="<?php echo $min ?>" selected><?php echo $min ?></option>
												<?php }else{ ?>
													<option value="<?php echo $min ?>"><?php echo $min ?></option>
												<?php }
												?>
												
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div> -->

			<div class="fah-form-group">
				<label>
					<input type="checkbox" name="booking_data[asap]" id="asap" checked="checked" /> As soon as possible
					<input type="hidden" name="booking_data[asapoff]" value="asap_on" />
				</label>
			</div>
			<div class="fah-row fah-asap-dependent">
				<div class="fah-col-half">
					<div class="fah-form-group">
						<label>Pickup Date</label>
						<input type="text" class="fah-datepicker fah-form-control" name="booking_data[pickup_date]" placeholder="Select Date" value="<?php echo isset($booking->pickup_date) ? $booking->pickup_date : null; ?>" />
						<span id="pickup_date_err"></span>
					</div>
				</div>
				<div class="fah-col-half">
					<div class="fah-form-group">
							<label>Pickup Time</label>
							<div class="fah-row">
								<div class="fah-col-full">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[pickup_time_hour]">
											<?php foreach(range(1, 24) as $hour): 
													if(isset($booking->pickup_time_hour) && ($booking->pickup_time_hour == $hour))
												{ ?>
												<option value="<?php echo $hour ?>" selected><?php echo sprintf("%02d", $hour) ?></option>
												<?php }else{ ?>
													<option value="<?php echo $hour ?>"><?php echo sprintf("%02d", $hour) ?></option>
												<?php } ?>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="fah-col-full">
									<div class="fah-input">
										<select class="fah-form-control" name="booking_data[pickup_time_min]">
											<?php foreach(array("00", "15", "30", "45") as $min):
												if(isset($booking->pickup_time_min) && ($booking->pickup_time_min == $min))
												{ ?>
												<option value="<?php echo $min ?>" selected><?php echo $min ?></option>
											<?php }
											else{ ?>
												<option value="<?php echo $min ?>"><?php echo $min ?></option>
											<?php }?>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							
							</div>
					</div>
				</div>
			</div>

			<div class="fah-form-group">
				<label>Pickup Location</label>
					<input type="text" name="booking_data[pickup_location]" id="fah-pickup_location" placeholder="Enter pickup location" class="fah-form-control" value="<?php echo isset($booking->pickup_location) ? $booking->pickup_location : null; ?>" />
					<span id="pickup_location_err"></span>
					<span id="pickup_location_coods_err"></span>
					<input type="hidden" name="booking_data[pickup_location_coods]" id="pickup_location_coods"  value="<?php echo isset($booking->pickup_location_coods) ? $booking->pickup_location_coods : null; ?>" />
			</div>

			<div class="fah-form-group">
				<label>Dropoff Location</label>
					<input type="text" placeholder="Enter Drop Off Location" name="booking_data[dropoff_location]" id="fah-dropoff_location" class="fah-form-control" value="<?php echo isset($booking->dropoff_location) ? $booking->dropoff_location : null; ?>" />
					<span id="dropoff_location_err"></span>
					<span id="dropoff_location_coods_err"></span>

					<input type="hidden" name="booking_data[dropoff_location_coods]" id="dropoff_location_coods"  value="<?php echo isset($booking->dropoff_location_coods) ? $booking->dropoff_location_coods : null; ?>" />
			</div>
		</div>
		<div class="fah-col-half" style="position: relative">
			<div class="fah-map-wrapper">
			<div class="fah-map-pin">
				<img src="<?php echo plugins_url('car-rental-wp-plugin/assets/img/pin.png'); ?>" />
			</div>
				<div id="fah-map"></div>
			</div>
			<button type="button" class="fah-btn fah-set-pickup">Set Pickup</button>
			<button type="button" class="fah-btn fah-set-dropoff">Set Drop-off</button>
			<label class="fah-checkbox">
				<input type="checkbox" id="choose-from-map" name="choose-from-map" /> Select location from map
			</label>
		</div>
	</div>

	<div class="fah-row">
		<div class="fah-col-full fah-right">
			<button class="fah-btn fah-btn-primary" id="nextbtn">Next</button>
		</div>
	</div>
</div>


<input type="hidden" name="action" value="save_booking_info" />
<input type="hidden" name="step" value="<?php echo 'select-location' ?>" />

</form>