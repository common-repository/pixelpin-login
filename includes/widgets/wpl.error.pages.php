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
* Generate WPL notices end errors pages.
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Display a simple notice to the user and kill WordPress execution
*
* This function is mainly used by bouncer
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*
*   The $message to display for users is passed as a parameter.
*/
if( ! function_exists( 'wpl_render_notice_page' ) )
{
	function wpl_render_notice_page( $message )
	{
		$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';
		
		$provider = 'pixelpin';
		// get idp adapter
		$adapter = wpl_process_login_get_provider_adapter( $provider );

		$config = $adapter->config;

		// if user authenticated successfully with pixelpin network
		if( $adapter->isUserConnected() )
		{
			// grab user profile via hybridauth api
			$hybridauth_user_profile = $adapter->getUserProfile();
		}

		$hybridauth_user_email       = sanitize_email( $hybridauth_user_profile->email );
		$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';
		$requested_user_email        = isset( $_REQUEST["user_email"] ) ? trim( $_REQUEST["user_email"] ) : $hybridauth_user_email;
		$requested_user_email        = apply_filters( 'wpl_new_users_gateway_alter_requested_email', $requested_user_email );
		$hybridauth_user_email       = sanitize_email( $hybridauth_user_profile->email );
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?></title>
		<style type="text/css">
			body {
				background: #f1f1f1;
			}
			h4 {
				color: #666;
				font: 20px "Open Sans", sans-serif;
				margin: 0;
				padding: 0;
				padding-bottom: 12px;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
			p {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#notice-page {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top: 50px;
			}
			#notice-page code {
				font-family: Consolas, Monaco, monospace;
			}
			.notice-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
		</style>
	<head>
	<body>
		<div id="notice-page">
			<table width="100%" border="0">
				<tr>
					<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
				</tr>
				<tr>
					<td align="center">
						<div class="notice-message">
							<?php echo nl2br( $message ); ?>
							<?php if ( wpl_wp_email_exists( $requested_user_email ) )
								{
									echo _wpl__( 'If you\'ve signed up to this website using PixelPin but have no password, <a href="/wp-login.php?action=lostpassword">you\'ll need to obtain a new password.</a>', 'wordpress-pixelpin-login' );
								}
							?>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<?php
			// Development mode on?
			if( get_option( 'wpl_settings_development_mode_enabled' ) )
			{
				wpl_render_error_page_debug_section();
			}
		?>
	</body>
</html>
<?php
		die();
	}
}

// --------------------------------------------------------------------

/**
* Display an error page to the user and kill WordPress execution
*
* This function differ than wpl_render_notice_page as it have some extra parameters and also should allow debugging
*
* This function is used when WPL fails to authenticated a user with pixelpin networks
*
* Note:
*   In case you want to customize the content generated, you may redefine this function
*   Just make sure the script DIES at the end.
*
*   The $message to display for users is passed as a parameter and it's required.
*/
if( ! function_exists( 'wpl_render_error_page' ) )
{
	function wpl_render_error_page( $message, $notes = null, $provider = null, $api_error = null, $php_exception = null )
	{
		$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';
?>
<!DOCTYPE html>
	<head>
		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php bloginfo('name'); ?> - <?php _wpl_e("Oops! We ran into an issue", 'wordpress-pixelpin-login') ?>.</title>
		<style type="text/css">
			body {
				background: #f1f1f1;
			}
			h4 {
				color: #666;
				font: 20px "Open Sans", sans-serif;
				margin: 0;
				padding: 0;
				padding-bottom: 7px;
			}
			p {
				font-size: 14px;
				line-height: 1.5;
				margin: 15px 0;
				line-height: 25px;
				padding: 10px;
				text-align:left;
			}
			a {
				color: #21759B;
				text-decoration: none;
			}
			a:hover {
				color: #D54E21;
			}
			#error-page {
				background: #fff;
				color: #444;
				font-family: "Open Sans", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top: 50px;
			}
			#error-page pre {
				max-width: 680px;
				overflow: scroll;
				padding: 5px;
				background: none repeat scroll 0 0 #F5F5F5;
				border-radius:3px;
				font-family: Consolas, Monaco, monospace;
			}
			.error-message {
				line-height: 26px;
				padding: 8px;
				background-color: #f2f2f2;
				border: 1px solid #ccc;
				padding: 10px;
				text-align:center;
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				margin-top:25px;
			}
			.error-hint{
				margin:0;
			}
			#debuginfo {
				display:none;
				text-align: center;
				margin: 0;
				padding: 0;
				padding-top: 10px;
				margin-top: 10px;
				border-top: 1px solid #d2d2d2;
			}
		</style>
		<script>
			function xi(){ document.getElementById('debuginfo').style.display = 'block'; }
		</script>
	</head>
	<body>
		<div id="error-page">
			<table width="100%" border="0">
				<tr>
					<td align="center"><img src="<?php echo $assets_base_url ?>alert.png" /></td>
				</tr>

				<tr>
					<td align="center"><h4><?php _wpl_e("Oops! We ran into an issue", 'wordpress-pixelpin-login') ?>.</h4></td>
				</tr>

				<tr>
					<td>
						<div class="error-message">
							<?php echo $message ; ?>
						</div>

						<?php
							// any hint or extra note?
							if( $notes )
							{
								?>
									<p class="error-hint"><?php _wpl_e( $notes, 'wordpress-pixelpin-login'); ?></p>
								<?php
							}
						?>
					</td>
				</tr>

				<tr>
					<td>
						<p style="padding: 0;">
							<a href="javascript:xi();" style="float:right"><?php _wpl_e("Details", 'wordpress-pixelpin-login') ?></a>
							<a href="<?php echo home_url(); ?>" style="float:left">&xlarr; <?php _wpl_e("Back to home", 'wordpress-pixelpin-login') ?></a>
						</p>

						<br style="clear:both;" />

						<p id="debuginfo">&xi; <?php echo $api_error ?></p>
					</td>
				</tr>
			</table>
		</div>

		<?php
			// Development mode on?
			if( get_option( 'wpl_settings_development_mode_enabled' ) )
			{
				wpl_render_error_page_debug_section( $php_exception );
			}
		?>
	</body>
</html>
<?php
	# keep these 2 LOC
		do_action( 'wpl_clear_user_php_session' );

		die();
	}
}

// --------------------------------------------------------------------

/**
* Display an extra debugging section to the error page, in case Mode Dev is on
*/
function wpl_render_error_page_debug_section( $php_exception = null )
{
?>
<hr />

<?php wpl_display_dev_mode_debugging_area(); ?>

<h3>Backtrace</h3>
<pre><?php echo wpl_generate_backtrace(); ?></pre>

<h3>Exception</h3>
<pre><?php print_r( $php_exception ); ?></pre>

<br />

<small>
	<?php _wpl_e("<strong>Note:</strong> This debugging area can be disabled from the plugin settings by setting <b>Development mode</b> to <b>Disabled</b>", 'wordpress-pixelpin-login'); ?>.
</small>
<?php
}

// --------------------------------------------------------------------
