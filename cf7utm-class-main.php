<?php

class CF7_UTM_Tracking {
	public static $PLUGIN_URL;
	public static $VERSION = '1.4';
    
	public static $instance;
    
    public static function instance() {
        if ( ! isset( self::$instance ) )
            return self::$instance = new CF7_UTM_Tracking();

        return self::$instance;
    }
    
    
	public function __construct () {
		self::$PLUGIN_URL = plugin_dir_url(__FILE__);
		//add_action("wpcf7_before_send_mail", array($this, "add_utm_to_wpcf7") );
		//add_filter("wpcf7_mail_components", array($this, "add_utm_to_wpcf7"), 10 , 2 );
		add_action("wp_enqueue_scripts", array($this, "assets") );		
		
		if ( is_admin() ) {
			add_action("admin_menu", array('CF7_UTM_Tracking_Admin', "init") );

		}

        add_action('wpcf7_init', function (){
            wpcf7_add_form_tag('utm_and_referer', [__CLASS__, 'form_tag_handler']);
        });

        /**
         * A tag to be used in "Mail" section so the user receives the special tag
         * [posts]
         */
        add_filter('wpcf7_special_mail_tags', [$this, 'wpcf7_mail_tag'], 10, 3);
        add_filter('wpcf7_collect_mail_tags', [$this, 'wpcf7_collect_mail_tags__filter'], 11, 3);
	}


    /**
     * @param $mailtags
     * @param $args
     * @param $cf7
     * @return array
     * @since  1.3
     */
    function wpcf7_collect_mail_tags__filter( $mailtags, $args, $cf7 ) {
        $mailtags[] = 'utm_and_referer';
        return $mailtags;
    }


    static function form_tag_handler($tag) {
        return '';
    }


    function wpcf7_mail_tag($output, $name, $html)
    {
        $name = preg_replace('/^wpcf7\./', '_', $name); // for back-compat
        $submission = WPCF7_Submission::get_instance();
        if (! $submission) {
            return $output;
        }

        if ('utm_and_referer' == $name) {
            return $this->get_utm_tags($html);
        }
        return $output;
    }

    /**
     * Add all data from Cookies to EMail body
     *
     * @param bool $html_format
     * @return mixed
     */
	public function get_utm_tags ( $html_format = false ) {
		$utm = $this->_parse_utmz();
		
		$utm_msg = '';
        $utm_params_msg = [];

		if ( !empty($_COOKIE['_referrer'])) {
			$utm_msg = '#Visitor referrer# ' . esc_attr($_COOKIE['_referrer']) . PHP_EOL . PHP_EOL;
		}
		if ( !empty($_COOKIE['_landing'])) {
			$utm_msg = '#Landing page (first page)# ' . esc_attr($_COOKIE['_landing']) . PHP_EOL . PHP_EOL;
		}
		
        $_gclid = false;
		if ( !empty($_COOKIE['_gclid']) && $_gclid = sanitize_text_field($_COOKIE['_gclid']) ) {
			$utm_msg .= '#Visitor gclid# ' . $_gclid . PHP_EOL . PHP_EOL;		
		}
		
		if ( is_array($utm) ) {
			$utm_params_msg = array();
			foreach($utm as $utm_key => $utm_tag) {
				$utm_params_msg[] = sanitize_title($utm_key) . ' = ' . esc_attr($utm_tag);
			}
			$utm_msg .= '#UTM Tracking#' . PHP_EOL . implode (PHP_EOL, $utm_params_msg);
		}			
	
//		if ($mail_params['body']) {
//			// IF mail format is HTML then convert /n to </br>
//			/*$mail = $WPCF7_ContactForm->prop('mail');
//			if ( $mail['use_html'] ) {
//				$utm_msg = nl2br( $utm_msg );
//			}*/
//			$mail_params['body'] .= PHP_EOL  . '--' . PHP_EOL  . $utm_msg;
//
//			//var_dump($mail_params['body']);
//		}

		$settings = array( 
			'last_sent' => current_time( 'mysql' ),
			'last_gclid' => $_gclid ? $_gclid : '-',
			'last_utm' => is_array($utm_params_msg) ? implode(' | ', $utm_params_msg) : '-',
			'last_referrer' => isset($_COOKIE['_referrer']) ? sanitize_text_field($_COOKIE['_referrer']) : '-',
			'last_landing' => isset($_COOKIE['_landing']) ? sanitize_text_field($_COOKIE['_landing']) : '-',
		);
		self::update_settings($settings);

		return $html_format ? nl2br( $utm_msg ) : $utm_msg;
	}

  
	/**
	 * Parse utmz cookie into variables
	 */
	private function _parse_utmz() {
  
		if (isset($_COOKIE['_utmz_cf7'])) {
			$utmz = $_COOKIE['_utmz_cf7'];
		} elseif (isset($_COOKIE['__utmz'])) {		
			$utmz = $_COOKIE['__utmz'];	
		} else {
			return false;  
		}
		$UTM_arr = array();
		
		//Break cookie in half
		$utmz_b = strstr($utmz, 'u');
	 
		//assign variables to first half of cookie
		//list($this->utmz_domainHash, $this->utmz_timestamp, $this->utmz_sessionNumber, $this->utmz_campaignNumber) = explode('.', $utmz_a);
		
		//break apart second half of cookie
		$utmzPairs = array();
		$z = explode('|', $utmz_b);
		foreach ($z as $value) {
		  $v = explode('=', $value);
		  $utmzPairs[$v[0]] = $v[1];
		}

		var_dump($utmzPairs);
		
		//Variable assignment for second half of cookie
		foreach ($utmzPairs as $key => $value) {
		  switch ($key) {
			case 'utmcsr':
			case 'source':
			    if ( $UTM_arr['utm_source'] && !$value ) {
                    break;
                }
			  $UTM_arr['utm_source'] = $value;
			  break;
			case 'utmcmd':
			case 'medium':
              if ( $UTM_arr['utm_medium'] && !$value ) {
                  break;
              }
			  $UTM_arr['utm_medium'] = $value;
			  break;
			case 'utmctr':
			  $UTM_arr['utm_term'] = $value;
			  break;
			case 'utmcct':
			  $UTM_arr['utm_content'] = $value;
			  break;
			case 'utmccn':
			  $UTM_arr['utm_campaign'] = $value;
			  break;
			case 'utmgclid':
			  $UTM_arr['utm_gclid'] = $value;
			  break;
			case 'area':
			  $UTM_arr['area'] = $value;
			  break;
			default:
			  //do nothing
		  }
		}

		var_dump($UTM_arr);

		return $UTM_arr;
    
	}

	public function assets () {
	// Our code will go here
		wp_enqueue_script( 'cf7-utm', CF7_UTM_Tracking::$PLUGIN_URL . 'assets/traffic_source2.min.js', array(), CF7_UTM_Tracking::$VERSION, true);
	}	
	
	public static function get_opt ($key, $default = false) {
		$option = get_option( 'cf7-utm', array() );
		if ( isset($option[$key]) ) {
			return $option[$key];
		}
		return $default;		
	}	
	
	public static function set_opt ($key, $val) {
		$settings = get_option( 'cf7-utm', array() );
		if ( isset($settings[$key]) ) {
			$settings[$key] = sanitize_text_field($val);
		}
		return update_option('cf7-utm', $settings);;		
	}	
	
	public static function update_settings ($settings) {
		return update_option('cf7-utm', $settings);;		
	}
	
	public static function activate () {
		add_option( 'cf7-utm', '', '', 'no' );
	}	
	
	public static function deactivate () {
		delete_option( 'cf7-utm' );
	}

}
