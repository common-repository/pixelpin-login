<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

session_start();

global $wpdb;

$_SERVER['HTTP_HOST'] = 'localhost';

define( 'WORDPRESS_PIXELPIN_LOGIN_ABS_PATH', dirname( __FILE__ ) . '/../' );

$_tests_dir = getenv('WP_TESTS_DIR');

if ( ! $_tests_dir )
{
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

echo "Booting...\n";
echo "PHP:session_id()=" . session_id() . "\n";
echo "WPT:WP_TESTS_DIR=" . $_tests_dir . "\n";
echo "WPL:WORDPRESS_PIXELPIN_LOGIN_ABS_PATH=" . realpath( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH ) . "\n";

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin()
{
	require_once WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'wp-pixelpin-login.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

echo "Activate WPL...\n";

activate_plugin( 'wordpress-pixelpin-login/wp-pixelpin-login.php' );

echo "Uninstall WPL...\n";

wpl_database_uninstall();

echo "Install WPL...\n";

wpl_database_install();

echo "ReInstall WPL...\n";

wpl_install();

echo "Testing WPL...\n";
