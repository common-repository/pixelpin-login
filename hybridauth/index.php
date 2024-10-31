<?php
/*!
* WordPress PixelPin Login
* 
* 2017 PixelPin and contributors https://github.com/PixelPinPlugins/WordPress-PixelPin-Login
* 
* Original Authors of WSL
* -----------------------
* http://hybridauth.sourceforge.net/wpl/index.html | http://github.com/hybridauth/WordPress-Social-Login
*    (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/extend/plugins/wordpress-social-login/
*/

// ------------------------------------------------------------------------
//	WPL End Point
// ------------------------------------------------------------------------

/**
* If for whatever reason you want to debug apis call made by hybridauth during the auth process, you can add the block of code below.
*
* <code>
*    include_once( '/path/to/file/wp-load.php' );
*    define( 'WORDPRESS_PIXELPIN_LOGIN_DEBUG_API_CALLS', true );
*    add_action( 'wpl_log_provider_api_call', 'wpl_watchdog_wpl_log_provider_api_call', 10, 8 );
*    do_action( 'wpl_log_provider_api_call', 'ENDPOINT', 'Hybridauth://endpoint', null, null, null, null, $_SERVER["QUERY_STRING"] );
* </code>
*/

//- Re-parse the QUERY_STRING for custom endpoints.
if( defined( 'WORDPRESS_PIXELPIN_LOGIN_CUSTOM_ENDPOINT' ) && ! isset( $_REQUEST['hauth_start'] ) ) 
{
	$_SERVER["QUERY_STRING"] = 'hauth_done=' . WORDPRESS_PIXELPIN_LOGIN_CUSTOM_ENDPOINT . '&' . str_ireplace( '?', '&', $_SERVER["QUERY_STRING"] );

	parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
}

//- Hybridauth required includes
require_once( "Hybrid/Storage.php"   );
require_once( "Hybrid/Error.php"     );
require_once( "Hybrid/Auth.php"      );
require_once( "Hybrid/Exception.php" );
require_once( "Hybrid/Endpoint.php"  );


//- Custom WPL endpoint class
require_once( "endpoints/WPL_Endpoint.php" );


//- Entry point to the End point 
WPL_Hybrid_Endpoint::process();
