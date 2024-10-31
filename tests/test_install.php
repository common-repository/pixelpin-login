<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

class WPL_Test_Install extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_requirements()
	{
		$this->assertTrue( wpl_check_requirements() );
	}

	function test_tables()
	{
		global $wpdb;

		$test = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wplusersprofiles'" );
		$this->assertEquals( $wpdb->prefix . 'wplusersprofiles', $test );

		$test = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wpluserscontacts'" );
		$this->assertEquals( $wpdb->prefix . 'wpluserscontacts', $test );
	}

	function test_options_created()
	{
		$test = get_option( 'wpl_settings_redirect_url' );
		$this->assertEquals( home_url(), $test );

		$test = get_option( 'wpl_settings_buddypress_xprofile_map' );
		$this->assertEquals( '', $test );
	}

	function test_registration_enabled()
	{
		$test = get_option( 'wpl_settings_bouncer_registration_enabled' );
		$this->assertEquals( 1, $test );
	}

	function test_authentication_enabled()
	{
		$test = get_option( 'wpl_settings_bouncer_authentication_enabled' );
		$this->assertEquals( 1, $test );
	}

	function test_default_networks_enabled()
	{
	}

	function test_devmode_disabled()
	{
		$test = get_option( 'wpl_settings_development_mode_enabled' ) ? 1 : null;
		$this->assertNull( $test );
	}

	function test_debugmode_disabled()
	{
		$test = get_option( 'wpl_settings_debug_mode_enabled' ) ? 1 : null;
		$this->assertNull( $test );
	}
}
