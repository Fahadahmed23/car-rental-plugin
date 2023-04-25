<?php 

add_shortcode('fah_booking_form', 'fah_booking_form');

function fah_booking_form($args = array()) {

	$allowed_steps = array('select-location', 'select-vehicle', 'add-passenger', 'review-booking','thank-you');

	$step = isset($_GET['step']) ? $_GET['step'] : '';

	if(!in_array($step, $allowed_steps)) {
		$step = 'select-location';
	}

	ob_start();
	if(isset($args['onepage']) && $args['onepage'] == 1) {
		include 'templates/booking-form-onepage.php';
	} else {
		include 'templates/booking-form.php';
	}
	$out = ob_get_clean();
	return $out;
}