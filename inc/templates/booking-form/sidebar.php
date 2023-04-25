<?php
	$booking = fah_get_booking(); 
	$no_of_passengers = $booking->vehicle->no_of_passengers;
	$sit_location = isset($booking->pickup_location) ? 'Set' : 'Not Set';
    $sit_passengers = isset($no_of_passengers[0]) ? 'Set' : 'Not Set'; 
    // getting vehicle
	$get_selected_vehicle = $booking->vehicle->id;
	$get_post_vehicle = get_post($get_selected_vehicle, true);
	$get_post_vehicle_meta = get_post_meta( $get_selected_vehicle, '' , true);

 if($sit_location == 'Set'){ ?>
<div class="fah-summary-item">
	<h3>Location Detail <a href="">Edit</a></h3>
	<div class="fah-summary-item-detail">
		<ul>
			<li><strong>Pickup Type:</strong><?php echo $booking->booking_type;   ?></li>
			<li><strong>Pickup Location:</strong><?php echo $booking->pickup_location; ?></li>
			<li><strong>Dropoff Location:</strong><?php echo $booking->dropoff_location; ?></li>
			<?php
			if(!empty($booking->pickup_date))
			{ ?>
			<li><strong>Pickup Time:</strong><?php echo $booking->pickup_date." ".$booking->pickup_time_hour.":".$booking->pickup_time_min; ?></li>
			<?php } ?>

		</ul>
	</div>
</div>
<?php 
}
if($sit_passengers == 'Set'){  
	$post_title = isset($get_post_vehicle['post_title']) ?$get_post_vehicle['post_title'] : 'Not Set'; 
	$post_baserate = isset($get_post_vehicle_meta['base_rate'][0]) ? $get_post_vehicle_meta['base_rate'][0] : 'Not Set'; 
	?>
<div class="fah-vehicle-detail-items">
<div class="fah-summary-item">
	<h3>Vehicle Detail <a href="">Edit</a></h3>
	<div class="fah-summary-item-detail">
		<div class="fah-summary-item-card">
			<img src="https://blipparcom-dev.s3.eu-west-1.amazonaws.com/files/my_files/Car%20reco%20--%20Jeep%20Cherokee--%20300x300.jpg" />
			<div class="fah-summary-item-card-detail">
				<h3 class="fah-vehicle-title" id="fah_vehicle_title"><?php echo $post_title; ?></h3>
				<div class="fah-vehicle-price">
					<span class="fah-vehicle-original-price" id="fah_vehicle_brate"><?php echo "$".$post_baserate."/hour" ?></span> <del>$10/hour</del>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php } ?>

<?php $situation = (isset($booking->passenger) ? 'Set' : 'Not Set');
	if(isset($situation) && $situation == 'Set'){ ?>
	<div class="fah-summary-item">
		<h3>Passenger Information <a href="">Edit</a></h3>
		<div class="fah-summary-item-detail">
		<?php 
		foreach ($booking->passenger as $key => $value) { ?>
		<ul>
			<li>
				<?php if ( !empty($value->first_name) && !empty($value->last_name) ) { ?>
						    <strong><?php echo $value->first_name." ".$value->last_name.": "; ?></strong>
			<?php	} ?>
			</li>
			<li><?php if ( !empty($value->email) ) { 
						echo $value->email;
						 }?>
			</li>
			<li><?php if ( !empty($value->phone) ) { 
						echo $value->phone;
						} ?>
			</li>
		</ul>
		<br/>
			<?php } ?>
		
		</div>
	</div>
	<?php }

 if(isset($nana)){ ?>
<div class="fah-summary-item fah-summary-item-total">
	<h3>Total</h3>
	<div class="fah-summary-item-detail">
		<ul>
			<li>
				<div class="fah-row">
					<div class="fah-col-half">Subtotal</div>
					<div class="fah-col-half fah-right"><strong>$100</strong></div>
				</div>
			</li>
			<li>
				<div class="fah-row">
					<div class="fah-col-half">Tax</div>
					<div class="fah-col-half fah-right"><strong>$10</strong></div>
				</div>
			</li>
			<li>
				<div class="fah-row">
					<div class="fah-col-half">Grandtotal</div>
					<div class="fah-col-half fah-right"><strong>$1110</strong></div>
				</div>
			</li>
		</ul>
	</div>
</div>
 
<?php } ?>