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
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_contacts_settings_sidebar()
{
	$sections = array(
		'what_is_this' => 'wpl_component_contacts_settings_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wpl_component_contacts_settings_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_contacts_settings_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wpl_component_contacts_settings_sidebar_sections' );
}

// --------------------------------------------------------------------	


// --------------------------------------------------------------------	
