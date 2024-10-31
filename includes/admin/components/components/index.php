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
* Components Manager 
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_help()
{
	// HOOKABLE: 
	do_action( "wpl_component_help_start" );

	include "wpl.components.help.setup.php";

	wpl_component_components_setup();

	// HOOKABLE: 
	do_action( "wpl_component_help_end" );
}

wpl_component_help();

// --------------------------------------------------------------------	
