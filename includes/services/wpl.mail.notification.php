<?php
/*!
* WordPress PixelPin Login
* 2017 PixelPin and contributors https://github.com/PixelPinPlugins/WordPress-PixelPin-Login
*
* Original Authors of WSL
* -----------------------
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/** 
* Email notifications to send. so far only the admin one is implemented
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Send a notification to blog administrator when a new user register using WPL 
*
* also borrowed from http://wordpress.org/extend/plugins/oa-social-login/
* 
* Note: 
*   You may redefine this function
*/
if( ! function_exists( 'wpl_admin_notification' ) )
{
	function wpl_admin_notification( $user_id, $provider )
	{
		//Get the user details
		$user = new WP_User($user_id);
		$user_login = stripslashes( $user->user_login );

		// The blogname option is escaped with esc_html on the way into the database
		// in sanitize_option we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$message  = sprintf(__('New user registration on your site: %s', 'wordpress-pixelpin-login'), $blogname        ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'                          , 'wordpress-pixelpin-login'), $user_login      ) . "\r\n";
		$message .= sprintf(__('Provider: %s'                          , 'wordpress-pixelpin-login'), $provider        ) . "\r\n";
		$message .= sprintf(__('Profile: %s'                           , 'wordpress-pixelpin-login'), $user->user_url  ) . "\r\n";
		$message .= sprintf(__('Email: %s'                             , 'wordpress-pixelpin-login'), $user->user_email) . "\r\n";
		$message .= "\r\n--\r\n";
		$message .= "WordPress PixelPin Login\r\n";
		$message .= "http://wordpress.org/extend/plugins/wordpress-social-login/\r\n";

		@ wp_mail(get_option('admin_email'), '[WordPress PixelPin Login] '.sprintf(__('[%s] New User Registration', 'wordpress-pixelpin-login'), $blogname), $message);
	}
}

// --------------------------------------------------------------------
