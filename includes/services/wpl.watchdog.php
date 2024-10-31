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
* WPL logging agent
*
* This is an utility to Logs WPL authentication process to a file or database.
*
* Note:
*   Things ain't optimized here but will do for now.
*/

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wpl_watchdog_init()
{
	if( ! get_option( 'wpl_settings_debug_mode_enabled' ) )
	{
		return;
	}

	define( 'WORDPRESS_PIXELPIN_LOGIN_DEBUG_API_CALLS', true );

	add_action( 'wpl_process_login_start', 'wpl_watchdog_wpl_process_login' );
	add_action( 'wpl_process_login_begin_start', 'wpl_watchdog_wpl_process_login_begin_start' );
	add_action( 'wpl_process_login_end_start', 'wpl_watchdog_wpl_process_login_end_start' );

	add_action( 'wpl_hook_process_login_before_hybridauth_authenticate', 'wpl_watchdog_wpl_hook_process_login_before_hybridauth_authenticate', 10, 2 );
	add_action( 'wpl_hook_process_login_after_hybridauth_authenticate', 'wpl_watchdog_wpl_hook_process_login_after_hybridauth_authenticate', 10, 2 );

	add_action( 'wpl_process_login_end_get_user_data_start', 'wpl_watchdog_wpl_process_login_end_get_user_data_start', 10, 2 );

	add_action( 'wpl_process_login_complete_registration_start', 'wpl_watchdog_wpl_process_login_complete_registration_start', 10, 3 );

	add_action( 'wpl_process_login_create_wp_user_start', 'wpl_watchdog_wpl_process_login_create_wp_user_start', 10, 4 );
	add_action( 'wpl_hook_process_login_alter_wp_insert_user_data', 'wpl_watchdog_wpl_hook_process_login_alter_wp_insert_user_data', 10, 3 );
	add_action( 'wpl_process_login_update_wpl_user_data_start', 'wpl_watchdog_wpl_process_login_update_wpl_user_data_start', 10, 5 );

	add_action( 'wpl_process_login_authenticate_wp_user_start', 'wpl_watchdog_wpl_process_login_authenticate_wp_user_start', 10, 5 );

	add_action( 'wpl_hook_process_login_before_wp_set_auth_cookie', 'wpl_watchdog_wpl_hook_process_login_before_wp_set_auth_cookie', 10, 4 );
	add_action( 'wpl_hook_process_login_before_wp_safe_redirect', 'wpl_watchdog_wpl_hook_process_login_before_wp_safe_redirect', 10, 5 );

	add_action( 'wpl_process_login_render_error_page', 'wpl_watchdog_wpl_process_login_render_error_page', 10, 4 );
	add_action( 'wpl_process_login_render_notice_page', 'wpl_watchdog_wpl_process_login_render_notice_page', 10, 1 );

	add_action( 'wpl_log_provider_api_call', 'wpl_watchdog_wpl_log_provider_api_call', 10, 8 );
}

// --------------------------------------------------------------------

function wpl_watchdog_log_action( $action_name, $action_args = array(), $user_id = 0 )
{
	$provider = wpl_process_login_get_selected_provider();

	if( ! $provider )
	{
		if( isset( $_REQUEST['hauth_start'] ) ) $provider = $_REQUEST['hauth_start'];
		if( isset( $_REQUEST['hauth_done'] ) ) $provider = $_REQUEST['hauth_done'];
	}

	$action_args[] = "Backtrace: " . wpl_generate_backtrace();
	$action_args[] = 'USER: ' . get_current_user() . '. PID: ' . getmypid() . '. MEM: ' . ceil( memory_get_usage() / 1024 ) . 'KB.';

	if( get_option( 'wpl_settings_debug_mode_enabled' ) == 1 )
	{
		return wpl_watchdog_log_to_file( $action_name, $action_args, $user_id, $provider );
	}

	wpl_watchdog_log_to_database( $action_name, $action_args, $user_id, $provider );
}

// --------------------------------------------------------------------

function wpl_watchdog_log_to_file( $action_name, $action_args = array(), $user_id = 0, $provider = '' )
{
	$wp_upload_dir = wp_upload_dir();
	wp_mkdir_p( $wp_upload_dir['basedir'] . '/wordpress-pixelpin-login' );
	$wpl_path = $wp_upload_dir['basedir'] . '/wordpress-pixelpin-login';
	@file_put_contents( $wpl_path . '/.htaccess', "Deny from all" );
	@file_put_contents( $wpl_path . '/index.html', "" );

	$extra = '';
	if( in_array( $action_name, array( 'dbg:provider_api_call', 'wpl_hook_process_login_alter_wp_insert_user_data', 'wpl_process_login_update_wpl_user_data_start', 'wpl_process_login_authenticate_wp_user' ) ) )
	$extra = print_r( $action_args, true );

	$log_path = $wpl_path . '/auth-log-' . date ('d.m.Y') . '.log';
	file_put_contents( $log_path, "\n" . implode( ' -- ', array( session_id(), date('d-m-Y H:i:s'), $_SERVER['REMOTE_ADDR'], $provider, $user_id, $action_name, wpl_get_current_url(), $extra ) ), FILE_APPEND );
}

// --------------------------------------------------------------------

function wpl_watchdog_log_to_database( $action_name, $action_args = array(), $user_id = 0, $provider = '' )
{
	global $wpdb;

	$sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wplwatchdog` ( 
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `session_id` varchar(50) NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `user_ip` varchar(50) NOT NULL,
			  `url` varchar(450) NOT NULL,
			  `provider` varchar(50) NOT NULL,
			  `action_name` varchar(255) NOT NULL,
			  `action_args` text NOT NULL,
			  `is_connected` int(11) NOT NULL,
			  `created_at` varchar(50) NOT NULL,
			  PRIMARY KEY (`id`) 
			)"; 

	$wpdb->query( $sql );

	$wpdb->insert(
		"{$wpdb->prefix}wplwatchdog", 
			array( 
				"session_id"    => session_id(),
				"user_id"       => $user_id,
				"user_ip"       => $_SERVER['REMOTE_ADDR'],
				"url"           => wpl_get_current_url(), 
				"provider"      => $provider, 
				"action_name"   => $action_name,
				"action_args"   => json_encode( $action_args ),
				"is_connected"  => get_current_user_id() ? 1 : 0, 
				"created_at"    => microtime( true ), 
			)
		);
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login()
{
	wpl_watchdog_log_action( 'wpl_process_login' );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_begin_start()
{
	wpl_watchdog_log_action( 'wpl_process_login_begin_start' );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_end_start()
{
	wpl_watchdog_log_action( 'wpl_process_login_end_start' );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_end_get_user_data_start( $provider, $redirect_to )
{
	wpl_watchdog_log_action( 'wpl_process_login_end_get_user_data_start', array( $provider, $redirect_to ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_hook_process_login_before_hybridauth_authenticate( $provider, $config )
{
	wpl_watchdog_log_action( 'wpl_hook_process_login_before_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_hook_process_login_after_hybridauth_authenticate( $provider, $config )
{
	wpl_watchdog_log_action( 'wpl_hook_process_login_after_hybridauth_authenticate', array( $provider, $config ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_complete_registration_start( $provider, $redirect_to, $hybridauth_user_profile )
{
	wpl_watchdog_log_action( 'wpl_process_login_complete_registration_start', array( $provider, $redirect_to, $hybridauth_user_profile ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_create_wp_user_start( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email )
{
	wpl_watchdog_log_action( 'wpl_process_login_create_wp_user_start', array( $provider, $hybridauth_user_profile, $request_user_login, $request_user_email ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_hook_process_login_alter_wp_insert_user_data( $userdata, $provider, $hybridauth_user_profile )
{
	wpl_watchdog_log_action( 'wpl_hook_process_login_alter_wp_insert_user_data', array( $userdata, $provider, $hybridauth_user_profile ) );

	return $userdata;
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_update_wpl_user_data_start( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile  )
{
	wpl_watchdog_log_action( 'wpl_process_login_update_wpl_user_data_start', array( $is_new_user, $user_id, $provider, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_authenticate_wp_user_start( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile  )
{
	wpl_watchdog_log_action( 'wpl_process_login_authenticate_wp_user_start', array( $user_id, $provider, $redirect_to, $adapter, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_hook_process_login_before_wp_set_auth_cookie( $user_id, $provider, $hybridauth_user_profile  )
{
	wpl_watchdog_log_action( 'wpl_hook_process_login_before_wp_set_auth_cookie', array( $user_id, $provider, $hybridauth_user_profile ), $user_id );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_hook_process_login_before_wp_safe_redirect( $user_id, $provider, $hybridauth_user_profile, $redirect_to )
{
	wpl_watchdog_log_action( 'wpl_hook_process_login_before_wp_safe_redirect', array( $user_id, $provider, $hybridauth_user_profile, $redirect_to ), $user_id );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_render_error_page( $e, $config, $provider, $adapter )
{
	wpl_watchdog_log_action( 'wpl_process_login_render_error_page', array( $e, $config, $provider, $adapter ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_process_login_render_notice_page( $message )
{
	wpl_watchdog_log_action( 'wpl_process_login_render_notice_page', array( $message ) );
}

// --------------------------------------------------------------------

function wpl_watchdog_wpl_log_provider_api_call( $client, $url, $method, $post_data, $http_code, $http_info, $http_response )
{
	wpl_watchdog_log_action( 'dbg:provider_api_call', array( $client, $url, $method, $post_data, $http_code, $http_info, $http_response ) );
}

// --------------------------------------------------------------------
