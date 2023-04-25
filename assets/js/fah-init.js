var fahInputPickup, 
    fahInputDropOff,
    fahInputPickupPlace, 
    fahInputDropOffPlace, 
    fahMap, 
    fahAutocompleteDropoff, 
    fahAutocompletePickup,
    fahPickupMarker,
    fahDropoffMarker,
    directionsDisplay,
    directionsService;

function fahGoogleMapInit() {
    
    fahInputPickup  = document.getElementById('fah-pickup_location');
    fahInputDropOff = document.getElementById('fah-dropoff_location');
    fahMap = new google.maps.Map(document.getElementById('fah-map'), {
        zoom: 12,
        center: new google.maps.LatLng(51.5236042,-0.1819144),
        types: ["street_address"]
    });

    fahPickupMarker = new google.maps.Marker({
        map: fahMap,
        title: 'Pickup'
    });

    fahDropoffMarker = new google.maps.Marker({
        map: fahMap,
        title: 'Drop-off'
    });

  
    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;
    directionsDisplay.setMap(fahMap);

    fahAutocompleteDropoff = new google.maps.places.Autocomplete(fahInputPickup, {
        componentRestrictions: {
            country: 'UK'
        }
    });

    fahAutocompletePickup  = new google.maps.places.Autocomplete(fahInputDropOff, {
        componentRestrictions: {
            country: 'UK'
        }
    });

    fahAutocompleteDropoff.addListener('place_changed', function() {
        fahInputDropOffPlace = fahAutocompleteDropoff.getPlace();

        var lat = fahInputDropOffPlace.geometry.location.lat();
        var lng = fahInputDropOffPlace.geometry.location.lng();
        var latlng = lat + ',' + lng;
        fahCalculateAndDisplayRoute({
            lat: fahInputPickupPlace.geometry.location.lat(), 
            lng: fahInputPickupPlace.geometry.location.lng()
        }, {lat: lat, lng: lng});
    }, true);

    fahAutocompletePickup.addListener('place_changed', function() {
        fahInputPickupPlace = fahAutocompletePickup.getPlace();
      
        var lat = fahInputPickupPlace.geometry.location.lat();
        var lng = fahInputPickupPlace.geometry.location.lng();
        fahCalculateAndDisplayRoute({lat: lat, lng: lng}, {
            lat: fahInputDropOffPlace.geometry.location.lat(), 
            lng: fahInputDropOffPlace.geometry.location.lng()
        }, true);
    });
}

function fahCalculateAndDisplayRoute(origin, destination, checkInput) {        
    if(checkInput === true && (fahInputPickupPlace == undefined || fahInputDropOffPlace == undefined)) {
        return;
    }

    directionsService.route({
        origin: origin,
        destination: destination,
        travelMode: 'DRIVING'
    }, function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });

    document.getElementById('pickup_location_coods').value = origin.lat + ',' + origin.lng;
    document.getElementById('dropoff_location_coods').value = destination.lat + ',' + destination.lng;

    fahMap.setCenter(fahInputPickupPlace.geometry.location);
    fahMap.setZoom(17);
}

jQuery(function($) {

    
   
    $("[name='booking_data[pickup_date]']").datepicker({  
        minDate: 0
    });
    //  $("[name='booking_data[return_date]']").datepicker({
    //     minDate: 0
    // });
    $('.fah-booking-type-dependent').hide();
    $('.fah-return-dependent').hide();
        
    $(document).on('change', '[name=choose-from-map]', function() {

        var $closest = $(this).closest('.fah-col-half');
        if($(this).is(':checked')) {
            $('#fah-pickup_location, #fah-dropoff_location').prop('disabled', true);
            $closest.addClass('enable-pin');
        } else {
            $('#fah-pickup_location, #fah-dropoff_location').prop('disabled', false);
            $closest.removeClass('enable-pin');
        }
    });

     $(document).on('change', "[name='booking_data[asap]']", function() {
        if($(this).is(':checked')) {
            $('.fah-asap-dependent').hide();
            $("[name='booking_data[asapoff]']").val('asap_off');
        } else {
            $('.fah-asap-dependent').show();
            $("[name='booking_data[asapoff]']").val('asap_on');
        }
    });


	$("input[name='booking_data[booking_type]']").change(function(){
   			  bookType = $(this).val();
   			 if(bookType == 'one way'){
   			 		  $('.fah-booking-type-dependent').hide();
   					  $('.fah-return-dependent').hide();
   			 }else if (bookType == 'daily'){
   			 		$('.fah-booking-type-dependent').show();
   			 		$('.fah-return-dependent').hide();
   			 }else if(bookType == 'return'){
   			 		  $('.fah-booking-type-dependent').hide();
   					  $('.fah-return-dependent').show();
   			 }
	});
  $('#ajaxcontactform').on('submit', function(e) {
        e.preventDefault();
          $.ajax({
            type:"POST",
            url: ajaxcontactajax.ajaxurl,
            data:$(this).serialize(),
            success:function(data){
            error = data['error']; // true or false
            if(error == true){
                booking_type = data['booking_data']['booking_type'];
                pickup_date = data['booking_data']['pickup_date'];
                pickup_time_hour =data['booking_data']['pickup_time_hour'];
                pickup_time_min =data['booking_data']['pickup_time_min'];
                dropoff_location  = data['booking_data']['dropoff_location'];
                dropoff_location_coods = data['booking_data']['dropoff_location_coods'];
                pickup_location  = data['booking_data']['pickup_location'];
                pickup_location_coods = data['booking_data']['pickup_location_coods'];
                no_of_days = data['booking_data']['no_of_days'];
                waiting_time_hour = data['booking_data']['waiting_time_hour'];
                waiting_time_min = data['booking_data']['waiting_time_min'];

                $('#pickup_date_err').empty();
                 $('#bookingtype_return_err').empty();
                $('#pickup_location_err').empty();
                $('#pickup_location_coods_err').empty();
                $('#dropoff_location_err').empty();
                $('#dropoff_location_coods_err').empty();
                $('#message_err').empty();
                message_err = data['message'];
                $('#message_err').text(message_err);
                errors = data['errors'];
                $.each( errors, function( key, value ) {
                  //console.log( key +' '+ value);
                   // var arr_chk = errors.indexOf("bookingtype_offon");  
                   // if(arr_chk !== -1){
                   //      $('#'+key+'_err').text(value);
                   //  }
                  $('#'+key+'_err').text(value);
                });
          }
          else if(error == false){
              $('#pickup_date_err').empty();
              $('#pickup_location_err').empty();
              $('#pickup_location_coods_err').empty();
              $('#dropoff_location_err').empty();
              $('#dropoff_location_coods_err').empty();
              $('#message_err').empty();
              current_url = window.location.href.split('?')[0];
              window.location.href = current_url+'?step=select-vehicle'; 
            }
          },
          error: function(errorThrown){
              console.log(errorThrown);
          }
      });
  });

   vehicles_empty = $("input[name='vehicles_empty']").val();  
   booking_htype = $("input[name='booking_htype']").val();
   if(vehicles_empty == "empty vehicles"){
           $('#btn-vehicle').prop('disabled', true);
           $('#booking-vehicle-form').removeAttr( "action" );
           $('#msg_vehicle_err').text('No Vehicle Available');
           current_url = window.location.href.split('?')[0];
           $('#link-to-location').append('<a href="'+current_url+'?step=select-location"> | GO BACK</a>');
   }
   if(vehicles_empty == "not empty vehicles"){
     if( booking_htype == 'Not Set'){
           $('#btn-vehicle').prop('disabled', true);
           $('#booking-vehicle-form').removeAttr( "action" );
           $('#msg_vehicle_err').text('Kindly! Select Location First');
           current_url = window.location.href.split('?')[0];
           $('#link-to-location').append('<a href="'+current_url+'?step=select-location"> | Select Location</a>');
     }
 }

    $("input[name='vehicle_id']").click(function()
    {

      post_id = $(this).val();
      $("[name='vehicle_id_info']").val(post_id); 
    });
    $('#booking-vehicle-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
        type:"POST",
        url: ajaxcontactajax.ajaxurl,
        data:$(this).serialize(),
        success:function(data){
        error = data['error']; // true or false
        console.log(data);
        if(error == true){       
            errors = data['errors']['vehicle_id_info'];
            $('#message_err').text(errors);
            console.log('Bhai aik error hai');
        }
        else if(error == false){
                $('#message_err').empty();
                console.log('Bhai Errors nahi hain');
                $('.fah-summary-item').show();
                current_url = window.location.href.split('?')[0];
                window.location.href = current_url+"?step=add-passenger";
            }

        },
        error: function(errorThrown){
            //alert(errorThrown);
            console.log(errorThrown);
        }
    });
     
  });

 // add-  passenger - page
    passenger_htype = $("input[name='passenger_htype']").val(); // getting passenger quantity
   if( passenger_htype == 'Not Set'){
         $('#btn-passenger').prop('disabled', true);
         $('#booking-passenger-form').removeAttr( "action" );
         $('#msg_passenger_err').text('Kindly! Select first vehicle');
         current_url = window.location.href.split('?')[0];
         $('#link-to-vehicle').append('<a href="'+current_url+'?step=select-vehicle">| Select Vehicle</a>');
   }
    var wrapper = $(".form-passengers"); //Fields wrapper
    var maxField = passenger_htype; //Input fields increment limitation
    var x = 1; //Initial field counter is 1
  $( "#add-passenger-btn" ).click(function(e) {
  e.preventDefault();
    //Check maximum number of input fields
    if(x < maxField){ 
        $(wrapper).append(
            '<div>'+
            '<div class="fah-row">'+
                    '<div class="fah-col-half">'+
                       '<div class="fah-form-group">'+
                            '<label>First name</label>'+
            '<input type="text" class="fah-form-control" placeholder="Enter your first name" name="passenger['+x+'][first_name]" />'+
                        '</div>'+
                    '</div>'+
                    '<div class="fah-col-half">'+
                        '<div class="fah-form-group">'+
                            '<label>Last name</label>'+
            '<input type="text" class="fah-form-control" placeholder="Enter your last name" name="passenger['+x+'][last_name]" />'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '<div class="fah-row">'+
                    '<div class="fah-col-half">'+
                        '<div class="fah-form-group">'+
                            '<label>Email</label>'+
                            '<input type="email" class="fah-form-control" placeholder="Enter an email" name="passenger['+x+'][email]" />'+
                        '</div>'+
                    '</div>'+
                    '<div class="fah-col-half">'+
                        '<div class="fah-form-group">'+
                            '<label>Phone</label>'+
                        '<input type="text" class="fah-form-control" placeholder="Enter your phone" name="passenger['+x+'][phone]" />'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<a href="#" class="remove_field"><i class="fa fa-times">Remove Passenger</a>'+
                '</div>'
                ); //Add field html
        x++; //Increment field counter
    }
});

   $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        $(this).parent('div').remove();
         x--;
    })

    $('#booking-passenger-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
        type:"POST",
        url: ajaxcontactajax.ajaxurl,
        data:$(this).serialize(),
        success:function(data){
            current_url = window.location.href.split('?')[0];
            window.location.href = current_url+"?step=review-booking";
    

        },
        error: function(errorThrown){
            //alert(errorThrown);
            console.log(errorThrown);
        }
    });
     
  });
 // Review Page Work
     review_situation = $("input[name='review-situation']").val();
     if(review_situation == 'Set'){
                     passenger_htype = $("input[name='passenger_htype']").val(); // getting passenger quantity
                    if( undefined != passenger_htype && passenger_htype != 'Not Set'){
                       $('.fah-summary-item').show();
                       $('.fah-vehicle-detail-items').show();
                      vehicle_name = $("input[name='passenger_hvehicle_name']").val();
                      base_rate = $("input[name='passenger_hbase_rate']").val();
                      
                      $('#fah_vehicle_title').text(vehicle_name);
                      $('#fah_vehicle_brate').text('$'+base_rate+'/hour'); 

                   }
      }
     else{ 
          // $('.fah-summary-item').hide();
          // $('.fah-vehicle-detail-items').hide();
         $('#btn-review').prop('disabled', true);
         $('#booking-review-form').removeAttr( "action" );
         $('#msg_review_err').text('Kindly! Add Passenger First');
         current_url = window.location.href.split('?')[0];
         $('#link-to-passenger').append('<a href="'+current_url+'"?step=add-passenger">| Add Passenger</a>');

     }
 // Setting Page
   $( "#target" ).submit(function( event ) {
    alert( "Handler for .submit() called." );
    event.preventDefault();
  });

// Deleting Passenger
  $('.delete-passenger').click(function(e) {
        e.preventDefault();
        index_no = $(this).val();
       // console.log(ajaxcontactajax.ajaxurl);
        $.ajax({
              type:"POST",
              url: ajaxcontactajax.ajaxurl,
              data:{ 
                  action: 'delete_passenger_info',
                  index_no: index_no 
                },
              success:function(data){
               //alert(data);
                console.log(data);
                location.reload();
                //myTimeoutFunction();
              },
              error: function(errorThrown){
              //alert(errorThrown);
                console.log(errorThrown);
              }
          });
      //alert(index_no);
  });
  no_of_passengers_vehicle = $( "input[name='no_of_passengers_vehicle']" ).val();
  if ( no_of_passengers_vehicle != null) {
    if ( no_of_passengers_vehicle.length > 0){
      if(passenger_htype == no_of_passengers_vehicle){
          $('#fah-add-passenger').hide();
      }
    }
}
    var wrapper = $(".form-passengers"); //Fields wrapper
    var max_field_after = passenger_htype - no_of_passengers_vehicle;  // 5 - 2 = 3
    var start_up = passenger_htype - max_field_after; // start up
   $( "#add-passenger-btn-after" ).click(function(e) {
    e.preventDefault();
        //Check maximum number of input fields
        if(start_up < passenger_htype){ 
            $(wrapper).append(
                '<div>'+
                '<div class="fah-row">'+
                        '<div class="fah-col-half">'+
                           '<div class="fah-form-group">'+
                                '<label>First name</label>'+
                '<input type="text" class="fah-form-control" placeholder="Enter your first name" name="passenger['+start_up+'][first_name]" />'+
                            '</div>'+
                        '</div>'+
                        '<div class="fah-col-half">'+
                            '<div class="fah-form-group">'+
                                '<label>Last name</label>'+
                '<input type="text" class="fah-form-control" placeholder="Enter your last name" name="passenger['+start_up+'][last_name]" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '<div class="fah-row">'+
                        '<div class="fah-col-half">'+
                            '<div class="fah-form-group">'+
                                '<label>Email</label>'+
                                '<input type="email" class="fah-form-control" placeholder="Enter an email" name="passenger['+start_up+'][email]" />'+
                            '</div>'+
                        '</div>'+
                        '<div class="fah-col-half">'+
                            '<div class="fah-form-group">'+
                                '<label>Phone</label>'+
                            '<input type="text" class="fah-form-control" placeholder="Enter your phone" name="passenger['+start_up+'][phone]" />'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<a href="#" class="remove_field"><i class="fa fa-times">Remove Passenger</a>'+
                    '</div>'
                    ); //Add field html
            start_up++; //Increment field counter
        }
    });

   $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        $(this).parent('div').remove();
         start_up--;
    });

    vehicle_set_after = $("input[name='vehicle_set_after']").val();
    if( undefined != vehicle_set_after && vehicle_set_after != 'Not Set')
    {
          $("[name='vehicle_id_info']").val(vehicle_set_after); 
        //alert('hahah');
      }
  
    if ($('#asap').is(':checked')) {
        $('.fah-asap-dependent').hide();
        $("[name='booking_data[asapoff]']").val('asap_off');
    }else{
        $('.fah-asap-dependent').show();
        $("[name='booking_data[asapoff]']").val('asap_on');
    }

    var $closest_two = $('[name=choose-from-map]').closest('.fah-col-half');
        if($('[name=choose-from-map]').is(':checked')) {
            $('#fah-pickup_location, #fah-dropoff_location').prop('disabled', true);
            $closest_two.addClass('enable-pin');
        } else {
            $('#fah-pickup_location, #fah-dropoff_location').prop('disabled', false);
            $closest_two.removeClass('enable-pin');
        }
  pickup_location_coods = $('#pickup_location_coods').val();
  if(undefined != pickup_location_coods){
    pickup_location_lat = pickup_location_coods.substr(0, pickup_location_coods.indexOf(',')); 
    pickup_location_lat = parseFloat(pickup_location_lat);
    pickup_location_lng= pickup_location_coods.split(",").pop();
    pickup_location_lng = parseFloat(pickup_location_lng);

   }

   dropoff_location_coods = $('#dropoff_location_coods').val();
  if(undefined != dropoff_location_coods){
     dropoff_location_lat = dropoff_location_coods.substr(0, dropoff_location_coods.indexOf(',')); 
     dropoff_location_lat = parseFloat(dropoff_location_lat);
     dropoff_location_lng= dropoff_location_coods.split(",").pop();
     dropoff_location_lng = parseFloat(dropoff_location_lng);

   }
  
   //var pickup_location_coods = pickup_location_coods.substr(pickup_location_coods.indexOf(",") + 1)
   if((undefined != dropoff_location_coods) && (pickup_location_coods.length > 0) && (dropoff_location_coods.length > 0)){
         lat = dropoff_location_lat;
         lng = dropoff_location_lng;
         fahCalculateAndDisplayRoute({
            lat: pickup_location_lat, 
            lng: pickup_location_lng
        }, {lat: lat, lng: lng});
        // alert('IF');
   }
    $(document).on('click', '.fah-set-pickup', function() {
        var centerPin = fahMap.getCenter()
        var lat = centerPin.lat();
        var lng = centerPin.lng();
        var latLng = new google.maps.LatLng(lat, lng);
        getPlaceName(latLng,'pickup_location_name');
      
        fahPickupMarker.setPosition(latLng);
    });

   $(document).on('click', '.fah-set-dropoff', function() {

        var centerPin = fahMap.getCenter()
        var lat = centerPin.lat();
        var lng = centerPin.lng();
        var latLng = new google.maps.LatLng(lat, lng);
        getPlaceName(latLng,'dropup_location_name');
        fahDropoffMarker.setPosition(latLng);
        fahCalculateAndDisplayRoute({
            lat: fahPickupMarker.getPosition().lat(), 
            lng: fahPickupMarker.getPosition().lng()
        }, {lat: lat, lng: lng});

    });
    function getPlaceName(latLng,name) { 
       var geocode = new google.maps.Geocoder();
       geocode.geocode( { 'latLng' : latLng }, function(results,status) {
          if(status === google.maps.GeocoderStatus.OK){
            if(results[0]){
                var address = results[0].formatted_address;
                sendAddress(address, name);
            }
          }
       } )
    }
    function sendAddress(address,name){
        $.ajax({
              type:"POST",
              url: ajaxcontactajax.ajaxurl,
              data:{ 
                  action: 'add_pickup_dropup_location',
                  location_name: address,
                  location_type : name 
                },
              success:function(data){
                console.log(data);
              },
              error: function(errorThrown){
                console.log(errorThrown);
              }
          });
    }
    // REVIEW PAGE
    paymentmethod_set_after = $("input[name='paymentmethod_set_after']").val();
    if( undefined != paymentmethod_set_after && paymentmethod_set_after != 'Not Set')
    {
          $("[name='payment_method_info']").val(paymentmethod_set_after); 
        
    }
   $("input[name='payment_method']").click(function(){

        payment_method = $(this).val();
        $("[name='payment_method_info']").val(payment_method); 
    });
    $('#booking-review-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
        type:"POST",
        url: ajaxcontactajax.ajaxurl,
        data:$(this).serialize(),
        success:function(data){
        error = data['error']; // true or false
        if(error == true){       
              errors = data['errors']['payment_method'];
              $('#message_err').text(errors);
        }
        else if(error == false){
                $('#message_err').empty();
                
                    $.ajax({
                          type:"POST",
                          url: ajaxcontactajax.ajaxurl,
                          data:{ 
                              action: 'save_all_data',
                             
                            },
                          success:function(data){
                            console.log(data);
                            message =data['message'];
                            status = data['status'];
                            if(status === 'true'){
                                  $.ajax({
                                        type:"POST",
                                        url: ajaxcontactajax.ajaxurl,
                                        data:{ 
                                            action: 'clean_all_data',
                                           
                                          },
                                        success:function(data){
                                        console.log(data);
                                        message =data['message'];
                                        status = data['status'];
                                        if(status === 'true'){
                                              current_url = window.location.href.split('?')[0];
                                              window.location.href = current_url+"?step=thank-you";
                                         
                                        }else if(status == 'false'){
                                              $('#message_err').text("Sorry ! Can't Proceed. There are some issues");
                                            }
                                             console.log(status);
                                        },
                                        error: function(errorThrown){
                                          console.log(errorThrown);
                                        }
                                    });
                                //current_url = window.location.href.split('?')[0];
                                //window.location.href = current_url+"?step=thank-you";
                            }
                            else if(status == 'false'){
                                 $('#message_err').text("Sorry ! Can't Proceed. There are some issues");
                            }
                            console.log(status);
                          },
                          error: function(errorThrown){
                            console.log(errorThrown);
                          }
                      });
            }

        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });
     
  });
  
  current_url = window.location.href.split('?')[0];
  $('#go_to_home_page').append("<a class='btn btn-primary btn-sm' href='"+current_url+"?step=select-location' role='button'>Continue to homepage</a>");

});