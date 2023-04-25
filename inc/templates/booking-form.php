<?php 
	
if(isset($step) && $step =="thank-you"){
		fah_get_template_part('booking-form/' . $step);
	}
else{ ?>
	<div class="fah-booking-section">
		<div class="fah-booking-container fah-steps-container">
			<div class="fah-row">
				<div class="fah-col-full">
					<ul class="fah-steps">
						<li>Location</li>
						<li>Select Car</li>
						<li>Passenger Information</li>
						<li>Review</li>
					</ul>
				</div>
			</div>

			<div class="fah-row">
				<div class="fah-col-full">
					<p class="fah-step-description">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
				</div>
			</div>
		</div>
		<script>
	    	AJAXURL = '<?php echo admin_url('admin-ajax.php') ?>';
		</script>
		<?php 
			fah_get_template_part('booking-form/' . $step);
		?>
	</div>
<?php } ?>


