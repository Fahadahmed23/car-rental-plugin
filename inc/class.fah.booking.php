<?php

class FAH_Booking extends FAH_Model {

	public $vehicle = null;
	public $passengers = array();
	public $pickup_location;
	public $dropoff_location;
	public $pickup_location_coods;
	public $dropoff_location_coods;
	public $booking_type = 'one_way';
	public $no_of_hours;
	public $no_of_days;

	public $pickup_date;
	public $pickup_time_hour;
	public $pickup_time_min;
	public $pickup_time;

	public $dropoff_time;
	
	public $return_date;
	public $return_time_hour;
	public $return_time_min;
	public $return_time;

	public $waiting_time_hour;
	public $waiting_time_min;
	//public $waiting_time;

	public $tax;
	public $fare;
	public $total_fare;
	public $payment_method;
	public $payment_status;
	public $additional_notes;
	public $id;
	public $post;
	
	protected $post_type = 'fah_booking';
	protected $skip_properties = array('passengers', 'vehicle');


	public function set_vehicle(FAH_Vehicle $vehicle)
	{
		$this->vehicle = $vehicle;
	}

	public function add_passenger(FAH_Passenger $passenger)
	{
		$this->passengers[] = $passenger;
	}

	public function delete_passenger($index)
	{
		unset($this->passengers[$index]);
		$this->passengers = array_values($this->passengers);
	}

	public function errors()
	{
		return array();
	}

	public function validate($data = array())
	{
		return true;
	}

	public function after_setting_data()
	{	
		$this->calculate_distance_duration();
		$this->set_no_of_hours();
		$this->calculate_fare();
	}


	public function set_user()
	{
		$this->user_id = wp_get_current_user()->ID !== 0 ? wp_get_current_user()->ID : null;
	}

	public function set_no_of_hours()
	{
		switch ($this->booking_type) {
			case 'return':

				$pickup_time = strtotime($this->pickup_date);
				$pickup_time_hour = $this->pickup_time_hour;
				$pickup_time_min = $this->pickup_time_min;

				//$return_date = strtotime("September 23, 2018");
				$return_time_hour = $this->return_time_hour;
				$return_time_min = $this->return_time_min;
				$pickupdate = date('Y/m/d', $pickup_time);

				$pickupdate_full = $pickupdate." ".$pickup_time_hour.":".$pickup_time_min;
				$returndate_full = $pickupdate." ".$return_time_hour.":".$return_time_min;
				$this->pickup_time = $pickupdate_full;
				$this->return_time = $returndate_full;
				$pickup_time = new Datetime($this->pickup_time);
				$return_time = new Datetime($this->return_time);
				$diff = $pickup_time->diff($return_time);
				$this->no_of_hours = $diff->h;
				break;

			case 'daily':
				$this->no_of_hours = $this->no_of_hours * $this->no_of_days;
				break;
		}
	}

	public function calculate_fare()
	{
		//$this->fare = get_option('fah_min_fare');
		$fah_get_options = get_option('fah_general_field');
		$this->fare= $fah_get_options['fah_min_fare'];
		$this->tax= $fah_get_options['fah_tax'];
		//$this->fare = 10; // hardcoded
		//$this->tax = get_option('fah_tax');
		//$this->tax = 20; // hardecoded

		if($this->vehicle && $this->vehicle->id) {
			$this->fare = $this->vehicle->base_rate[0] * $this->no_of_hours;
			if($this->fare < $this->vehicle->min_fare[0]) {
				$this->fare = $this->vehicle->min_fare[0];
			}
		}
		//var_dump($this->fare, $this->tax);

		$this->total_fare = $this->fare + $this->tax;
	}

	public function save($data = array())
	{
		$booking_id = parent::save($data);
		
		foreach ($this->passengers as $passenger) {
			$passenger_id = $passenger->save(array(
				'post_parent' => $booking_id
			));
		}

		return $this;
		//$this->passengers_info = $this->passenger;
	}

	public function calculate_distance_duration()
	{
		/*$params = array(
			"origins" => $this->pickup_location_coods,
			"destinations" => $this->dropoff_location_coods,
			"key" => get_option('fah_google_map_api'),
			"units" => "imperial"
		);

		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?" . http_build_query($params, '', '&');

		$google_json_response = file_get_contents($url);

		if(!$google_json_response) {
			return;
		}

		$google_response = json_decode($google_json_response, true);


		$orig_distance = isset($google_response['rows'][0]['elements'][0]['distance']['value']) ? 
						$google_response['rows'][0]['elements'][0]['distance']['value'] : 
						null;
		$orig_duration = isset($google_response['rows'][0]['elements'][0]['duration']['value']) ? 
						$google_response['rows'][0]['elements'][0]['duration']['value'] : 
						null;

		$distance = $orig_distance;
		$duration = $orig_duration;

		if(!$distance || !$duration) {
			return;
		}*/

		$this->no_of_hours = rand(1,10); //;ceil($duration / 60 / 60);
		$this->total_distance = rand(5,20); //$distance;
	}

}