<?php
/*!
* WordPress PixelPin Login
*
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2014 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

class WPL_Test_Session extends WP_UnitTestCase
{
	function setUp()
	{
		parent::setUp();
	}

	function tearDown()
	{
		parent::tearDown();
	}

	function test_wpl_version()
	{
		global $WORDPRESS_PIXELPIN_LOGIN_VERSION;

		$this->assertEquals( $_SESSION["wpl::plugin"], "WordPress PixelPin Login " . $WORDPRESS_PIXELPIN_LOGIN_VERSION );
	}
}
