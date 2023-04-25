<?php 
	$booking = fah_get_booking(); 
	$no_of_passengers = $booking->vehicle->no_of_passengers;

	// getting vehicle
	$get_selected_vehicle = $booking->vehicle->id;
	$get_post_vehicle = get_post($get_selected_vehicle, true);
	$get_post_vehicle_meta = get_post_meta( $get_selected_vehicle, '' , true);
	$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"); 

?>
	<input type="hidden" name="passenger_htype"
	 	value="<?php echo isset($no_of_passengers[0]) ? $no_of_passengers[0] : 'Not Set';  ?>" />
	<input type="hidden" name="passenger_hvehicle_name" 
		value="<?php echo isset($get_post_vehicle['post_title']) ?$get_post_vehicle['post_title'] : 'Not Set'; ?>" />
	<input type="hidden" name="passenger_hbase_rate" 
		value="<?php echo isset($get_post_vehicle_meta['base_rate'][0]) ? $get_post_vehicle_meta['base_rate'][0] : 'Not Set'; ?>" />
<?php if(!empty($booking->passengers)){  ?>
	<input type="hidden" name="no_of_passengers_vehicle" 
		value="<?php echo !empty($booking->passengers) ? count($booking->passengers) : null ?>" />
	<form id="booking-passenger-form" action="<?php echo admin_url('admin-ajax.php') ?>" method="post">
		<div class="fah-booking-container fah-passenger-detail">
			<div class="fah-row">
					<span id="msg_passenger_err"></span>
					<span id="link-to-vehicle"></span> 
				<div class="fah-col-quater">
					<?php fah_get_template_part('booking-form/sidebar') ?>
				</div>
				<div class="fah-col-quater3">
					<div class="fah-row" id="fah-add-passenger">
						<div class="fah-col-full fah-right">
							<button class="fah-btn fah-btn-secondary" id="add-passenger-btn-after">Add</button>
						</div>
					</div>

					<div class="fah-row fah-passenger-container">
						<div class="fah-col-full form-passengers">
							<?php  foreach($booking->passengers as $key => $value){ ?>
							
							<br/>
				<button type="button" class="btn btn-warning btn-sm delete-passenger" value="<?php echo $key; ?>">Delete This Passenger</button>
							<br><br> 

							
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>First name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your first name" 	name="passenger[<?php echo $key; ?>][first_name]" 
											value="<?php echo $value->first_name; ?>" <?php if($key == 0){ ?>  required="required"  <?php } ?> />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Last name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your last name" name="passenger[<?php echo $key; ?>][last_name]" value="<?php echo $value->last_name; ?>" <?php if($key == 0){ ?>  required="required"  <?php } ?> />
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Company name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your company name" name="passenger[<?php echo $key; ?>][company]" value="<?php echo $value->company; ?>" />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Email</label>
										<input type="email" class="fah-form-control" placeholder="Enter your email" name="passenger[<?php echo $key; ?>][email]" value="<?php echo $value->email; ?>" <?php if($key == 0){ ?>  required="required"  <?php } ?> />
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Country</label>
										<select class="fah-form-control" name="passenger[0][country]" placeholder="Select 	Country">
											<?php
											foreach ($countries as $key => $country) { 
													if($country == $value->country){ ?>
														<option value="<?php echo $country; ?>" selected><?php echo $country; ?></option>
													<?php } else{ ?>
														<option value="<?php echo $country; ?>"><?php echo $country; ?></option>
													<?php } } ?>				
										</select>
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>City</label>
										<input type="text" class="fah-form-control" placeholder="Enter your city" name="passenger[0][city]" value="<?php echo $value->city; ?>" />
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Phone</label>
										<input type="text" class="fah-form-control" placeholder="Enter your phone" name="passenger[0][phone]" value="<?php echo $value->phone; ?>" <?php if($key == 0){ ?>  required="required"  <?php } ?> />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Mobile</label>
									<input type="text" class="fah-form-control" placeholder="Enter your mobile" name="passenger[0][mobile]" value="<?php echo $value->mobile; ?>" <?php if($key == 0){ ?>  required="required"  <?php } ?> />
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="fah-row">
						<div class="fah-col-full fah-right">
							<button class="fah-btn fah-btn-primary" id="btn-passenger">Next</button>
						</div>
					</div>
				</div>
			</div>
		</div>
			<input type="hidden" name="action" value="save_booking_info" />
			<input type="hidden" name="step" value="<?php echo 'add-passenger' ?>"/>
	</form>
<?php }else{  ?>
	<form id="booking-passenger-form" action="<?php echo admin_url('admin-ajax.php') ?>" method="post">

		<div class="fah-booking-container fah-passenger-detail">
			<div class="fah-row">
					<span id="msg_passenger_err"></span>
					<span id="link-to-vehicle"></span> 
				<div class="fah-col-quater">
					<?php fah_get_template_part('booking-form/sidebar') ?>
				</div>
				<div class="fah-col-quater3">
					<div class="fah-row">
						<div class="fah-col-full fah-right">
							<button class="fah-btn fah-btn-secondary" id="add-passenger-btn">Add</button>
						</div>
					</div>

					<div class="fah-row fah-passenger-container">
						<div class="fah-col-full form-passengers">
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>First name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your first name" name="passenger[0][first_name]" required="required" />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Last name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your last name" name="passenger[0][last_name]" required="required" />
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Company name</label>
										<input type="text" class="fah-form-control" placeholder="Enter your company name" name="passenger[0][company]" />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Email</label>
										<input type="email" class="fah-form-control" placeholder="Enter your email" name="passenger[0][email]" required="required"/>
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Country</label>
								<select class="fah-form-control" name="passenger[0][country]" placeholder="Select Country">
											<?php
					foreach ($countries as $key => $value) { ?>
									<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
											<?php } ?>				
								</select>
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>City</label>
										<input type="text" class="fah-form-control" placeholder="Enter your city" name="passenger[0][city]" />
									</div>
								</div>
							</div>
							<div class="fah-row">
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Phone</label>
										<input type="text" class="fah-form-control" placeholder="Enter your phone" name="passenger[0][phone]" required="required" />
									</div>
								</div>
								<div class="fah-col-half">
									<div class="fah-form-group">
										<label>Mobile</label>
									<input type="text" class="fah-form-control" placeholder="Enter your mobile" name="passenger[0][mobile]" required="required"/>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="fah-row">
						<div class="fah-col-full fah-right">
							<button class="fah-btn fah-btn-primary" id="btn-passenger">Next</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="action" value="save_booking_info" />
		<input type="hidden" name="step" value="<?php echo 'add-passenger' ?>"/>
	</form>
<?php } ?>