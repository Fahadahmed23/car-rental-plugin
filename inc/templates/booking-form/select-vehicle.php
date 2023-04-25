<?php 
	$booking = fah_get_booking(); 
	global $post;
	$vehicles = get_posts(array('post_type' => 'fah_vehicle', 'posts_per_page' => -1));
?>
<input type="hidden" name="vehicles_empty" 
	value="<?php echo empty($vehicles) ? 'empty vehicles' : 'not empty vehicles'; ?>">
<input type="hidden" name="booking_htype" 
	value="<?php echo isset($booking->pickup_location_coods) ? 'Set' : 'Not Set';  ?>" />
<form id="booking-vehicle-form" action="<?php echo admin_url('admin-ajax.php') ?>" method="post" >
<div class="fah-booking-container fah-select-vehicle">
	<div class="fah-row">
		<span id="message_err"></span>
		<span id="msg_vehicle_err"></span>
		<span id="link-to-location"></span> 
		<div class="fah-col-quater">

			<?php fah_get_template_part('booking-form/sidebar') ?>
		</div>
		<div class="fah-col-quater3">
			<ul class="fah-vehicle-list">
				<?php foreach($vehicles as $post): setup_postdata($post); 
						$no_of_passengers = intval(get_post_meta(get_the_ID(), 'no_of_passengers', true));
						$rate_per_hour = intval(get_post_meta(get_the_ID(), 'rate_per_hour', true));
						$rate_per_km = intval(get_post_meta(get_the_ID(), 'rate_per_km', true));
						$vehicle_feature = get_post_meta(get_the_ID(), 'vehicle_feature', true);
						$base_rate = intval(get_post_meta(get_the_ID(), 'base_rate', true));
				 ?>
					<li class="fah-vehicle-item">
						<div class="fah-vehicle-item-inner">
							<div class="fah-vehicle-image" style="background-image: url(https://blipparcom-dev.s3.eu-west-1.amazonaws.com/files/my_files/Car%20reco%20--%20Jeep%20Cherokee--%20300x300.jpg)">
							</div>
							<div class="fah-vehicle-detail">
								<h3 class="fah-vehicle-title"><?php the_title() ?></h3>
								<div class="fah-vehicle-meta">
									<ul>
										<li><?php echo $no_of_passengers; ?> Passengers</li>
										<li>Free Wifi</li>
										<?php if(!empty($vehicle_feature)){ ?> 
										<li><?php 
										 $features_arr = array();
										foreach ($vehicle_feature as $key => $value) {
													//echo $value['vehicle_feature']." ,"; 
													$features_arr[] = $value['vehicle_feature'];
												}
												echo implode(",",$features_arr);
												 ?>
										</li>
										<?php }  ?>
									</ul>
								</div>
							</div>
							<div class="fah-vehicle-select-box">
								<div class="fah-vehicle-price">
									<span class="fah-vehicle-original-price">$<?php echo $base_rate; ?>/hour</span> <del>$10/hour</del>
								</div>
								<label class="fah-btn fah-btn-select-vehicle">
									<span class="fah-unselected-text">Select</span>
									<span class="fah-selected-text">Selected</span>
									<input type="radio" name="vehicle_id" id="vehicle_id" 
										value="<?php echo $post->ID; ?>" />
								</label>
							</div>
						</div>
						<div class="fah-vehicle-item-extra">
							<ul>
								<li>
									<input type="checkbox" name="extra[]" value="1" /> Child Seat
								</li>
								<li>
									<input type="checkbox" name="extra[]" value="1" /> Child Seat
								</li>
								<li>
									<input type="checkbox" name="extra[]" value="1" /> Child Seat
								</li>
							</ul>
						</div>
					</li>
				<?php endforeach; ?>
				<input type="hidden" name="vehicle_id_info" value="empty" />
			</ul>
		</div>
	</div>
	<div class="fah-row">
		<div class="fah-col-full fah-right">
			<button class="fah-btn fah-btn-primary" id="btn-vehicle">Next</button>
		</div>
	</div>
</div>
	<input type="hidden" name="vehicle_set_after" 
		value="<?php echo isset($booking->vehicle->id) ? $booking->vehicle->id : 'Not Set'; ?>" />
	<input type="hidden" name="action" value="save_booking_info" />
	<input type="hidden" name="step" value="<?php echo 'select-vehicle' ?>"/>
</form>