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
* Wannabe Users Manager module
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_users()
{
	// HOOKABLE: 
	do_action( "wpl_component_users_start" );

	include "wpl.components.users.list.php";
	include "wpl.components.users.profiles.php";

	if( isset( $_REQUEST["uid"] ) && $_REQUEST["uid"] ){
		$user_id = (int) $_REQUEST["uid"];

		wpl_component_users_profiles( $user_id );
	}
	else{
		wpl_component_users_list();
	}

	// HOOKABLE: 
	do_action( "wpl_component_users_end" );
}

wpl_component_users();

// --------------------------------------------------------------------	
