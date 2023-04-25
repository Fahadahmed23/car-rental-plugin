<?php

function fah_init_session() {
	if(!session_id()) {
		session_start();
	}
}

function fah_get_booking() {
	if(isset($_SESSION['FAH_BOOKING']) && $_SESSION['FAH_BOOKING'] instanceof FAH_Booking) {
		return $_SESSION['FAH_BOOKING'];
	}

	$booking = new FAH_Booking();
	$_SESSION['FAH_BOOKING'] = $booking;
	return $_SESSION['FAH_BOOKING'];
}

function fah_sanitize_text_field($array, $key, $default = null) {
	return isset($array[$key]) ? sanitize_text_field($array[$key]) : $default;
}

function fah_haversine_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
	// convert from degrees to radians
	// thanks to https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);
	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	return $angle * $earthRadius;
}

function fah_get_template_part($template_path) {
	if (locate_template( array( $template_path . '.php' ) ) != '') {
		get_template_part($template_path);
	} else {
		include CRF_PLUGIN_DIR . '/inc/templates/' . $template_path . '.php';
	}
}


