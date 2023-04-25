<?php

class FAH_Passenger extends FAH_Model {

	public $first_name;

	public $last_name;

	public $email;

	public $company;

	public $phone;

	public $mobile;

	public $country;

	public $state;

	public $address_line_1;

	public $address_line_2;

	public $city;

	public $booking_id;

	protected $post_type = 'fah_passenger';
	
}