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
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_loginwidget_setup()
{
	// HOOKABLE: 
	do_action( "wpl_component_loginwidget_setup_start" );

	$sections = array(
		'basic_settings'    => 'wpl_component_loginwidget_setup_basic_settings',
		'advanced_settings' => 'wpl_component_loginwidget_setup_advanced_settings',
		'custom_css'        => 'wpl_component_loginwidget_setup_custom_css', 
	);

	$sections = apply_filters( 'wpl_component_loginwidget_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_loginwidget_setup_sections', $action );
	} 
?>
<div>
	<?php
		// HOOKABLE: 
		do_action( 'wpl_component_loginwidget_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" /> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_loginwidget_setup_basic_settings()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Basic Settings", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p> 
			<?php _wpl_e("<b>Connect with PixelPin caption :</b> Change the content of the label to display above WPL widget", 'wordpress-pixelpin-login') ?>. 
		</p>

		<p> 
			<?php _wpl_e("<b>PixelPin icon set :</b> WPL provides two set of icons to display on the widget", 'wordpress-pixelpin-login') ?>.
			<?php _wpl_e("You can also display the providers names instead of icons. This allow the customization of the widget to a great extent", 'wordpress-pixelpin-login') ?>.
		</p>

		<p> 
			<?php _wpl_e("<b>Users avatars :</b> Determines whether to show users avatars from pixelpin networks or to display the default ones", 'wordpress-pixelpin-login') ?>.

			<?php _wpl_e("Avatars display should work right out of the box with most WordPress themes, BuddyPress and bbPress", 'wordpress-pixelpin-login') ?>.
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="180" align="right"><strong><?php _wpl_e("Connect with PixelPin caption", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td> 
			<input type="text" class="inputgnrc" style="width:535px" value="<?php _wpl_e( get_option( 'wpl_settings_connect_with_label' ), 'wordpress-pixelpin-login' ); ?>" name="wpl_settings_connect_with_label" > 
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("PixelPin SSO options", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td> 	
				<?php
					$icon_sets = array(
						'ssoicon' => "Use the PixelPin Icon as the SSO button",
					);

					$icon_sets = apply_filters( 'wpl_component_loginwidget_setup_alter_icon_sets', $icon_sets );
					
					$wpl_settings_pixelpin_icon_set = get_option( 'wpl_settings_pixelpin_icon_set' );
				?>
				<select name="wpl_settings_pixelpin_icon_set" style="width:535px">
					<?php
						foreach( $icon_sets as $folder => $label )
						{
							?>
								<option <?php if( $wpl_settings_pixelpin_icon_set == $folder ) echo "selected"; ?>   value="<?php echo $folder; ?>"><?php _wpl_e( $label, 'wordpress-pixelpin-login' ) ?></option>
							<?php
						}
					?>
					<option <?php if( $wpl_settings_pixelpin_icon_set == "none" ) echo "selected"; ?>     value="none"><?php _wpl_e("Display PixelPin as a word instead of an icon", 'wordpress-pixelpin-login') ?></option> 
				</select> 
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("Users avatars", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<select name="wpl_settings_users_avatars" style="width:535px">
					<option <?php if( ! get_option( 'wpl_settings_users_avatars' ) ) echo "selected"; ?> value="0"><?php _wpl_e("Display the default WordPress avatars", 'wordpress-pixelpin-login') ?></option> 
					<option <?php if(   get_option( 'wpl_settings_users_avatars' ) ) echo "selected"; ?> value="1"><?php _wpl_e("Display users avatars from pixelpin networks when available", 'wordpress-pixelpin-login') ?></option>
				</select> 
			</td>
		  </tr> 
		</table> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_loginwidget_setup_advanced_settings()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Advanced Settings", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p> 
			<?php _wpl_e("<b>Redirect URL :</b> By default and after they authenticate, users will be automatically redirected to the page where they come from. If WPL wasn't able to identify where they come from (or if they used wp-login page to connect), then they will be redirected to <code>Redirect URL</code> instead", 'wordpress-pixelpin-login') ?>.
		</p>

		<p> 
			<?php _wpl_e("<b>Force redirection :</b> When set to <b>Yes</b>, users will be <b>always</b> redirected to <code>Redirect URL</code>", 'wordpress-pixelpin-login') ?>.
		</p>

		<p> 
			<?php _wpl_e("<b>Authentication display :</b> Determines how the authentication dialog is rendered. You can chose to open the dialog in a <b>popup</b> or to <b>in page</b>. If a user is visiting using a mobile device, WPL will fall back to more <b>in page</b>", 'wordpress-pixelpin-login') ?>.
		</p>

		<p> 
			<?php _wpl_e("<b>Widget display :</b> Determines where you want to show the authentication widget", 'wordpress-pixelpin-login') ?>. 
		</p>

		<p> 
			<?php _wpl_e("<b>Notification :</b> Determines whether you want to receive a notification by mail when a new user is logged in via WPL", 'wordpress-pixelpin-login') ?>. 
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="180" align="right"><strong><?php _wpl_e("Redirect URL", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<input type="text" name="wpl_settings_redirect_url" class="inputgnrc" style="width:535px" value="<?php echo get_option( 'wpl_settings_redirect_url' ); ?>"> 
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("Force redirection", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<select name="wpl_settings_force_redirect_url" style="width:100px">
					<option <?php if( get_option( 'wpl_settings_force_redirect_url' ) == 1 ) echo "selected"; ?> value="1"><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option> 
					<option <?php if( get_option( 'wpl_settings_force_redirect_url' ) == 2 ) echo "selected"; ?> value="2"><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option> 
				</select> 
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("Authentication display", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<select name="wpl_settings_use_popup" style="width:100px">
					<option <?php if( get_option( 'wpl_settings_use_popup' ) == 1 ) echo "selected"; ?> value="1"><?php _wpl_e("Popup", 'wordpress-pixelpin-login') ?></option> 
					<option <?php if( get_option( 'wpl_settings_use_popup' ) == 2 ) echo "selected"; ?> value="2"><?php _wpl_e("In Page", 'wordpress-pixelpin-login') ?></option> 
				</select> 
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("Widget display", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<?php
					$widget_display = array(
						4 => "Do not display the widget anywhere, I'll use shortcodes",
						1 => "Display the widget in the comments area, login and register forms",
						3 => "Display the widget only in the login and register forms",
						2 => "Display the widget only in the comments area",
					);

					$widget_display = apply_filters( 'wpl_component_loginwidget_setup_alter_widget_display', $widget_display );
					
					$wpl_settings_widget_display = get_option( 'wpl_settings_widget_display' );
				?>
				<select name="wpl_settings_widget_display" style="width:535px">
					<?php
						foreach( $widget_display as $display => $label )
						{
							?>
								<option <?php if( $wpl_settings_widget_display == $display ) echo "selected"; ?>   value="<?php echo $display; ?>"><?php _wpl_e( $label, 'wordpress-pixelpin-login' ) ?></option>
							<?php
						}
					?>
				</select>  
			</td>
		  </tr>
		  <tr>
			<td align="right"><strong><?php _wpl_e("Notification", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<?php
					$users_notification = array(
						1 => "Notify ONLY the blog admin of a new user",
					);

					$users_notification = apply_filters( 'wpl_component_loginwidget_setup_alter_users_notification', $users_notification );
					
					$wpl_settings_users_notification = get_option( 'wpl_settings_users_notification' );
				?>
				<select name="wpl_settings_users_notification" style="width:535px">
					<option <?php if( $wpl_settings_users_notification == 0 ) echo "selected"; ?> value="0"><?php _wpl_e("No notification", 'wordpress-pixelpin-login') ?></option>
					<?php
						foreach( $users_notification as $type => $label )
						{
							?>
								<option <?php if( $wpl_settings_users_notification == $type ) echo "selected"; ?>   value="<?php echo $type; ?>"><?php _wpl_e( $label, 'wordpress-pixelpin-login' ) ?></option>
							<?php
						}
					?>
				</select> 
			</td>
		  </tr> 
		</table>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_loginwidget_setup_custom_css()
{
?>
<style>
	.com { color: #6c7c7c; }
	.lit { color: #195f91; }
	.pun, .opn, .clo { color: #93a1a1; }
	.fun { color: #dc322f; }
	.str, .atv { color: #D14; }
	.kwd, .prettyprint .tag { color: #1e347b; }
	.typ, .atn, .dec, .var { color: teal; }
	.pln { color: #48484c; }
	.prettyprint {
	  padding: 8px;
	  background-color: #f7f7f9;
	  border: 1px solid #e1e1e8;
	}
	.prettyprint.linenums {
	  -webkit-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
		 -moz-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
			  box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0;
	}
	ol.linenums {
	  margin: 0 0 0 33px; /* IE indents via margin-left */
	}
	ol.linenums li {
	  padding-left: 12px;
	  color: #bebec5;
	  text-shadow: 0 1px 0 #fff;
	  margin-bottom: 0;
	}
	.prettyprint code {
		background-color: #ffd88f;
		border-radius: 4px;
		color: #c7254e;
		font-size: 90%;
		padding: 2px 4px;
		text-shadow: 0 1px 0 #ffcf75;
	}
</style>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Custom CSS", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e("To customize the default widget styles you can either: change the css in the <b>text area</b> bellow or add it to your website <b>theme</b> files", 'wordpress-pixelpin-login') ?>. 
		</p>

		<textarea style="width:100%;height:120px;margin-top:6px;" name="wpl_settings_authentication_widget_css" dir="ltr"><?php echo get_option( 'wpl_settings_authentication_widget_css' );  ?></textarea>

		<br />

		<p>
			<?php _wpl_e("The basic widget markup is the following", 'wordpress-pixelpin-login') ?>:
		</p>

		<pre class="prettyprint linenums" dir="ltr">
		<?php ob_start() ?>

<div class="wp-pixelpin-login-widget">
	<div class="wp-pixelpin-login-connect-with">Connect with PixelPin:</div>
		<div class="wp-pixelpin-login-provider-list">
			<a class="wp-pixelpin-login-provider wp-pixelpin-login-provider-pixelpin">
		        <img src="{provider_icon_pixelpin}" />
		    </a>
		</div>
	<div class="wp-pixelpin-login-widget-clearing"></div>
</div>

		<?php $code = ob_get_clean();
		echo htmlspecialchars($code);
		?> 
		</pre>

	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
