<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
class FAH_Ajax_Actions {

	public function __construct()
	{
		add_action('wp_ajax_fah_create_booking', array($this, 'create_booking'));
		add_action('wp_ajax_nopriv_fah_create_booking', array($this, 'create_booking'));

		add_action('wp_ajax_save_booking_info', array($this, 'save_booking_info'));
		add_action('wp_ajax_nopriv_save_booking_info', array($this, 'save_booking_info'));

		add_action('wp_ajax_save_all_data', array($this, 'save_all_data'));
		add_action('wp_ajax_nopriv_save_all_data', array($this, 'save_all_data'));

		add_action('wp_ajax_delete_passenger_info', array($this, 'delete_passenger_info'));
		add_action('wp_ajax_nopriv_delete_passenger_info', array($this, 'delete_passenger_info'));

		add_action('wp_ajax_clean_all_data', array($this, 'clean_all_data'));
		add_action('wp_ajax_nopriv_clean_all_data', array($this, 'clean_all_data'));

		add_action('wp_ajax_add_pickup_dropup_location', array($this, 'add_pickup_dropup_location'));
		add_action('wp_ajax_nopriv_add_pickup_dropup_location', array($this, 'add_pickup_dropup_location'));

		add_action('wp_ajax_fah_calculate_price', array($this, 'calculate_price'));
		add_action('wp_ajax_nopriv_fah_calculate_price', array($this, 'calculate_price'));	

		add_action('wp_ajax_fah_review', array($this, 'review'));
		add_action('wp_ajax_nopriv_review', array($this, 'review'));

		add_action('wp_ajax_fah_checkout', array($this, 'checkout'));
		add_action('wp_ajax_nopriv_checkout', array($this, 'checkout'));

		add_action('wp_aja_fah_delete_passenger', array($this, 'delete_passenger'));
		add_action('wp_ajax_nopriv_fah_delete_passenger', array($this, 'delete_passenger'));
	}
	
	public function clean_all_data(){
			$booking = fah_get_booking();
			$response_parameters = array();
			unset($_SESSION['FAH_BOOKING']);
			if(isset($_SESSION['FAH_BOOKING']) && !empty($_SESSION['FAH_BOOKING'])) {
					$response_parameters = array(
						'status' => false,
						'message' => '',
						'errors' => $booking->errors()
				);
				
			}else {
				$response_parameters = array(
				'status' => true,
				'message' => 'Finally Booking Added',
				);
			}

		ob_start();
		header('Content-Type: application/json');
		echo json_encode($response_parameters);
		wp_die();
	}
	public function save_all_data(){
		$booking = fah_get_booking();
		$booking->after_setting_data();
		$checkout = $this->checkout();

		wp_die();
	}

	public function add_pickup_dropup_location(){
		
		$location_name = $_REQUEST['location_name'];
		$location_type = $_REQUEST['location_type'];
		$booking = fah_get_booking();
		if($location_type == 'pickup_location_name'){
			$booking->pickup_location = $location_name;
			$booking_location = $booking->pickup_location;
		}
		elseif($location_type == 'dropup_location_name') {
			$booking->dropoff_location = $location_name;
			$booking_location = $booking->dropoff_location;
		}
		echo $booking_location;
		wp_die();
	}
	public function delete_passenger_info() {
 		 
 		$index_no = $_REQUEST['index_no'];
 		$booking = fah_get_booking();
 		$booking->delete_passenger($index_no);
 		echo "done unset";
 		wp_die();
	}

	public function save_booking_info()
	{
		$booking = fah_get_booking();
		$step = isset($_POST['step']) ? $_POST['step'] : 'select-location';
		
		$response = array('error' => true, 'message' => __('Invalid Step'));
		switch($step) {
			case 'select-location':
				$response = $this->save_location_data($booking);
				break;

			case 'select-vehicle':
				$response = $this->save_vehicle_data($booking);
				break;

			case 'add-passenger':
				$response = $this->save_passenger_data($booking);
				break;
			case 'review-booking':
				$response = $this->save_review_data($booking);
				break;
		}
		return $this->response_json($response);
	}
	protected function save_review_data($booking)
	{
	/**
		 * saving other booking information
		 */
		$payment_method_info = fah_sanitize_text_field($_POST, 'payment_method_info');
		$additional_notes = fah_sanitize_text_field($_POST,'additional_notes');
		$errors = array();
		$new_booking_data = array();
		if( $payment_method_info == "empty" ) {
			 $errors['payment_method'] = 'Kindly , Select Payment Method';	
		}else{
			$new_booking_data['payment_method'] = fah_sanitize_text_field($_POST, 'payment_method_info');
		}

		if(!empty($additional_notes)){
			$new_booking_data['additional_notes'] = $additional_notes;
		}
		$booking->set_data($new_booking_data);
		
		if(count($errors) > 0) {
			$booking = fah_get_booking();
			return array(
				'error' => true,
				'errors' => $errors,
				'booking_data' => $booking, // in the end , i will remove it (FAHAD AHMED)
				'message' => __('Validation failed. One or more field contain errors')
			);
		}
		return array(
			'error' => false
		);
	}
	protected function save_location_data($booking)
	{
		/**
		 * saving other booking information
		 */

		$errors = array();
		if(isset($_POST['booking_data']) && is_array($_POST['booking_data'])) {
			$booking_data = $_POST['booking_data'];
			$new_booking_data = array();
			$booking_type = $booking_data['booking_type'];

			if($booking_type == 'return')
			{
				if($booking_data['asapoff'] == 'asap_off')
				{

					$errors['bookingtype_return'] = 'Kindly select Pickup Date & Time for returning a car';
				}
				elseif($booking_data['asapoff'] == 'asap_on'){
					if(empty($booking_data['pickup_date'])){
						$errors['pickup_date'] = 'Pick Up date is required';
					}
					else{
						$new_booking_data['pickup_date'] = sanitize_text_field($booking_data['pickup_date']);
					}
				}
			}
			if($booking_type == 'one way' || $booking_type == 'daily'){
				if($booking_data['asapoff'] == 'asap_on'){

					if(empty($booking_data['pickup_date'])){
						$errors['pickup_date'] = 'Pick Up date is required';
					}
					else{
					$new_booking_data['pickup_date'] = sanitize_text_field($booking_data['pickup_date']);
					}
				}
			}
			foreach ($booking_data as $key => $value) {
				if($key == 'pickup_location' && trim($value) === '') {
					$errors[$key] = 'Pickup location is required';
				}

				if($key == 'pickup_location_coods' && trim($value) === '') {
					$errors[$key] = 'Pickup location coords is required';
				}

				if($key == 'dropoff_location' && trim($value) === '') {
					$errors[$key] = 'Dropoff location is required';
				}

				if($key == 'dropoff_location_coods' && trim($value) === '') {
					$errors[$key] = 'Dropoff location coords is required';
				}
 
				$new_booking_data[$key] = sanitize_text_field($value);
			}
			$booking->set_data($new_booking_data);
		}
		if(count($errors) > 0) {
			$booking = fah_get_booking();
			return array(
				'error' => true,
				'errors' => $errors,
				'booking_data' => $booking, // in the end , i will remove it (FAHAD AHMED)
				'message' => __('Validation failed. One or more field contain errors')
			);
		}
		return array(
			'error' => false
		);
	}
	protected function save_vehicle_data($booking)
	{
		/**
		 * adding vehicle data
		 *
		 */
		// please validate vehicle here ...
		$vehicle_id_info = fah_sanitize_text_field($_POST, 'vehicle_id_info');
		  if(isset($booking->vehicle->id)){
		  		if($booking->vehicle->id != $vehicle_id_info){
		  			unset($booking->passenger); 
		  		}
		  }
		$errors = array();
		
		if( $vehicle_id_info == "empty" ) {
			$errors['vehicle_id_info'] = 'Kindly select a vehicle to proceed further';	
		}
		$vehicle_id = fah_sanitize_text_field($_POST, 'vehicle_id');
		if( $vehicle_id ) {
			$vehicle = new FAH_Vehicle($vehicle_id);
			if($vehicle->id) {
				$booking->set_vehicle($vehicle);
			}
		}
		
		if(count($errors) > 0){
			return array(
				'error' => true,
				'errors' => $errors,
				'message' => __('Validation failed. One or more field contain errors')
			);
		}
		return array(
			'error' => false
		);

	}

	protected function save_passenger_data($booking)
	{
		/**
		 * saving passenger data
		 */
		if(!empty($booking->passenger))
		{
			unset($booking->passenger);
		}

		$errors = array();
		if(isset($_POST['passenger']) && is_array($_POST['passenger'])) 
		{

			$passengers = $_POST['passenger'];
			$booking->passengers = [];

			foreach ($passengers as $passenger_data) 
			{

				$new_passenger_data = array(
					'first_name' => fah_sanitize_text_field($passenger_data, 'first_name'),
					'last_name' => fah_sanitize_text_field($passenger_data, 'last_name'),
					'email' => fah_sanitize_text_field($passenger_data, 'email'),
					'phone' => fah_sanitize_text_field($passenger_data, 'phone'),
					'mobile' => fah_sanitize_text_field($passenger_data, 'mobile'), 
					'address_line_1' => fah_sanitize_text_field($passenger_data, 'address_line_1'),
					'address_line_2' => fah_sanitize_text_field($passenger_data, 'address_line_2'),
					'city' => fah_sanitize_text_field($passenger_data, 'city'),
					'state' => fah_sanitize_text_field($passenger_data, 'state'),
					'country' => fah_sanitize_text_field($passenger_data, 'country'),
					'company' => fah_sanitize_text_field($passenger_data, 'company'),
				);

				$passenger = new FAH_Passenger;
				$passenger->set_data($new_passenger_data);
				$booking->add_passenger($passenger);

			}
		}
		return array(
			'error' => false
		);
	}

	public function checkout()
	{
		$booking = fah_get_booking();
		
		if($booking->validate()) {
			
			$booking->save();

			$response_parameters = array();

			if($booking->payment_method == 'stripe') {

			} else if($booking->payment_method == 'paypal') {

			} else { // payment method is cash

			}

			$response_parameters = array(
				'status' => true,
				'message' => 'Finally Booking Added',
			);
			
		} else {
			$response_parameters = array(
				'status' => false,
				'message' => '',
				'errors' => $booking->errors()
			);
		}

		ob_start();

		header('Content-Type: application/json');
		echo json_encode($response_parameters);
		wp_die();

	}

	// public function create_booking()
	// {
	// 	header('Content-Type: application/json');

	// 	$has_error = false;
	// 	$error_message = "";

	// 	$booking_type = sanitize_text_field($_POST['booking_type']);
	// 	$password = sanitize_text_field($_POST['password']);
 //        $vehicle_id = sanitize_text_field($_POST['vehicle_id']);
	// 	$qty_vehicle = (int) sanitize_text_field($_POST['fah_vehicle_qty']);

	// 	$qty_vehicle = !$qty_vehicle || $qty_vehicle < 1 ? 1 : $qty_vehicle;

	// 	//$no_of_vehicles  = 	sanitize_text_field($_POST['no_of_vehicles']);
	// 	$no_of_days = sanitize_text_field($_POST['no_of_days']);
	// 	$waiting_time_hour = sanitize_text_field($_POST['waiting_time_hour']);
	// 	$waiting_time_min = sanitize_text_field($_POST['waiting_time_min']);
	// 	$pickup_date = sanitize_text_field($_POST['pickup_date']);
	// 	$pickup_time_hour = sanitize_text_field($_POST['pickup_time_hour']);
	// 	$pickup_time_min = sanitize_text_field($_POST['pickup_time_min']);
	// 	$pickup_location = sanitize_text_field($_POST['pickup_location']);
	// 	$pickup_location_coods = sanitize_text_field($_POST['pickup_location_coods']);
	// 	$dropoff_location = sanitize_text_field($_POST['dropoff_location']);
	// 	$dropoff_location_coods = sanitize_text_field($_POST['dropoff_location_coods']);


	// 	$first_name = sanitize_text_field($_POST['first_name']);
	// 	$last_name = sanitize_text_field($_POST['last_name']);
	// 	$company = sanitize_text_field($_POST['company']);
	// 	$email = sanitize_text_field($_POST['email']);
	// 	$city = sanitize_text_field($_POST['city']);
	// 	$county = sanitize_text_field($_POST['county']);
	// 	$phone = sanitize_text_field($_POST['phone']);
	// 	$mobile = sanitize_text_field($_POST['mobile']);
	// 	$additional_notes = sanitize_text_field($_POST['additional_notes']);


	// 	if(!$vehicle_id || !$pickup_location_coods || !$dropoff_location_coods) {

	// 		$has_error = true;
	// 		$error_message = "yar ! Error aa rha hai..";
	// 	}

	// 	if($has_error) {
	// 		ob_start();
	// 		include __DIR__ . '/inc/templates/total-bottom.php';
	// 		$html = ob_get_clean();
	// 		echo json_encode(array(
	// 			"status" => 0,
	// 			"html" => $html
	// 		));
	// 		wp_die();
	// 	}

	// 	$params = array(
	// 		"origins" => $pickup_location_coods,
	// 		"destinations" => $dropoff_location_coods,
	// 		"key" => CRF_GOOGLE_API_KEY,
	// 		"units" => "imperial"
	// 	);

	// 	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?" . http_build_query($params, '', '&');

	// 	$google_json_response = file_get_contents($url);

	// 	if(!$google_json_response) {
	// 		$has_error = true;
	// 		$error_message = "Failed to fetch response from given url.";

	// 	}

	// 	$google_response = json_decode($google_json_response, true);


	// 	$orig_distance = isset($google_response['rows'][0]['elements'][0]['distance']['value']) ? 
	// 					$google_response['rows'][0]['elements'][0]['distance']['value'] : 
	// 					null;
	// 	$orig_duration = isset($google_response['rows'][0]['elements'][0]['duration']['value']) ? 
	// 					$google_response['rows'][0]['elements'][0]['duration']['value'] : 
	// 					null;

	// 	$distance = $orig_distance;
	// 	$duration = $orig_duration;

	// 	if($orig_distance == null || $orig_duration == null) {
	// 		$has_error = true;
	// 		$error_message = "Invalid location selected";
	// 	}

	// 	if($has_error) {
	// 		ob_start();
	// 		include __DIR__ . '/inc/templates/total-bottom.php';
	// 		$html = ob_get_clean();
	// 		echo json_encode(array(
	// 			"status" => 0,
	// 			"html" => $html
	// 		));
	// 		wp_die();
	// 	}

	// 	$rate_per_hour = (int) get_post_meta($vehicle_id, 'rate_per_hour', true);
	// 	if($rate_per_hour == 0) {
	// 		$rate_per_hour = 1;
	// 	}

	// 	$rate_per_km = (int) get_post_meta($vehicle_id, 'rate_per_km', true);
	// 	if($rate_per_km == 0) {
	// 		$rate_per_km = 1;
	// 	}

	// 	$base_rate = (int) get_post_meta($vehicle_id, 'base_rate', true);
	// 	if($base_rate == 0) {
	// 		$base_rate = 1;
	// 	}

	// 	$min_fare = (int) get_post_meta($vehicle_id, 'min_fare', true);
	// 	if($min_fare == 0) {
	// 		$min_fare = 1;
	// 	}

	// 	$duration = ceil($duration / 60 / 60);

	// 	if($booking_type == "return") {
	// 		$waiting_time_hour = $waiting_time_hour + ($waiting_time_min / 60);
	// 		$duration += ceil($waiting_time_hour / 2);
	// 	}

	// 	$fare = $base_rate;
	// 	$fare += $rate_per_hour * ($duration);
	// 	$fare += $rate_per_km * (ceil($distance * 0.001));

	// 	if($fare < $min_fare) {
	// 		$fare = $min_fare;
	// 	}

	// 	if ($booking_type == "daily") {
	// 		$fare *= $no_of_days;
	// 	} else if($booking_type == "return") {
	// 		$fare *= 2;
	// 	}

	// 	$fare *= $qty_vehicle;
	// 	$has_error = false;

	// 	if(isset($_GET['estimate']) && $_GET['estimate'] == 1) {

	// 		ob_start();
	// 		include __DIR__ . '/inc/templates/total-bottom.php';
	// 		$html = ob_get_clean();

	//         $map_image_params = [
	//             "key" => "AIzaSyBcP29rWZ2RbDnsDHCh7eTSCwrwJPNIS4o",
	//             "center" => $pickup_location_coods . "," . $dropoff_location_coods,
	//             "maptype" => "roadmap",
	//             "size" => '600x400',
	//             "zoom" => 13,
	//             "markers" => 
	//                 "color:red|label:P|" . $pickup_location_coods .
	//                 "color:green|label:D|" . $dropoff_location_coods
	//         ];

	//         $map_image_url = "https://maps.googleapis.com/maps/api/staticmap?" . http_build_query($map_image_params, '', '&');

	//         $booking = (object) array(
	//         	'booking_type' => $booking_type,
	//         	'vehicle' => get_post($vehicle_id),
	//         	'vehicle_id' => $vehicle_id,
	//         	'no_of_days' => $no_of_days,
	//         	'waiting_time_hour' => $waiting_time_hour,
	//         	'pickup_date' => $pickup_date,
	//         	'waiting_time_min' => $waiting_time_min,
	//         	'pickup_time_hour' => $pickup_time_hour,
	//         	'pickup_time_min' => $pickup_time_min,
	//         	'pickup_location' => $pickup_location,
	//         	'pickup_location_coods' => $pickup_location_coods,
	//         	'dropoff_location' => $dropoff_location,
	//         	'dropoff_location_coods' => $dropoff_location_coods,
	//         	'first_name' => $first_name,
	//         	'last_name' => $last_name,
	//         	'email' => $email,
	//         	'phone' => $phone,
	//         	'mobile' => $mobile,
	//         	'city' => $city,
	//         	'county' => $county,
	//         	'additional_notes' => $additional_notes,
	//         	'map_image_url' => $map_image_url,
	//         	'fare' => $fare,
 //                'is_admin' => false
	//         );
	        
	//         ob_start();
	// 		include __DIR__ . '/inc/templates/review.php';
	// 		$review_html = ob_get_clean();

	// 		echo json_encode(array(
	// 			"status" => 1,
	// 			"html" => $html,
	// 			"review_html" => $review_html
	// 		));

	// 	} else {

	// 		$booking_id = wp_insert_post(array('post_type' => 'booking', 'post_title' => $first_name . ' ' . $last_name));

	// 		update_post_meta($booking_id, 'first_name', $first_name);
	// 		update_post_meta($booking_id, 'email', $email);
	// 		update_post_meta($booking_id, 'last_name', $last_name);
	// 		update_post_meta($booking_id, 'booking_type', $booking_type);
	// 		update_post_meta($booking_id, 'vehicle_id', $vehicle_id);
	// 		update_post_meta($booking_id, 'no_of_days', $no_of_days);
	// 		update_post_meta($booking_id, 'waiting_time_hour', $waiting_time_hour);
	// 		update_post_meta($booking_id, 'waiting_time_min', $waiting_time_min);
	// 		update_post_meta($booking_id, 'pickup_date', $pickup_date);
	// 		update_post_meta($booking_id, 'pickup_time_hour', $pickup_time_hour);
	// 		update_post_meta($booking_id, 'pickup_location', $pickup_location);
	// 		update_post_meta($booking_id, 'pickup_location_coods', $pickup_location_coods);
	// 		update_post_meta($booking_id, 'dropoff_location', $dropoff_location);
	// 		update_post_meta($booking_id, 'dropoff_location_coods', $dropoff_location_coods);
	// 		update_post_meta($booking_id, 'phone', $phone);
	// 		update_post_meta($booking_id, 'mobile', $mobile);
	// 		update_post_meta($booking_id, 'city', $city);
	// 		update_post_meta($booking_id, 'county', $county);
	// 		update_post_meta($booking_id, 'additional_notes', $additional_notes);
	// 		update_post_meta($booking_id, 'fare', $fare);
	// 		update_post_meta($booking_id, 'orig_distance', $orig_distance);
	// 		update_post_meta($booking_id, 'orig_duration', $orig_duration);
	// 		update_post_meta($booking_id, 'min_fare', $min_fare);
	// 		update_post_meta($booking_id, 'rate_per_km', $rate_per_km);
	// 		update_post_meta($booking_id, 'rate_per_hour', $rate_per_hour);
	// 		update_post_meta($booking_id, 'payment_status', 'pending');
            
 //            $user_id = null;
            
 //            if(is_user_logged_in()) {
 //                $current_user = wp_get_current_user();
	// 		    $user_id = $current_user->ID;
 //            }
            
 //            if(isset($_POST['register']) && $_POST['register'] == 1) {
 //                $user_id = wp_create_user( uniqid(), $password, $email );
 //            }
            
 //            update_post_meta($booking_id, 'user_id', $user_id);
            

	// 		$paypalEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
 //            $paypalParams['business']       = '';
 //            $paypalParams['cmd']            = '_xclick';
 //            $paypalParams['amount']			= $fare;
 //            $paypalParams['item_name']		= 'Vehicle Booking';
 //            $paypalParams['currency_code']  = 'EUR'; 
 //            $redirectUrl = $paypalEndpoint . '?' . http_build_query($paypalParams, '', '&');
		
 //            echo json_encode(array(
 //            	"status" => 1,
 //            	"redirect_url" => $redirectUrl
 //            ));
	// 	}

	// 	wp_die();

	// }	
	protected function response_json($data)
	{
		header('Content-Type: application/json');
		echo json_encode($data);
		wp_die();
	}
}

new FAH_Ajax_Actions;