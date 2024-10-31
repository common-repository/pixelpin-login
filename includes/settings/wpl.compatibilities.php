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
* Check and upgrade compatibilities from old WPL versions
*
* Here we attempt to:
*	- set to default all settings when WPL is installed
*	- make WPL compatible when updating from older versions, by registering new options
*
* Side note: Over time, the number of options have become too long, and as you can notice
*            things are not optimal. If you have any better idea on how to tackle this issue,
*            please don't hesitate to share it.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Check and upgrade compatibilities from old WPL versions
*/
function wpl_update_compatibilities()
{
	delete_option( 'wpl_settings_development_mode_enabled' );
	delete_option( 'wpl_settings_debug_mode_enabled' );

	update_option( 'wpl_settings_welcome_panel_enabled', 1 );

	if( ! get_option( 'wpl_settings_redirect_url' ) )
	{
		update_option( 'wpl_settings_redirect_url', home_url() );
	}

	if( ! get_option( 'wpl_settings_force_redirect_url' ) )
	{
		update_option( 'wpl_settings_force_redirect_url', 2 );
	}

	if( ! get_option( 'wpl_settings_connect_with_label' ) )
	{
		update_option( 'wpl_settings_connect_with_label', _wpl__("Connect with PixelPin:", 'wordpress-pixelpin-login') );
	}

	if( ! get_option( 'wpl_settings_users_avatars' ) )
	{
		update_option( 'wpl_settings_users_avatars', 1 );
	}

	if( ! get_option( 'wpl_settings_use_popup' ) )
	{
		update_option( 'wpl_settings_use_popup', 2 );
	}

	if( ! get_option( 'wpl_settings_widget_display' ) )
	{
		update_option( 'wpl_settings_widget_display', 1 );
	}

	if( ! get_option( 'wpl_settings_authentication_widget_css' ) )
	{
		update_option( 'wpl_settings_authentication_widget_css', ".wp-pixelpin-login-connect-with {}\n.wp-pixelpin-login-provider-list {}\n.wp-pixelpin-login-provider-list a {}\n.wp-pixelpin-login-provider-list img {}\n.wpl_connect_with_provider {}" );
	}

	# bouncer settings
	if( ! get_option( 'wpl_settings_bouncer_registration_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_registration_enabled', 1 );
	}

	if( ! get_option( 'wpl_settings_bouncer_authentication_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_authentication_enabled', 1 );
	}

	if( ! get_option( 'wpl_settings_bouncer_accounts_linking_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_accounts_linking_enabled', 1 );
	}

	if( ! get_option( 'wpl_settings_bouncer_profile_completion_require_email' ) )
	{
		update_option( 'wpl_settings_bouncer_profile_completion_require_email', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_profile_completion_change_username' ) )
	{
		update_option( 'wpl_settings_bouncer_profile_completion_change_username', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_profile_completion_hook_extra_fields' ) )
	{
		update_option( 'wpl_settings_bouncer_profile_completion_hook_extra_fields', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_moderation_level' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_moderation_level', 1 );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_membership_default_role' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_membership_default_role', "default" );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_domain_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_domain_enabled', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_domain_text_bounce' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_domain_text_bounce', _wpl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-pixelpin-login') );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_email_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_email_enabled', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_email_text_bounce' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_email_text_bounce', _wpl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-pixelpin-login') );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_profile_enabled' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_profile_enabled', 2 );
	}

	if( ! get_option( 'wpl_settings_bouncer_new_users_restrict_profile_text_bounce' ) )
	{
		update_option( 'wpl_settings_bouncer_new_users_restrict_profile_text_bounce', _wpl__("<strong>This website is restricted to invited readers only.</strong><p>It doesn't look like you have been invited to access this site. If you think this is a mistake, you might want to contact the website owner and request an invitation.<p>", 'wordpress-pixelpin-login') );
	}

	# contacts import
	if( ! get_option( 'wpl_settings_buddypress_enable_mapping' ) )
	{
		update_option( 'wpl_settings_buddypress_enable_mapping', 2 );
	}

	# buddypress profile mapping
	if( ! get_option( 'wpl_settings_buddypress_xprofile_map' ) )
	{
		update_option( 'wpl_settings_buddypress_xprofile_map', '' );
	}

	global $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
	$nok = true;
	foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
	{
		$provider_id = $item["provider_id"];

		if( get_option( 'wpl_settings_' . $provider_id . '_enabled' ) )
		{
			$nok = false;
		}
	}

	if( $nok )
	{
		foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
		{
			$provider_id = $item["provider_id"];

			if( isset( $item["default_network"] ) && $item["default_network"] ){
				update_option( 'wpl_settings_' . $provider_id . '_enabled', 1 );
			}
		}
	}
}

// --------------------------------------------------------------------

/**
* Old junk
*
* Seems like some people are using WPL _internal_ functions for some reason...
*
* Here we keep few of those old/depreciated/undocumented/internal functions, so their websites
* doesn't break when updating to newer versions.
*
* TO BE REMOVED AS OF WPL 3.0
**
* Ref: http://miled.github.io/wordpress-social-login/developer-api-migrating-2.2.html
*/

// 2.1.6
function wpl_render_login_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); return wpl_render_auth_widget(); }
function wpl_render_comment_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_render_login_form_login_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_render_login_form_login_on_register_and_login(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_render_login_form_login(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_shortcode_handler(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); return wpl_shortcode_wordpress_pixelpin_login(); }

// 2.2.2
function wpl_render_wpl_widget_in_comment_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_render_wpl_widget_in_wp_login_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_render_wpl_widget_in_wp_register_form(){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); wpl_action_wordpress_pixelpin_login(); }
function wpl_user_custom_avatar($avatar, $mixed, $size, $default, $alt){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); return wpl_get_wp_user_custom_avatar($html, $mixed, $size, $default, $alt); }
function wpl_bp_user_custom_avatar($html, $args){ wpl_deprecated_function( __FUNCTION__, '2.2.3' ); return wpl_get_bp_user_custom_avatar($html, $args); }

// nag about it
function wpl_deprecated_function( $function, $version )
{
	// user should be admin and logged in
	if( current_user_can('manage_options') )
	{
		trigger_error( sprintf( __('%1$s is <strong>deprecated</strong> since WordPress PixelPin Login %2$s! For more information, check WPL Developer API - Migration.'), $function, $version ), E_USER_NOTICE );
	}
}

// --------------------------------------------------------------------
