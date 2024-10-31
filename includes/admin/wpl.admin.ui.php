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
* The LOC in charge of displaying WPL Admin GUInterfaces
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate wpl admin pages
*
* wp-admin/options-general.php?page=wordpress-pixelpin-login&..
*/
function wpl_admin_main()
{
	// HOOKABLE:
	do_action( "wpl_admin_main_start" );

	if ( ! current_user_can('manage_options') )
	{
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	if( ! wpl_check_requirements() )
	{
		wpl_admin_ui_fail();

		exit;
	}

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;

	if( isset( $_REQUEST["enable"] ) && isset( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $_REQUEST["enable"] ] ) )
	{
		$component = $_REQUEST["enable"];

		$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = true;

		update_option( "wpl_components_" . $component . "_enabled", 1 );

		wpl_register_components();
	}

	if( isset( $_REQUEST["disable"] ) && isset( $WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $_REQUEST["disable"] ] ) )
	{
		$component = $_REQUEST["disable"];

		$WORDPRESS_PIXELPIN_LOGIN_COMPONENTS[ $component ][ "enabled" ] = false;

		update_option( "wpl_components_" . $component . "_enabled", 2 );

		wpl_register_components();
	}

	$wplp            = "networks";
	$wpldwp          = 0;
	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	if( isset( $_REQUEST["wplp"] ) )
	{
		$wplp = trim( strtolower( strip_tags( $_REQUEST["wplp"] ) ) );
	}

	wpl_admin_ui_header( $wplp );

	if( isset( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wplp] ) && $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wplp]["enabled"] )
	{
		if( isset( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wplp]["action"] ) && $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wplp]["action"] )
		{
			do_action( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS[$wplp]["action"] );
		}
		else
		{
			include "components/$wplp/index.php";
		}
	}
	else
	{
		wpl_admin_ui_error();
	}

	wpl_admin_ui_footer();

	// HOOKABLE:
	do_action( "wpl_admin_main_end" );
}

// --------------------------------------------------------------------

/**
* Render wpl admin pages header (label and tabs)
*/
function wpl_admin_ui_header( $wplp = null )
{
	// HOOKABLE:
	do_action( "wpl_admin_ui_header_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS;

?>
<a name="wpltop"></a>
<div class="wpl-container">

	<?php
		// nag

		if( in_array( $wplp, array( 'networks', 'login-widget' ) ) and ( isset( $_REQUEST['settings-updated'] ) or isset( $_REQUEST['enable'] ) ) )
		{
			$active_plugins = implode('', (array) get_option('active_plugins') );
			$cache_enabled  =
				strpos( $active_plugins, "w3-total-cache"   ) !== false |
				strpos( $active_plugins, "wp-super-cache"   ) !== false |
				strpos( $active_plugins, "quick-cache"      ) !== false |
				strpos( $active_plugins, "wp-fastest-cache" ) !== false |
				strpos( $active_plugins, "wp-widget-cache"  ) !== false |
				strpos( $active_plugins, "hyper-cache"      ) !== false;

			if( $cache_enabled )
			{
				?>
					<div class="fade updated" style="margin: 4px 0 20px;">
						<p>
							<?php _wpl_e("<b>Note:</b> WPL has detected that you are using a caching plugin. If the saved changes didn't take effect immediately then you might need to empty the cache", 'wordpress-pixelpin-login') ?>.
						</p>
					</div>
				<?php
			}
		}

		if( get_option( 'wpl_settings_development_mode_enabled' ) )
		{
			?>
				<div class="fade error wpl-error-dev-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _wpl_e('<b>Warning:</b> You are now running WordPress PixelPin Login with DEVELOPMENT MODE enabled. This mode is not intend for live websites as it might raise serious security risks', 'wordpress-pixelpin-login') ?>.
					</p>
					<p>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wplp=tools#dev-mode"><?php _wpl_e('Change this mode', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/troubleshooting-advanced.html" target="_blank"><?php _wpl_e('Read about the development mode', 'wordpress-pixelpin-login') ?></a>
					</p>
				</div>
			<?php
		}

		if( get_option( 'wpl_settings_debug_mode_enabled' ) )
		{
			?>
				<div class="fade updated wpl-error-debug-mode-on" style="margin: 4px 0 20px;">
					<p>
						<?php _wpl_e('<b>Note:</b> You are now running WordPress PixelPin Login with DEBUG MODE enabled. This mode is not intend for live websites as it might add to loading time and store unnecessary data on your server', 'wordpress-pixelpin-login') ?>.
					</p>
					<p>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wplp=tools#debug-mode"><?php _wpl_e('Change this mode', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wplp=watchdog"><?php _wpl_e('View WPL logs', 'wordpress-pixelpin-login') ?></a>
						<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/troubleshooting-advanced.html" target="_blank"><?php _wpl_e('Read about the debug mode', 'wordpress-pixelpin-login') ?></a>
					</p>
				</div>
			<?php
		}
	?>

	<div class="alignright">
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="http://miled.github.io/wordpress-social-login/documentation.html"><?php _wpl_e('Docs', 'wordpress-pixelpin-login') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="http://miled.github.io/wordpress-social-login/support.html"><?php _wpl_e('Support', 'wordpress-pixelpin-login') ?></a> -
		<a style="font-size: 0.9em; text-decoration: none;" target="_blank" href="https://github.com/PixelPinPlugins/WordPress-PixelPin-Login"><?php _wpl_e('Github', 'wordpress-pixelpin-login') ?></a>
	</div>

	<h1 <?php if( is_rtl() ) echo 'style="margin: 20px 0;"'; ?>>
		<?php _wpl_e( 'WordPress PixelPin Login', 'wordpress-pixelpin-login' ) ?>

		<small><?php echo $WORDPRESS_PIXELPIN_LOGIN_VERSION ?></small>
	</h1>

	<h2 class="nav-tab-wrapper">
		&nbsp;
		<?php
			$css_pull_right = "";

			foreach( $WORDPRESS_PIXELPIN_LOGIN_ADMIN_TABS as $name => $settings )
			{
				if( $settings["enabled"] && ( $settings["visible"] || $wplp == $name ) )
				{
					if( isset( $settings["pull-right"] ) && $settings["pull-right"] )
					{
						$css_pull_right = "float:right";

						if( is_rtl() )
						{
							$css_pull_right = "float:left";
						}
					}

					?><a class="nav-tab <?php if( $wplp == $name ) echo "nav-tab-active"; ?>" style="<?php echo $css_pull_right; ?>" href="options-general.php?page=wordpress-pixelpin-login&wplp=<?php echo $name ?>"><?php if( isset( $settings["ico"] ) ) echo '<img style="margin: 0px; padding: 0px; border: 0px none;width: 16px; height: 16px;" src="' . WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . '/assets/img/' . $settings["ico"] . '" />'; else _wpl_e( $settings["label"], 'wordpress-pixelpin-login' ); ?></a><?php
				}
			}
		?>
	</h2>

	<div id="wpl_admin_tab_content">
<?php
	// HOOKABLE:
	do_action( "wpl_admin_ui_header_end" );
}

// --------------------------------------------------------------------

/**
* Renders wpl admin pages footer
*/
function wpl_admin_ui_footer()
{
	// HOOKABLE:
	do_action( "wpl_admin_ui_footer_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_VERSION;
?>
	</div> <!-- ./wpl_admin_tab_content -->

<div class="clear"></div>

<?php
	wpl_admin_help_us_localize_note();

	// HOOKABLE:
	do_action( "wpl_admin_ui_footer_end" );

	if( get_option( 'wpl_settings_development_mode_enabled' ) )
	{
		wpl_display_dev_mode_debugging_area();
 	}
}

// --------------------------------------------------------------------

/**
* Renders wpl admin error page
*/
function wpl_admin_ui_error()
{
	// HOOKABLE:
	do_action( "wpl_admin_ui_error_start" );
?>
<div id="wpl_div_warn">
	<h3 style="margin:0px;"><?php _wpl_e('Oops! We ran into an issue.', 'wordpress-pixelpin-login') ?></h3>

	<hr />

	<p>
		<?php _wpl_e('Unknown or Disabled <b>Component</b>! Check the list of enabled components or the typed URL', 'wordpress-pixelpin-login') ?> .
	</p>

	<p>
		<?php _wpl_e("If you believe you've found a problem with <b>WordPress PixelPin Login</b>, be sure to let us know so we can fix it", 'wordpress-pixelpin-login') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="http://miled.github.io/wordpress-social-login/support.html" target="_blank"><?php _wpl_e( "Report as bug", 'wordpress-pixelpin-login' ) ?></a>
		<a class="button-primary" href="options-general.php?page=wordpress-pixelpin-login&wplp=components" style="float:<?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>"><?php _wpl_e( "Check enabled components", 'wordpress-pixelpin-login' ) ?></a>
	</div>
</div>
<?php
	// HOOKABLE:
	do_action( "wpl_admin_ui_error_end" );
}

// --------------------------------------------------------------------

/**
* Renders WPL #FAIL page
*/
function wpl_admin_ui_fail()
{
	// HOOKABLE:
	do_action( "wpl_admin_ui_fail_start" );
?>
<div class="wpl-container">
		<div style="background: none repeat scroll 0 0 #fff;border: 1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);padding:20px;">
			<h1><?php _e("WordPress PixelPin Login - FAIL!", 'wordpress-pixelpin-login') ?></h1>

			<hr />

			<p>
				<?php _e('Despite the efforts, put into <b>WordPress PixelPin Login</b> in terms of reliability, portability, and maintenance by the plugin <a href="http://profiles.wordpress.org/miled/" target="_blank">author</a> and <a href="https://github.com/hybridauth/WordPress-Social-Login/graphs/contributors" target="_blank">contributors</a>', 'wordpress-pixelpin-login') ?>.
				<b style="color:red;"><?php _e('Your server failed the requirements check for this plugin', 'wordpress-pixelpin-login') ?>:</b>
			</p>

			<p>
				<?php _e('These requirements are usually met by default by most "modern" web hosting providers, however some complications may occur with <b>shared hosting</b> and, or <b>custom wordpress installations</b>', 'wordpress-pixelpin-login') ?>.
			</p>

			<p>
				<?php _wpl_e("The minimum server requirements are", 'wordpress-pixelpin-login') ?>:
			</p>

			<ul style="margin-left:60px;">
				<li><?php _wpl_e("PHP >= 5.2.0 installed", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("WPL Endpoint URLs reachable", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("PHP's default SESSION handling", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("PHP/CURL/SSL Extension enabled", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("PHP/JSON Extension enabled", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("PHP/REGISTER_GLOBALS Off", 'wordpress-pixelpin-login') ?></li>
				<li><?php _wpl_e("jQuery installed on WordPress backoffice", 'wordpress-pixelpin-login') ?></li>
			</ul>
		</div>

<?php
	include_once( WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'includes/admin/components/tools/wpl.components.tools.actions.job.php' );

	wpl_component_tools_do_diagnostics();
?>
</div>
<style>.wpl-container .button-secondary { display:none; }</style>
<?php
	// HOOKABLE:
	do_action( "wpl_admin_ui_fail_end" );
}

// --------------------------------------------------------------------

/**
* Renders wpl admin welcome panel
*/
function wpl_admin_welcome_panel()
{
	if( isset( $_REQUEST["wpldwp"] ) && (int) $_REQUEST["wpldwp"] )
	{
		$wpldwp = (int) $_REQUEST["wpldwp"];

		update_option( "wpl_settings_welcome_panel_enabled", wpl_get_version() );

		return;
	}

	// if new user or wpl updated, then we display wpl welcome panel
	if( get_option( 'wpl_settings_welcome_panel_enabled' ) == wpl_get_version() )
	{
		return;
	}

	$wplp = "networks";

	if( isset( $_REQUEST["wplp"] ) )
	{
		$wplp = $_REQUEST["wplp"];
	}
?>
<!--
	if you want to know if a UI was made by developer, then here is a tip: he will always use tables

	//> wpl-w-panel is shamelessly borrowed and modified from wordpress welcome-panel
-->
<div id="wpl-w-panel">
	<a href="options-general.php?page=wordpress-pixelpin-login&wplp=<?php echo $wplp ?>&wpldwp=1" id="wpl-w-panel-dismiss" <?php if( is_rtl() ) echo 'style="left: 10px;right: auto;"'; ?>><?php _wpl_e("Dismiss", 'wordpress-pixelpin-login') ?></a>

	<table width="100%" border="0" style="margin:0;padding:0;">
		<tr>
			<td width="10" valign="top"></td>
			<td width="300" valign="top">
				<b style="font-size: 16px;"><?php _wpl_e("Welcome!", 'wordpress-pixelpin-login') ?></b>
				<p>
					<?php _wpl_e("If you are still new to WordPress PixelPin Login, we have provided a few walkthroughs to get you started", 'wordpress-pixelpin-login') ?>.
				</p>
			</td>
			<td width="40" valign="top"></td>
			<td width="300" valign="top">
				<br />
				<p>
					<b><?php _wpl_e("WordPress PixelPin Login - Get Started", 'wordpress-pixelpin-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><a href="http://developer.pixelpin.io/wordpresspp.php" target="_blank"><?php _wpl_e('WordPress PixelPin Login installation guide', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://developer.pixelpin.io/developeraccount.php" target="_blank"><?php _wpl_e('How to Create A PixelPin Developer Account', 'wordpress-pixelpin-login') ?></a></li>
				</ul>
			</td>
			<td width="260" valign="top">
				<br />
				<p>
					<b><?php _wpl_e("WordPress Social Login - Get Started", 'wordpress-pixelpin-login') ?></b>
				</p>
				<ul style="margin-left:25px;">
					<li><a href="http://miled.github.io/wordpress-social-login/overview.html" target="_blank"><?php _wpl_e('Plugin Overview', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-social-login/networks.html" target="_blank"><?php _wpl_e('Setup and Configuration', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-social-login/widget.html" target="_blank"><?php _wpl_e('Customize WPL Widgets', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-social-login/userdata.html" target="_blank"><?php _wpl_e('Manage users and contacts', 'wordpress-pixelpin-login') ?></a></li>
					<li><a href="http://miled.github.io/wordpress-social-login/documentation.html" target="_blank"><?php _wpl_e('WPL Developer API', 'wordpress-pixelpin-login') ?></a></li>
				</ul>
			</td>
		</tr>
		<tr id="wpl-w-panel-updates-tr">
			<td colspan="5" style="border-top:1px solid #ccc;" id="wpl-w-panel-updates-td">
				&nbsp;
			</td>
		</tr>
	</table>
</div>
<?php
}

// --------------------------------------------------------------------

/**
* Renders wpl localization note
*/
function wpl_admin_help_us_localize_note()
{
	return; // nothing, until I decide otherwise..

	$assets_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';

	?>
		<div id="l10n-footer">
			<br /><br />
			<img src="<?php echo $assets_url ?>flags.png">
			<a href="https://www.transifex.com/projects/p/wordpress-social-login/" target="_blank"><?php _wpl_e( "Help us translate WordPress PixelPin Login into your language", 'wordpress-pixelpin-login' ) ?></a>
		</div>
	<?php
}

// --------------------------------------------------------------------

/**
* Renders an editor in a page in the typical fashion used in Posts and Pages.
* wp_editor was implemented in wp 3.3. if not found we fallback to a regular textarea
*
* Utility.
*/
function wpl_render_wp_editor( $name, $content )
{
	if( ! function_exists( 'wp_editor' ) )
	{
		?>
			<textarea style="width:100%;height:100px;margin-top:6px;" name="<?php echo $name ?>"><?php echo htmlentities( $content ); ?></textarea>
		<?php
		return;
	}
?>
<div class="postbox">
	<div class="wp-editor-textarea" style="background-color: #FFFFFF;">
		<?php
			wp_editor(
				$content, $name,
				array( 'textarea_name' => $name, 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink' ) )
			);
		?>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

/**
* Display WordPress PixelPin Login on settings as submenu
*/
function wpl_admin_menu()
{
	add_options_page('WP PixelPin Login', 'WP PixelPin Login', 'manage_options', 'wordpress-pixelpin-login', 'wpl_admin_main' );

	add_action( 'admin_init', 'wpl_register_setting' );
}

add_action('admin_menu', 'wpl_admin_menu' );

// --------------------------------------------------------------------

/**
* Enqueue WPL admin CSS file
*/
function wpl_add_admin_stylesheets()
{
	if( ! wp_style_is( 'wpl-admin', 'registered' ) )
	{
		wp_register_style( "wpl-admin", WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . "assets/css/admin.css" );
	}

	wp_enqueue_style( "wpl-admin" );
}

add_action( 'admin_enqueue_scripts', 'wpl_add_admin_stylesheets' );

// --------------------------------------------------------------------
