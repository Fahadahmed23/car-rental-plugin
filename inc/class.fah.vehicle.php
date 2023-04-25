<?php

class FAH_Vehicle extends FAH_Model {

	public $no_of_passengers;

	public $rate_per_hour;

	public $rate_per_km;

	public $min_fare;

	public $base_rate;

	public $available_qty;
	
	protected $post_type = 'fah_vehicle';
	
}