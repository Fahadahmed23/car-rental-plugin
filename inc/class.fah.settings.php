<?php 

class FAH_Settings {

	public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_save_setting_form', array($this, 'save_setting_form'));
        add_action('wp_ajax_nopriv_save_setting_form', array($this, 'save_setting_form'));
    }

    /**
     * Admin Enqueue Scripts for adding admin javascript css dependencies for this plugin
     */
    public function admin_enqueue_scripts()
    {
        
    	wp_enqueue_script( 'fah-selectize', plugin_dir_url( FAH_PLUGIN_DIR ) . 'car-rental-wp-plugin/assets/js/selectize.min.js', array('jquery'), '', true );
        wp_enqueue_style( 'fah-selectize', plugin_dir_url( FAH_PLUGIN_DIR ) . 'car-rental-wp-plugin/assets/css/selectize.css' );
        wp_enqueue_script( 'fah-init-admin', plugin_dir_url( FAH_PLUGIN_DIR ) . 'car-rental-wp-plugin/assets/js/admin-init.js', array('jquery'), true );
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'admin-styles', plugin_dir_url( FAH_PLUGIN_DIR ) . 'car-rental-wp-plugin/assets/css/admin-style.css' );
    }

	/**
     * Register Admin Menus
     */
    public function register_admin_menus()
    {
		
        add_submenu_page( 
            'edit.php?post_type=fah_booking',
            'Settings',
            'Settings',
            'manage_options',
            'fah-settings',
            array($this, 'setting_page')
        );
        
    }

	/**
     * Render plugin setting page
     */
    public function setting_page()
    {
        $this->general_options = get_option( 'fah_general_field' );
        $this->style_options = get_option( 'fah_style_field' );
		
		    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

		    $general = ($active_tab == 'general') ? "nav-tab-active" : '';

		    $style = ($active_tab == 'styles') ? "nav-tab-active" : '';
        ?>
        <style>
            #fah-setting {
                position: relative;
                padding-top: 5px;
            }
            
            .fah-section-container {
                padding: 20px;
                background: #ffffff;
                margin: 10px 0;
                box-shadow: 0px 1px 1px #cccccc;
                font-size: 13px;
            }
            
            .fah-section-container .form-table,
            .fah-section-container .form-table td,
            .fah-section-container .form-table td p, 
            .fah-section-container .form-table th {
                font-size: 13.5px;
            }

            .fah-section-container h2 {
                font-size: 16px;
                padding-bottom: 15px;
                margin: 0;
                border-bottom: 1px solid #ececec;
            }

            .fah-radio-container {
                display: inline-block;
                border-radius: 3px;
                height: 30px;
                background: #e4e4e4;
                /* border: 1px solid #e4e4e4; */
                overflow: hidden;

            }

            .fah-radio-container > label {
                float: left;
                margin: 0 !important;
            }

            .fah-radio-container > label > span {
                display: block;
                width: 100%;
                box-sizing: border-box;
                border-right: 1px solid #cccccc;
                line-height: 29px;
                height: 30px;
                display: inline-block;
                font-weight: bold;
                padding: 0 10px;
                color: #696868;
            }

            .fah-radio-container > label > input[type=checkbox]:checked + span,
            .fah-radio-container > label > input[type=radio]:checked + span {
                background: #005ae2;
                color: #ffffff;
            }

            .fah-radio-container > label:last-child > span {
                border-right: none;
            }

            .fah-radio-container > label > input[type=checkbox],
            .fah-radio-container > label > input[type=radio] {
                display: none;
            }
            
            .fah-description {
                color: #757575;
                font-style: italic; 
                font-size: 13px;
                margin: 10px 0 !important;
                display: block;
            }

            .fah_input_field {
                min-width: 300px;
                height: 32px;
                line-height: 32px;
                border: 1px solid #cccccc;
                padding: 0 10px;
                box-shadow: inset 0 1px 1px rgba(0,0,0,.125);
            }

            .fah_input_field:focus {
                outline: none;
                border-color: #0073aa;
            }
            
            .fah-description pre,
            .fah-info pre {
                margin: 20px 0 0;
                padding: 7px 10px;
                background: #f2f2f2;
                display: block;
                box-shadow: inset 0 1px 1px rgba(0,0,0,.125);
            }
            
            .fah-loading {
                background: url(<?php echo site_url('/wp-admin/images/spinner-2x.gif') ?>) #ffffffd1 no-repeat;
                background-position: center;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                z-index: 999;
                display: none;
            }
            
            .fah-section-submit p {
                text-align: right;
                padding-right: 15px;
            }
            
            .selectize-dropdown.fah_input_field,
            .selectize-dropdown.fah_select_field,
            .selectize-control.fah_input_field,
            .selectize-control.fah_select_field {
			    border: none;
			    padding: 0;
			    height: auto !important;
			    max-width: 300px;
			}
        </style>
        <div class="wrap" id="fah-setting">
            <div class="fah-loading"></div>
            <h2 class="fah-setting-heading"><?php _e('FAH Settings') ?></h2>
			<h2 class="nav-tab-wrapper">
			    <a href="?post_type=fah_booking&page=fah-settings&tab=general" class="nav-tab <?php echo $general; ?>">General</a>
			    <a href="?post_type=fah_booking&page=fah-settings&tab=styles" class="nav-tab <?php echo $style; ?>">Styles</a>
			</h2>

            <form method="post" action="<?php echo admin_url('admin-ajax.php') ?>" id="fah-form">
                <?php if($active_tab == 'styles'): ?>
                    <div class="fah-section-container">
                        <?php 
                            do_settings_sections( 'fah_style_button_settings_group' );
                        ?>
                    </div>
                
                    <div class="fah-section-container">
                        <?php 
                            do_settings_sections( 'fah_style_input_settings_group' );
                        ?>
                    </div>
                
                    <div class="fah-section-container">
                        <?php 
                            do_settings_sections( 'fah_custom_style_input_settings_group' );
                        ?>
                    </div>
                <?php else: ?>
                    
	                <div class="fah-section-container">
	                    <?php 
	                        do_settings_sections( 'fah_general_settings_group' );
	                    ?>
	                </div>

	                <div class="fah-section-container">
	                    <?php 
	                        do_settings_sections( 'fah_payment_settings_group' );
	                    ?>
	                </div>
                
                <?php endif; ?>
                
                <div class="fah-section-submit">
                    <?php submit_button(); ?>
                </div>
                
                <input type="hidden" name="action" value="save_setting_form" />
                
            </form>
        </div>
        <?php
    }

    /**
     * init all admin functions like checking dependencies and pages
     */
    public function admin_init()
    {   
        $this->register_setting_section_and_tabs();
    }

    /**
     * register all required setting sections and fields
     */
    public function register_setting_section_and_tabs()
    {
        wp_enqueue_media();
        
        /*General Tab*/
        /*Get Quote Fields*/
        register_setting(
            'fah_general_settings_group',
            'fah_general_field'
        );

        add_settings_section(
            'setting_section_id',
            'General Options',
            null,
            'fah_general_settings_group'
        );

        add_settings_field( 
            'fah_layout_type', 
            'Select Layout Type', 
            array($this, 'radio_buttons'), 
            'fah_general_settings_group', 
            'setting_section_id', 
            array(
                "option"    => "fah_layout_type",
                'values'    => array(
                    'onepage' => __('One Page'),
                    'steps' => __('Steps')
                ),
                'default' => 'onepage'
            )
        );

        add_settings_field(
            'fah_google_api',
            'Google Map Key',
            array($this, 'add_text_field'),
            'fah_general_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_google_api",
               "name"  =>"fah_general_field[fah_google_api]"
            )
        );

        add_settings_field(
            'fah_min_fare',
            'Min Fare',
            array($this, 'add_text_field'),
            'fah_general_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_min_fare",
               "name"  =>"fah_general_field[fah_min_fare]"
            )
        );
        add_settings_field(
            'fah_tax',
            'Tax',
            array($this, 'add_text_field'),
            'fah_general_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_tax",
               "name"  =>"fah_general_field[fah_tax]"
            )
        );
        add_settings_field( 
            'fah_send_order_to_user', 
            'Send Order/Enquiry copy to user', 
            array($this, 'radio_buttons'), 
            'fah_general_settings_group', 
            'setting_section_id', 
            array(
                "option"    => "fah_send_order_to_user",
                'values'    => array(
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ),
                'default' => 'no'
            )
        );

        add_settings_field( 
            'fah_guest_checkout', 
            'Guest Checkout', 
            array($this, 'radio_buttons'), 
            'fah_general_settings_group', 
            'setting_section_id', 
            array(
                "option"    => "fah_guest_checkout",
                'values'    => array(
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ),
                'default' => 'no'
            )
        );

        /*Payment Tab*/
        register_setting(
            'fah_payment_settings_group',
            'fah_payment_field'
        );

        add_settings_section(
            'setting_section_id',
            'Payment Options',
            null,
            'fah_payment_settings_group'
        );

        add_settings_field( 
            'fah_payment_methods', 
            'Payment Methods', 
            array($this, 'multi_checkbox_buttons'), 
            'fah_payment_settings_group', 
            'setting_section_id', 
            array(
                "option"    => "fah_payment_methods",
                'values'    => array(
                    'cash' => __('Cash'),
                    'paypal' => __('Paypal'),
                    'stripe' => __('Stripe'),
                )
            )
        );

        add_settings_field(
            'fah_paypal_email',
            'Paypal Email',
            array($this, 'add_text_field'),
            'fah_payment_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_paypal_email",
               "name"  =>"fah_general_field[fah_paypal_email]"
            )
        );

        add_settings_field(
            'fah_stripe_email',
            'Stripe Email',
            array($this, 'add_text_field'),
            'fah_payment_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_stripe_email",
               "name"  =>"fah_general_field[fah_stripe_email]"
            )
        );

        add_settings_field(
            'fah_currency',
            'Currency',
            array($this, 'dropdown'),
            'fah_payment_settings_group',
            'setting_section_id',
            array(
               "option"=>"fah_currency",
               "name"  =>"fah_general_field[fah_currency]",
               "options" => array(
		    		"AUD" => "Australian Dollar",
					"BRL" => "Brazilian Real",
					"CAD" => "Canadian Dollar",
					"CZK" => "Czech Koruna",
					"DKK" => "Danish Krone",
					"EUR" => "Euro",
					"HKD" => "Hong Kong Dollar",
					"HUF" => "Hungarian Forint",
					"ILS" => "Israeli New Sheqel",
					"JPY" => "Japanese Yen",
					"MXN" => "Mexican Peso",
					"NOK" => "Norwegian Krone",
					"NZD" => "New Zealand Dollar",
					"PHP" => "Philippine Peso",
					"PLN" => "Polish Zloty",
					"GBP" => "Pound Sterling",
					"SGD" => "Singapore Dollar",
					"SEK" => "Swedish Krona",
					"CHF" => "Swiss Franc",
					"TWD" => "Taiwan New Dollar",
					"THB" => "Thai Baht",
					"USD" => "United States Dollar"
	    	    )
           )
        );
        /*Payment Tab*/


        /*Style Tab*/
        register_setting(
            'style_button_settings_group',
            'fah_style_field'
        );

        /*Button Styles*/
        add_settings_section(
            'button_styles',
            'Button Styles',
            false,
            'style_button_settings_group'
        );  

        add_settings_field(
            'btn_bg_color',
            'Button Background Color',
            array($this, 'add_text_field'),
            'style_button_settings_group',
            'button_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[btn_bg_color]",
                "option"=>"btn_bg_color",
                "flag"  =>"style_options",
                "id"    => "btn_bg_color"
            )
        );
        
        add_settings_field(
            'btn_text_color',
            'Button Text Color',
            array($this, 'add_text_field'),
            'style_button_settings_group',
            'button_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[btn_text_color]",
                "option"=>"btn_text_color",
                "flag"  =>"style_options",
                "id"    => "btn_text_color"
            )
        );  
        
        add_settings_field(
            'btn_border_color',
            'Button Border Color',
            array($this, 'add_text_field'),
            'style_button_settings_group',
            'button_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[btn_border_color]",
                "option"=>"btn_border_color",
                "flag"  =>"style_options",
                "id"    => "btn_border_color"
            )
        );      

        add_settings_field(
            'btn_width_height',
            'Button Width x Height',
            array($this, 'add_width_height_fields'),
            'style_button_settings_group',
            'button_styles',
            array(
                "type"  =>"number",
                "name"  =>"btn"
            )
        );      
        /*End Button Styles*/
        
        /*Input Styles*/
        add_settings_section(
            'input_styles',
            'Input Styles',
            false,
            'style_input_settings_group'
        );  

        add_settings_field(
            'input_bg_color',
            'Input Background Color',
            array($this, 'add_text_field'),
            'style_input_settings_group',
            'input_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[input_bg_color]",
                "option"=>"input_bg_color",
                "flag"  =>"style_options",
                "id"    => "input_bg_color"
            )
        );      
        
        add_settings_field(
            'input_text_color',
            'Input Text Color',
            array($this, 'add_text_field'),
            'style_input_settings_group',
            'input_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[input_text_color]",
                "option"=>"input_text_color",
                "flag"  =>"style_options",
                "id"    => "input_text_color"
            )
        );      
        
        add_settings_field(
            'input_border_color',
            'Input Border Color',
            array($this, 'add_text_field'),
            'style_input_settings_group',
            'input_styles',
            array(
                "type"  =>"text",
                "name"  =>"fah_style_field[input_border_color]",
                "option"=>"input_border_color",
                "flag"  =>"style_options",
                "id"    => "input_border_color"    
            )
        );      

        add_settings_field(
            'input_width_height',
            'Input Width x Height',
            array($this, 'add_width_height_fields'),
            'style_input_settings_group',
            'input_styles',
            array(
                "type"  =>"number",
                "name"  =>"input",
                "id"    => "input_height"
            )
        );
        
        add_settings_section(
            'woo_quote_custom_css',
            __('Custom Styles'),
            false,
            'custom_style_input_settings_group'
        );  
        
        add_settings_field(
            'woo_quote_custom_css',
            'Custom CSS',
            array($this, 'woo_quote_custom_css'),
            'custom_style_input_settings_group',
            'woo_quote_custom_css'
        );  
        
        /*End Input Styles*/
        /*End Style Tab*/
    }

    /**
     * Saves a setting form data
     */
    public function save_setting_form()
    {

        if(isset($_POST['fah_general_field'])) {
            update_option('fah_general_field', $_POST['fah_general_field']);
        }
        
        if(isset($_POST['fah_style_field'])) {
            update_option('fah_style_field', $_POST['fah_style_field']);
        }
        
        header('Content-Type: application/json');
        
        echo json_encode([
           'status' => 1,
            'message' => __('Settings have been updated successfully')
        ]);
        exit();
    }
    
    /**
     * rendering setting radio buttons
     *
     * @param      array  $attr   The attribute
     */
    public function radio_buttons($attr)
    {
        $field_value = $this->general_options[$attr['option']];
        
        $html = '<div class="fah-radio-container">';
        
        foreach($attr['values'] as $value => $label) {
            
            $default_value = isset($attr['default']) ? $attr['default'] : '';
        
            $field_value = $field_value ? $field_value : $default_value;
            
            $checked = $field_value == $value ? 'checked' : '';
            
            $html .= '<label>
                        <input 
                            type="radio"
                            name="fah_general_field['.$attr['option'].']" 
                            value="'.$value.'" '.$checked.' class="'.$attr['option'].'" /> <span>'.$label.'</span>
                    </label>';
        }
        
        $html .= '</div>';
        
        if(isset($attr['description']) && $attr['description']) {
            $html .= '<div class="fah-description">'.$attr['description'].'</div>';
        }
        
        $html .= '<div class="fah-info">'.(isset($attr['info']) ? $attr['info'] : '').'</div>';
        
        echo $html;
    }

    /**
     * rendering multiple checkboxes
     * @param      array  $attr   The attribute
     * return void print fields
     */

    public function multi_checkbox_buttons($attr)
    {
    	$field_value = $this->general_options[$attr['option']];

        $get_fah_general_field =get_option('fah_general_field');  // array
        $setorisset = $get_fah_general_field["fah_payment_methods"];
        if (isset($setorisset))
          {
            $field_value = $setorisset; 
          }
        $html = '<div class="fah-radio-container">';
        foreach($attr['values'] as $value => $label) {
            $default_value = isset($attr['default']) ? $attr['default'] : '';
            $field_value = $field_value ? $field_value : $default_value;
             if (isset($setorisset))
            {
                $checked = in_array($value, $field_value) ? 'checked' : '';
             }

            //$checked = $field_value == $value ? 'checked' : '';
            $html .= '<label>
                        <input 
                            type="checkbox"
                            name="fah_general_field['.$attr['option'].'][]" 
                            value="'.$value.'" '.$checked.' class="'.$attr['option'].'" /> <span>'.$label.'</span>
                    </label>';
        }
        
        $html .= '</div>';
        
        if(isset($attr['description']) && $attr['description']) {
            $html .= '<div class="fah-description">'.$attr['description'].'</div>';
        }
        
        $html .= '<div class="fah-info">'.(isset($attr['info']) ? $attr['info'] : '').'</div>';
        
        echo $html;
    }
    /**
     * For showing dropdown for contact form 7
     *
     * @param      array  $attr   The attribute
     */
    public function contact_form_list($attr)
    {
        $field_value = $this->general_options[$attr['option']];
		$contact_form = get_posts(['post_type' => 'wpcf7_contact_form']);
		$form_list = wp_list_pluck($contact_form,'post_title','ID');
		$element = '<select class="fah_input_field fah_select_field" ';
		foreach($attr as $key=>$attribute):
    		$element .= " $key='$attribute'";
		endforeach;
        	
		$element .= "><option value=''>".$attr['placeholder']."</option>";
		echo $element;
		foreach($form_list as $id=>$title):
			echo "<option value=$id";
            selected($id,$field_value);
            echo '>'.$title.'</option>';
		endforeach;
		echo '</select>';
	}

    /**
     * For showing width and height fields
     *
     * @param      array  $attr   The attribute
     */
    public function add_width_height_fields($attr)
    {	

        $name = $attr['name']."_width";
        $field_value = $this->style_options[$name];
        
        $default_value = isset($attr['default']) ? $attr['default'] : '';
        
        $field_value = $field_value ? $field_value : $default_value;
        
        $element .= '<input type="number" option="'.$attr['option'].'" maxlength="4" value="'.$field_value.'" name="fah_style_field['.$name.']" style="width: 60px;"/>&nbsp;&nbsp;x';

        $name = $attr['name']."_height";
        $field_value = $this->style_options[$name];
        $element .= '&nbsp;&nbsp;<input type="number" maxlength="4" option="'.$attr['option'].'" value="'.$field_value.'" name="fah_style_field['.$name.']"  style="width: 60px;"/>';
    	_e($element);
	}

    /**
     * For showing dropdown text field
     *
     * @param      array  $attr   The attribute
     */
    public function add_text_field($attr)
    {	
        $element = 
                    ($attr['type']=='textarea') ?
                    '<textarea>' : 
                    '<input class="fah_input_field '.$attr['option'].'" ';
    	
    	foreach($attr as $key=>$attribute):
    		if($key=='class'){ continue; }
    		$element .= " $key='$attribute'";
    	endforeach;

        $field_value = isset($attr['flag']) ? $this->style_options[$attr['option']] : $this->general_options[$attr['option']];

        $default_value = isset($attr['default']) ? $attr['default'] : '';
        
        $field_value = $field_value ? $field_value : $default_value;

    	$element = ($attr['type']=='textarea') ? $element.' value="'.$field_value.'">'.$field_value.'</textarea>' : $element;
    	$element .= ($attr['type']!='checkbox' && $attr['type']!='textarea') ? "value='$field_value'":null;
    	echo $element;
    	checked( $field_value, 'on' );
    	echo ($attr['type']!='textarea') ? '/>' : '';
        
        if(isset($attr['description']) && $attr['description']) {
            echo '<p class="fah-description">'.$attr['description'].'</p>';
        }
        
	}
    
    /**
     * For showing dropdown for all pages
     *
     * @param      array  $attr   The attribute
     */
    public function page_dropdown($attr)
    {
        $field_value = isset($this->general_options[$attr['option']]) ? $this->general_options[$attr['option']] : null;
        $pages = get_posts(['post_type' => 'page', 'posts_per_page' => -1]);
        $css_class = isset($attr['css_class']) ? $attr['css_class'] : '';
        $html = '<select name="fah_general_field['.$attr['option'].']" class="fah_input_field fah_select_field '.$css_class.'">';
        foreach($pages as $page) {
            $html .= '<option '.($field_value == $page->ID ? 'selected' : '').' value="'.$page->ID.'">'.get_the_title($page->ID).'</option>';
        }
        $html .= '</select>';
        
        echo $html;
        
        if(isset($attr['description']) && $attr['description']) {
            echo '<p class="fah-description">'.$attr['description'].'</p>';
        }
        
    }

    /**
     * For showing dropdown for all pages
     *
     * @param      array  $attr   The attribute
     */
    public function dropdown($attr)
    {
    	$field_value = isset($this->general_options[$attr['option']]) ? $this->general_options[$attr['option']] : null;
        $options = isset($attr['options']) && is_array($attr['options']) ? $attr['options'] : [];
        $css_class = isset($attr['css_class']) ? $attr['css_class'] : '';
        $html = '<select name="fah_general_field['.$attr['option'].']" class="fah_input_field fah_select_field '.$css_class.'">';
        foreach($options as $key => $option) {
            $html .= '<option '.($field_value == $key ? 'selected' : '').' value="'.$key.'">'.$option.'</option>';
        }
        $html .= '</select>';
        
        echo $html;
        
        if(isset($attr['description']) && $attr['description']) {
            echo '<p class="fah-description">'.$attr['description'].'</p>';
        }
        
    }

}

new FAH_Settings;