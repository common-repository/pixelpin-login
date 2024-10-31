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
* Authentication widgets generator
*
* http://miled.github.io/wordpress-social-login/widget.html
* http://miled.github.io/wordpress-social-login/themes.html
* http://miled.github.io/wordpress-social-login/developer-api-widget.html
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* Generate the HTML content of WPL Widget
*
* Note:
*   WPL shortcode arguments are still experimental and might change in future versions.
*
*   [wordpress_pixelpin_login
*        auth_mode="login"
*        caption="Connect with PixelPin"
*        enable_providers="pixelpin"
*        restrict_content="wpl_user_logged_in"
*        assets_base_url="http://example.com/wp-content/uploads/2022/01/"
*   ]
*
*   Overall, WPL widget work with these simple rules :
*      1. Shortcode arguments rule over the defaults
*      2. Filters hooks rule over shortcode arguments
*      3. Bouncer rules over everything
*/
function wpl_render_auth_widget( $args = array() )
{
	$auth_mode = isset( $args['mode'] ) && $args['mode'] ? $args['mode'] : 'login';

	// validate auth-mode
	if( ! in_array( $auth_mode, array( 'login', 'link', 'test' ) ) )
	{
		return;
	}

	// auth-mode eq 'login' => display wpl widget only for NON logged in users
	// > this is the default mode of wpl widget.
	if( $auth_mode == 'login' && is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'link' => display wpl widget only for LOGGED IN users
	// > this will allows users to manually link other pixelpin network accounts to their WordPress account
	if( $auth_mode == 'link' && ! is_user_logged_in() )
	{
		return;
	}

	// auth-mode eq 'test' => display wpl widget only for LOGGED IN users only on dashboard
	// > used in Authentication Playground on WPL admin dashboard
	if( $auth_mode == 'test' && ! is_user_logged_in() && ! is_admin() )
	{
		return;
	}

	// Bouncer :: Allow authentication?
	if( get_option( 'wpl_settings_bouncer_authentication_enabled' ) == 2 )
	{
		return;
	}

	// HOOKABLE: This action runs just before generating the WPL Widget.
	do_action( 'wpl_render_auth_widget_start' );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	ob_start();

	// Icon set. If eq 'none', we show text instead
	$pixelpin_icon_set = get_option( 'wpl_settings_pixelpin_icon_set' );

	// wpzoom icons set, is shown by default
	if( empty( $pixelpin_icon_set ) )
	{
		$pixelpin_icon_set = "ssoicon/";
	}

	$assets_base_url  = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/32x32/' . $pixelpin_icon_set . '/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	// HOOKABLE:
	$assets_base_url = apply_filters( 'wpl_render_auth_widget_alter_assets_base_url', $assets_base_url );

	// get the current page url, which we will use to redirect the user to,
	// unless Widget::Force redirection is set to 'yes', then this will be ignored and Widget::Redirect URL will be used instead
	$redirect_to = wpl_get_current_url();

	// Use the provided redirect_to if it is given and this is the login page.
	if ( in_array( $GLOBALS["pagenow"], array( "wp-login.php", "wp-register.php" ) ) && !empty( $_REQUEST["redirect_to"] ) )
	{
		$redirect_to = $_REQUEST["redirect_to"];
	}

	// build the authentication url which will call for wpl_process_login() : action=wordpress_pixelpin_authenticate
	$authenticate_base_url = site_url( 'wp-login.php', 'login_post' ) 
                                        . ( strpos( site_url( 'wp-login.php', 'login_post' ), '?' ) ? '&' : '?' ) 
                                                . "action=wordpress_pixelpin_authenticate&mode=login&";

	// if not in mode login, we overwrite the auth base url
	// > admin auth playground
	if( $auth_mode == 'test' )
	{
		$authenticate_base_url = home_url() . "/?action=wordpress_pixelpin_authenticate&mode=test&";
	}

	// > account linking
	elseif( $auth_mode == 'link' )
	{
		$authenticate_base_url = home_url() . "/?action=wordpress_pixelpin_authenticate&mode=link&";
	}

	// Connect with caption
	$connect_with_label = _wpl__( get_option( 'wpl_settings_connect_with_label' ), 'wordpress-pixelpin-login' );

	$connect_with_label = isset( $args['caption'] ) ? $args['caption'] : $connect_with_label;

	// HOOKABLE:
	$connect_with_label = apply_filters( 'wpl_render_auth_widget_alter_connect_with_label', $connect_with_label );
?>

<!--
	wpl_render_auth_widget
	WordPress PixelPin Login <?php echo wpl_get_version(); ?>.
	http://wordpress.org/plugins/wordpress-social-login/
-->
<?php
	// Widget::Custom CSS
	$widget_css = get_option( 'wpl_settings_authentication_widget_css' );

	// HOOKABLE:
	$widget_css = apply_filters( 'wpl_render_auth_widget_alter_widget_css', $widget_css, $redirect_to );

	// show the custom widget css if not empty
	if( ! empty( $widget_css ) )
	{
?>

<style type="text/css">
<?php
	echo
		preg_replace(
			array( '%/\*(?:(?!\*/).)*\*/%s', '/\s{2,}/', "/\s*([;{}])[\r\n\t\s]/", '/\\s*;\\s*/', '/\\s*{\\s*/', '/;?\\s*}\\s*/' ),
				array( '', ' ', '$1', ';', '{', '}' ),
					$widget_css );
?>
</style>
<?php
	}
?>

<div class="wp-pixelpin-login-widget">

	<div class="wp-pixelpin-login-connect-with">Connect with PixelPin:</div>

	<div class="wp-pixelpin-login-provider-list">
<?php
	// Widget::Authentication display
	$wpl_settings_use_popup = get_option( 'wpl_settings_use_popup' );

	// if a user is visiting using a mobile device, WPL will fall back to more in page
	$wpl_settings_use_popup = function_exists( 'wp_is_mobile' ) ? wp_is_mobile() ? 2 : $wpl_settings_use_popup : $wpl_settings_use_popup;

	$no_idp_used = true;

	// display provider icons
	foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
	{
		$provider_id    = isset( $item["provider_id"]    ) ? $item["provider_id"]   : '' ;
		$provider_name  = isset( $item["provider_name"]  ) ? $item["provider_name"] : '' ;

		// provider enabled?
		if( get_option( 'wpl_settings_' . $provider_id . '_enabled' ) )
		{
			// restrict the enabled providers list
			if( isset( $args['enable_providers'] ) )
			{
				$enable_providers = explode( '|', $args['enable_providers'] ); // might add a couple of pico seconds

				if( ! in_array( strtolower( $provider_id ), $enable_providers ) )
				{
					continue;
				}
			}

			// build authentication url
			$authenticate_url = $authenticate_base_url . "provider=" . $provider_id . "&redirect_to=" . urlencode( $redirect_to );

			// http://codex.wordpress.org/Function_Reference/esc_url
			$authenticate_url = esc_url( $authenticate_url );

			// in case, Widget::Authentication display is set to 'popup', then we overwrite 'authenticate_url'
			// > /assets/js/connect.js will take care of the rest
			if( $wpl_settings_use_popup == 1 &&  $auth_mode != 'test' )
			{
				$authenticate_url= "javascript:void(0);";
			}

			// HOOKABLE: allow user to rebuilt the auth url
			$authenticate_url = apply_filters( 'wpl_render_auth_widget_alter_authenticate_url', $authenticate_url, $provider_id, $auth_mode, $redirect_to, $wpl_settings_use_popup );

			// HOOKABLE: allow use of other icon sets
			$provider_icon_markup = apply_filters( 'wpl_render_auth_widget_alter_provider_icon_markup', $provider_id, $provider_name, $authenticate_url );

			$ppsso_custom = get_option('wpl_settings_pixelpin_ppsso_custom');
			$ppsso_colour = get_option('wpl_settings_pixelpin_ppsso_colour');
			$ppsso_size = get_option('wpl_settings_pixelpin_ppsso_size');
			$ppsso_show_text = get_option('wpl_settings_pixelpin_ppsso_show_text');
			$ppsso_text = get_option('wpl_settings_pixelpin_ppsso_text');

			if( $provider_icon_markup != $provider_id )
			{
				echo $provider_icon_markup;
			}
			else
			{
?>

			<?php if(get_option('wpl_settings_pixelpin_ppsso_custom')){ ?> 
				<a href="<?php echo $authenticate_url; ?>" 
					title="<?php echo sprintf( _wpl__("Connect with PixelPin %s", 'wordpress-pixelpin-login'), $provider_name ) ?>" 
					class="ppsso-btn <?php echo $ppsso_size ?> <?php echo $ppsso_colour ?>" 
					data-provider="<?php echo $provider_id ?>">
					<?php if(get_option('wpl_settings_pixelpin_ppsso_show_text')){ echo $ppsso_text; ?> 
						<span class="ppsso-logotype">PixelPin</span> 
					<?php } ?>
				</a>
			<?php } else { ?>
				<a href="<?php echo $authenticate_url; ?>" 
					title="<?php echo sprintf( _wpl__("Connect with PixelPin %s", 'wordpress-pixelpin-login'), $provider_name ) ?>" 
					class="ppsso-btn" 
					data-provider="<?php echo $provider_id ?>">
					Log In With <span class="ppsso-logotype">PixelPin</span> 
				</a>
			<?php } ?>
<?php
			}

			$no_idp_used = false;
		}
	}

	// no provider enabled?
	if( $no_idp_used )
	{
?>
		<p style="background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;">
			<?php _wpl_e( '<strong>WordPress PixelPin Login is not configured yet</strong>.<br />Please navigate to <strong>Settings &gt; WP PixelPin Login</strong> to configure this plugin.<br />For more information, refer to the <a rel="nofollow" href="http://miled.github.io/wordpress-pixelpin-login">online user guide</a>.', 'wordpress-pixelpin-login') ?>.
		</p>
		<style>#wp-pixelpin-login-connect-with{display:none;}</style>
<?php
	}
?>

	</div>

	<div class="wp-pixelpin-login-widget-clearing"></div>

</div>

<?php
	// provide popup url for hybridauth callback
	if( $wpl_settings_use_popup == 1 )
	{
?>
<input type="hidden" id="wpl_popup_base_url" value="<?php echo esc_url( $authenticate_base_url ) ?>" />
<input type="hidden" id="wpl_login_form_uri" value="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" />

<?php
	}

	// HOOKABLE: This action runs just after generating the WPL Widget.
	do_action( 'wpl_render_auth_widget_end' );
?>
<!-- wpl_render_auth_widget -->

<?php
	// Display WPL debugging area bellow the widget.
	// wpl_display_dev_mode_debugging_area(); // ! keep this line commented unless you know what you are doing :)

	return ob_get_clean();
}

// --------------------------------------------------------------------

/**
* WPL wordpress_pixelpin_login action
*
* Ref: http://codex.wordpress.org/Function_Reference/add_action
*/
function wpl_action_wordpress_pixelpin_login( $args = array() )
{
	echo wpl_render_auth_widget( $args );
}

add_action( 'wordpress_pixelpin_login', 'wpl_action_wordpress_pixelpin_login' );

// --------------------------------------------------------------------

/**
* WPL wordpress_pixelpin_login shortcode
*
* Note:
*   WPL shortcode arguments are still experimental and might change in future versions.
*
* Ref: http://codex.wordpress.org/Function_Reference/add_shortcode
*/
function wpl_shortcode_wordpress_pixelpin_login( $args = array(), $content = null )
{
	$restrict_content = isset( $args['restrict_content'] ) && $args['restrict_content'] ? true : false;

	if( 'wp_user_logged_in' == $restrict_content && is_user_logged_in() )
	{
		return do_shortcode( $content );
	}

	if( 'wpl_user_logged_in' == $restrict_content && wpl_get_stored_hybridauth_user_profiles_by_user_id( get_current_user_id() ) )
	{
		return do_shortcode( $content );
	}

	return wpl_render_auth_widget( $args );
}

add_shortcode( 'wordpress_pixelpin_login', 'wpl_shortcode_wordpress_pixelpin_login' );

// --------------------------------------------------------------------

/**
* WPL wordpress_pixelpin_login_meta shortcode
*
* Note:
*   This shortcode is experimental and might change in future versions.
*
*   [wordpress_pixelpin_login_meta
*        user_id="215"
*        meta="wpl_current_user_image"
*        display="html"
*        css_class="my_style_is_better"
*   ]
*/
function wpl_shortcode_wordpress_pixelpin_login_meta( $args = array() )
{
	// wordpress user id default to current user connected
	$user_id = isset( $args['user_id'] ) && $args['user_id'] ? $args['user_id'] : get_current_user_id();

	// display default to plain text
	$display = isset( $args['display'] ) && $args['display'] ? $args['display'] : 'plain';

	// when display is set to html, css_class will be used for the main dom el
	$css_class = isset( $args['css_class'] ) && $args['css_class'] ? $args['css_class'] : '';

	// wpl user meta to display
	$meta = isset( $args['meta'] ) && $args['meta'] ? $args['meta'] : null;

	if( ! is_numeric( $user_id ) )
	{
		return;
	}

	if( ! $meta )
	{
		return;
	}

	$assets_base_url  = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	$assets_base_url  = isset( $args['assets_base_url'] ) && $args['assets_base_url'] ? $args['assets_base_url'] : $assets_base_url;

	$return = '';

	if( 'current_avatar' == $meta )
	{
		if( 'plain' == $display )
		{
			$return = wpl_get_user_custom_avatar( $user_id );
		}
		else
		{
			$return = '<img class="wordpress_pixelpin_login_meta_user_avatar ' . $css_class . '" src="' . wpl_get_user_custom_avatar( $user_id ) . '" />';
		}
	}

	if( 'current_provider' == $meta )
	{
		$provider = get_user_meta( $user_id, 'wpl_current_provider', true );

		if( 'plain' == $display )
		{
			$return = $provider;
		}
		else
		{
			$return = '<img class="wordpress_pixelpin_login_meta_user_provider ' . $css_class . '" src="' . $assets_base_url . strtolower( $provider ) . '.png"> ' . $provider;
		}
	}

	if( 'user_identities' == $meta )
	{
		ob_start();

		$linked_accounts = wpl_get_stored_hybridauth_user_profiles_by_user_id( $user_id );

		if( $linked_accounts )
		{
			?><table class="wp-pixelpin-login-linked-accounts-list <?php echo $css_class; ?>"><?php

			foreach( $linked_accounts AS $item )
			{
				$identity = $item->profileurl;
				$photourl = $item->photourl;

				if( ! $identity )
				{
					$identity = $item->identifier;
				}

				?><tr><td><?php if( $photourl ) { ?><img  style="vertical-align: top;width:16px;height:16px;" src="<?php echo $photourl ?>"> <?php } else { ?><img src="<?php echo $assets_base_url . strtolower(  $item->provider ) . '.png' ?>" /> <?php } ?><?php echo ucfirst( $item->provider ); ?> </td><td><?php echo $identity; ?></td></tr><?php

				echo "\n";
			}

			?></table><?php
		}

		$return = ob_get_clean();

		if( 'plain' == $display )
		{
			$return = strip_tags( $return );
		}
	}

	return $return;
}

add_shortcode( 'wordpress_pixelpin_login_meta', 'wpl_shortcode_wordpress_pixelpin_login_meta' );

// --------------------------------------------------------------------

/**
* Display on comment area
*/
function wpl_render_auth_widget_in_comment_form()
{
	$wpl_settings_widget_display = get_option( 'wpl_settings_widget_display' );

	if( comments_open() )
	{
		if(
			!  $wpl_settings_widget_display
		||
			$wpl_settings_widget_display == 1
		||
			$wpl_settings_widget_display == 2
		)
		{
			echo wpl_render_auth_widget();
		}
	}
}

add_action( 'comment_form_top'              , 'wpl_render_auth_widget_in_comment_form' );
add_action( 'comment_form_must_log_in_after', 'wpl_render_auth_widget_in_comment_form' );

// --------------------------------------------------------------------

/**
* Display on login form
*/
function wpl_render_auth_widget_in_wp_login_form()
{
	$wpl_settings_widget_display = get_option( 'wpl_settings_widget_display' );

	if( $wpl_settings_widget_display == 1 || $wpl_settings_widget_display == 3 )
	{
		echo wpl_render_auth_widget();
	}
}

add_action( 'login_form'                      , 'wpl_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_account_details_fields', 'wpl_render_auth_widget_in_wp_login_form' );
add_action( 'bp_before_sidebar_login_form'    , 'wpl_render_auth_widget_in_wp_login_form' );

// --------------------------------------------------------------------

/**
* Display on login & register form
*/
function wpl_render_auth_widget_in_wp_register_form()
{
	$wpl_settings_widget_display = get_option( 'wpl_settings_widget_display' );

	if( $wpl_settings_widget_display == 1 || $wpl_settings_widget_display == 3 )
	{
		echo wpl_render_auth_widget();
	}
}

add_action( 'register_form'    , 'wpl_render_auth_widget_in_wp_register_form' );
add_action( 'after_signup_form', 'wpl_render_auth_widget_in_wp_register_form' );

// --------------------------------------------------------------------

/**
* Enqueue WPL CSS file
*/
function wpl_add_stylesheets()
{
	wp_register_style( "wpl-widget", "https://developer-assets.pixelpin.io/sso-buttons/sso-button.css"  );
	
	if( ! wp_style_is( 'wpl-widget', 'registered' ) )
	{
		wp_register_style( "wpl-widget", WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . "assets/css/style.css" );
	}

	wp_enqueue_style( "wpl-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'wpl_add_stylesheets' );
add_action( 'login_enqueue_scripts', 'wpl_add_stylesheets' );

// --------------------------------------------------------------------

/**
* Enqueue WPL Javascript, only if we use popup
*/
function wpl_add_javascripts()
{
	if( get_option( 'wpl_settings_use_popup' ) != 1 )
	{
		return null;
	}

	if( ! wp_script_is( 'wpl-widget', 'registered' ) )
	{
		wp_register_script( "wpl-widget", WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . "assets/js/widget.js" );
	}

	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "wpl-widget" );
}

add_action( 'wp_enqueue_scripts'   , 'wpl_add_javascripts' );
add_action( 'login_enqueue_scripts', 'wpl_add_javascripts' );

// --------------------------------------------------------------------