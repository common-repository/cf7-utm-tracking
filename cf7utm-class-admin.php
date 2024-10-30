<?php

class CF7_UTM_Tracking_Admin {
	
	public static function init() {
		add_submenu_page( 'wpcf7', 'UTM tracking', 'CF7 UTM tracking', 'manage_options', 'cf7-utm-tracking', array('CF7_UTM_Tracking_Admin', 'render_page') );
		//add_menu_page( 'CF7 UTM tracking', 'CF7 UTM tracking' , 'manage_options', 'cf7_utm_tracking', array('CF7_UTM_Tracking_Admin', 'render_page'), false, 75);

        if ( isset($_GET['action']) && $_GET['action'] === 'dismiss_cf7_utm_message' ) {
            self::dismiss_admin_message();
        }

        add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}
	
	public static function render_page() {
		include 'templates/admin.php';
	}

    /**
     * Admin message
     * @since 1.3
     */
	public static function admin_notices() {
        if ( ! get_option( 'cf7_utm_v1_3_tag' ) ) {
            echo '<div class="notice notice-info notification-notice"><p>';

            printf( '"Contact Form 7 UTM tracking" warning: since the version 1.3 UTM tags aren\'t automatically added to the emails. You have to <a href="%s" target="_blank">put manually the tag</a> <code>[utm_and_referer]</code>!',
                'https://res.cloudinary.com/dxo61viuo/image/upload/v1559999389/wp-vote.net/CF7_utm_mail_tag.jpg');

            echo '<a href="' . add_query_arg( array('action'=>'dismiss_cf7_utm_message', '_wpnonce' => wp_create_nonce('cf7-utm-dismiss')) ) . '" class="dismiss-beg-message button button-primary" type="submit" style="float: right;">';
            echo 'I already did this >';
            echo '</a>';

            echo '</p></div>';

        }

	}

    /**
     * Dismiss message
     * @since 1.3
     */
    public static function dismiss_admin_message() {

        check_admin_referer( 'cf7-utm-dismiss' );

        update_option( 'cf7_utm_v1_3_tag', 'dismissed' );

    }

}