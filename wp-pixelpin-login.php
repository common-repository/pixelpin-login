<?php
/*
Plugin Name: WordPress PixelPin Login
Plugin URI: https://en-gb.wordpress.org/plugins/pixelpin-login/
Description: Allow your visitors to comment and login with pixelpin.
Version: 1.3.0
Author: PixelPin Plugins
Author URI: https://github.com/PixelPinPlugins
License: MIT License
Text Domain: wordpress-pixelpin-login
Domain Path: /languages
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------


global $WORDPRESS_PIXELPIN_LOGIN_VERSION;
global $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
global $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
global $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

$WORDPRESS_PIXELPIN_LOGIN_VERSION = "1.3.0";


function wpl_plugin_version() {
	$WORDPRESS_PIXELPIN_LOGIN_VERSION = "1.3.0";
	$wplPluginVersion = "WordPress PixelPin Login " . $WORDPRESS_PIXELPIN_LOGIN_VERSION;
	
	return $wplPluginVersion;
}

// --------------------------------------------------------------------

/**
* This file might be used to :
*     1. Redefine WPL constants, so you can move WPL folder around.
*     2. Define WPL Pluggable PHP Functions. See http://miled.github.io/wordpress-social-login/developer-api-functions.html
*     5. Implement your WPL hooks.
*/
if( file_exists( WP_PLUGIN_DIR . '/wp-pixelpin-login-custom.php' ) )
{
	include_once( WP_PLUGIN_DIR . '/wp-pixelpin-login-custom.php' );
}

// --------------------------------------------------------------------

/**
* Define WPL constants, if not already defined
*/
defined( 'WORDPRESS_PIXELPIN_LOGIN_ABS_PATH' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_ABS_PATH', plugin_dir_path( __FILE__ ) );

defined( 'WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

defined( 'WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL' )
	|| define( 'WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL', WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'hybridauth/' );

// --------------------------------------------------------------------

/**
* Check for Wordpress 3.0
*/
function wpl_activate()
{
	if( ! function_exists( 'register_post_status' ) )
	{
		deactivate_plugins( basename( dirname( __FILE__ ) ) . '/' . basename (__FILE__) );

		wp_die( __( "This plugin requires WordPress 3.0 or newer. Please update your WordPress installation to activate this plugin.", 'wordpress-pixelpin-login' ) );
	}
}

register_activation_hook( __FILE__, 'wpl_activate' );

// --------------------------------------------------------------------

/**
* Attempt to install/migrate/repair WPL upon activation
*
* Create wpl tables
* Migrate old versions
* Register default components
*/
function wpl_install()
{
	wpl_database_install();

	wpl_update_compatibilities();

	wpl_register_components();
}

register_activation_hook( __FILE__, 'wpl_install' );

// --------------------------------------------------------------------

/**
* Add a settings to plugin_action_links
*/
function wpl_add_plugin_action_links( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}

	if( $file == $this_plugin )
	{
		$wpl_links  = '<a href="options-general.php?page=wordpress-pixelpin-login">' . __( "Settings" ) . '</a>';

		array_unshift( $links, $wpl_links );
	}

	return $links;
}

add_filter( 'plugin_action_links', 'wpl_add_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------

/**
* Add faq and user guide links to plugin_row_meta
*/
function wpl_add_plugin_row_meta( $links, $file )
{
	static $this_plugin;

	if( ! $this_plugin )
	{
		$this_plugin = plugin_basename( __FILE__ );
	}

	if( $file == $this_plugin )
	{
		$wpl_links = array(

		);

		return array_merge( $links, $wpl_links );
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'wpl_add_plugin_row_meta', 10, 2 );

// --------------------------------------------------------------------

/**
* Loads the plugin's translated strings.
*
* http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
*/
if( ! function_exists( 'wpl_load_plugin_textdomain' ) )
{
	function wpl_load_plugin_textdomain()
	{
		load_plugin_textdomain( 'wordpress-pixelpin-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

add_action( 'plugins_loaded', 'wpl_load_plugin_textdomain' );

// --------------------------------------------------------------------

/**
* _e() wrapper
*/
function _wpl_e( $text, $domain )
{
	echo __( $text, $domain );
}

// --------------------------------------------------------------------

/**
* __() wrapper
*/
function _wpl__( $text, $domain )
{
	return __( $text, $domain );
}

// --------------------------------------------------------------------

/* includes */

# WPL Setup & Settings
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.providers.php'            ); // List of supported providers (mostly provided by hybridauth library)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.database.php'             ); // Install/Uninstall WPL database tables
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.initialization.php'       ); // Check WPL requirements and register WPL settings
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/settings/wpl.compatibilities.php'      ); // Check and upgrade WPL database/settings (for older versions)

# Services & Utilities
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.authentication.php'       ); // Authenticate users via pixelpin networks. <- that's the most important script
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.mail.notification.php'    ); // Emails and notifications
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.user.avatar.php'          ); // Display users avatar
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.user.data.php'            ); // User data functions (database related)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.user.fields.php'		   );
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.utilities.php'            ); // Unclassified functions & utilities
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/services/wpl.watchdog.php'             ); // WPL logging agent

# WPL Widgets & Front-end interfaces
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.auth.widgets.php'          ); // Authentication widget generators (where WPL widget/icons are displayed)
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.users.gateway.php'         ); // Accounts linking + Profile Completion
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.error.pages.php'           ); // Generate WPL notices end errors pages
require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/widgets/wpl.loading.screens.php'       ); // Generate WPL loading screens

# WPL Admin interfaces
if( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) )
{
	require_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/admin/wpl.admin.ui.php'        ); // The entry point to WPL Admin interfaces
}

// --------------------------------------------------------------------
