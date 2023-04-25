<?php

if(!class_exists('FAH_Bootstrap')) {
	return;
}

class FAH_Bootstrap {

	public function __construct()
	{
		/**
		 * include all required files
		 */
		require_once __DIR__ . '/class.fah.model.php';
		require_once __DIR__ . '/class.fah.vehicle.php';
		require_once __DIR__ . '/class.fah.passenger.php';
		require_once __DIR__ . '/class.fah.booking.php';
		require_once __DIR__ . '/class.fah.ajax.actions.php';
		require_once __DIR__ . '/class.fah.settings.php';
		require_once __DIR__ . '/fah.shortcodes.php';
		require_once __DIR__ . '/lib/cmb2/init.php';
		require_once __DIR__ . '/fah.functions.php';

		/**
		 * main hooks
		 */
		add_action('init', array($this, 'init'));
		add_action('cmb2_admin_init', array($this, 'init_meta_fields'));
		add_action('wp_enqueue_scripts', array($this,'wp_enqueue_scripts'));
	}

	public function init()
	{
		fah_init_session();
		$this->init_post_types();
	}

	public function init_post_types()
	{
		// Registering booking post type

		$booking_args = array(
			'labels' => array(
				'name' => __('Bookings', 'cr-fahsoft'),
				'menu_name' => __('Bookings', 'cr-fahsoft'),
				'singular_name' => __('Booking', 'cr-fahsoft'),
			),
			'public' => false,
			'show_ui' => true,
			'publicly_queryable' => false,
		    'has_archive' => false,
		    'supports' => false,
		    'capabilities' => array(
			 	'create_posts' => false,
			),
			'map_meta_cap' => true
		);

		register_post_type('fah_booking', $booking_args);

		// Registering vehicle post type

		$vehicle_args = array(
			'labels' => array(
				'name' => __('Vehicles', 'cr-fahsoft'),
				'menu_name' => __('Vechicles', 'cr-fahsoft'),
				'singular_name' => __('Vechile', 'cr-fahsoft'),
			),
			'public' => false,
			'show_ui' => true,
			'publicly_queryable' => false,
		    'has_archive' => false,
		    'show_in_menu' => 'edit.php?post_type=fah_booking',
		    //'supports' => array('title', 'editor', 'thumbnail')
		    'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail'),
		);

		register_post_type('fah_vehicle', $vehicle_args);

		// Registering passenger post type

		$passenger_args = array(
			'public' => false,
			'show_ui' => false,
			'publicly_queryable' => false,
		    'has_archive' => false,
		    'supports' => false
		);

		register_post_type('fah_passenger', $passenger_args);
	}

	public function init_meta_fields()
	{
		$cmb_vehicle = new_cmb2_box( array(
			'id'            => 'fah_vehicle_metabox',
			'title'         => esc_html__( 'Vehicle Detail' ),
			'object_types'  => array( 'fah_vehicle' )
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'No. of Passengers' ),
			'id'         => 'no_of_passengers',
			'type'       => 'text'
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'Rate Per Hour' ),
			'id'         => 'rate_per_hour',
			'type'       => 'text'
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'Rate Per Km' ),
			'id'         => 'rate_per_km',
			'type'       => 'text'
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'Min. Fare' ),
			'id'         => 'min_fare',
			'type'       => 'text'
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'Base Rate' ),
			'id'         => 'base_rate',
			'type'       => 'text'
		) );

		$cmb_vehicle->add_field( array(
			'name'       => esc_html__( 'Available Qty.' ),
			'id'         => 'available_qty',
			'type'       => 'text'
		) );
		// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_vehicle->add_field( array(
		'id'          => 'vehicle_feature',
		'type'        => 'group',
		'description' => esc_html__( 'Generates reusable form entries', 'cmb2' ),
		'options'     => array(
			'group_title'   => esc_html__( 'Entry {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Vehicle', 'cmb2' ),
			'remove_button' => esc_html__( 'Remove Vehicle', 'cmb2' ),
			'sortable'      => true,
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );
	
	$cmb_vehicle->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Enter Feature', 'cmb2' ),
		'id'         => 'vehicle_feature',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
}

	public function wp_enqueue_scripts()
	{
		/**
		 * core dependencies
		 */
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

		/**
		 * additional plugin and initialize script
		 */

		wp_enqueue_script( 'fah-selectize', plugin_dir_url( __DIR__ ) . 'assets/js/selectize.min.js', array('jquery'), '', true );

		wp_enqueue_script( 'fah-init', plugin_dir_url( __DIR__ ) . 'assets/js/fah-init.js', array('jquery'), '', true );
		
		wp_localize_script( 'fah-init', 'ajaxcontactajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		/**
		 * google map url
		 */
		$googleMapApiUrlParams = http_build_query(array(
									'key' => CRF_GOOGLE_API_KEY,
									'libraries' => 'places',
									'callback' => 'fahGoogleMapInit'
								), '', '&');

		$googleMapApiUrl = 'https://maps.googleapis.com/maps/api/js?' . $googleMapApiUrlParams;
		wp_enqueue_script( 'fah-map', $googleMapApiUrl, null, '', true );

		/**
		 * styles
		 */
        wp_enqueue_style( 'fah-selectize', plugin_dir_url( __DIR__ ) . 'assets/css/selectize.css' );
		wp_enqueue_style( 'fah-styles', plugin_dir_url( __DIR__ ) . 'assets/css/fah-main.css' );
	}

}

new FAH_Bootstrap;