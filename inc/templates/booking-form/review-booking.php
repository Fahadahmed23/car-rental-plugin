<?php 
		$booking = fah_get_booking();
		$situation = (isset($booking->passengers) ? 'Set' : 'Not Set');
		$no_of_passengers = $booking->vehicle->no_of_passengers;
		// getting vehicle
		$get_selected_vehicle = $booking->vehicle->id;
		$get_post_vehicle = get_post($get_selected_vehicle, true);
		$get_post_vehicle_meta = get_post_meta( $get_selected_vehicle, '' , true);
?>

<input type="hidden" name="review-situation" value="<?php echo $situation; ?>">
<!--  Getting Car Details -->
<input type="hidden" name="passenger_htype" value="<?php echo isset($no_of_passengers[0]) ? $no_of_passengers[0] : 'Not Set';  ?>" />
<input type="hidden" name="passenger_hvehicle_name" value="<?php echo isset($get_post_vehicle['post_title']) ?$get_post_vehicle['post_title'] : 'Not Set'; ?>" />
<input type="hidden" name="passenger_hbase_rate" value="<?php echo isset($get_post_vehicle_meta['base_rate'][0]) ? $get_post_vehicle_meta['base_rate'][0] : 'Not Set'; ?>" />
<form id="booking-review-form" action="<?php echo admin_url('admin-ajax.php') ?>" method="post" >
<div class="fah-booking-container fah-review-detail">
	<div class="fah-row">
		
		<span id="msg_review_err"></span>
		<span id="link-to-passenger"></span> 
		<div class="fah-col-quater">
			<?php fah_get_template_part('booking-form/sidebar') ?>
		</div>
		<div class="fah-col-quater3">
			<div class="fah-row fah-review-container">
				<div class="fah-col-full">
					<?php 
						$get_payment_methods = get_option('fah_general_field');
						if( $get_payment_methods != false){
							//var_dump($get_payment_methods["fah_payment_methods"]);
							if( isset($get_payment_methods["fah_payment_methods"]) ) { ?>

								<ul class="fah-payment-methods">
								<?php foreach($get_payment_methods["fah_payment_methods"] as $key => $value): 
										switch ($value) {
										    case "cash":
										         $img_src = plugins_url('car-rental-wp-plugin/assets/img/cash.jpg');
										        break;
										    case "paypal":
										        $img_src = plugins_url('car-rental-wp-plugin/assets/img/paypal.jpg');
										        break;
										    case "stripe":
										     	$img_src = plugins_url('car-rental-wp-plugin/assets/img/stripe.jpg');
										        break;
										    default:
										    	$img_src = plugins_url('car-rental-wp-plugin/assets/img/paypal.jpg');
										}

										?>
										<li>
											<label>
												<img src="<?php echo $img_src; ?>" /> 
												<input type="radio" name="payment_method" id="payment_method" value="<?php echo $value; ?>" /><?php echo ucfirst($value); ?>
											</label>
										</li>
									<?php endforeach; ?>
									<span id="message_err"></span>
									<input type="hidden" name="payment_method_info" value="empty" />
								</ul>
						<?php	}
						} ?>
					<label>Additional Notes</label>
                            <textarea class="fah-form-control" placeholder="Write Additional Notes" name="additional_notes" rows="60"></textarea>
				</div>
				<div class="fah-col-full fah-right">
					<button class="fah-btn fah-btn-primary" id="btn-review">Paynow</button>
				</div>
			</div>
		<!-- 	<div class="fah-row ">
                    <div class="fah-col-full">
                       <div class="fah-form-group">
                            
                        </div>
                    </div>
                  
    		</div> -->
		</div>
	</div>

</div>
<input type="hidden" name="paymentmethod_set_after" 
		value="<?php echo isset($booking->payment_method) ? $booking->payment_method : 'Not Set'; ?>" />
<input type="hidden" name="action" value="save_booking_info" />
<input type="hidden" name="step" value="<?php echo 'review-booking' ?>" />
</form>
