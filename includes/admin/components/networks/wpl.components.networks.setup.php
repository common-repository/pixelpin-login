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
* PixelPin networks configuration and setup
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

/**
* 
*/
function wpl_component_networks_setup()
{
	// HOOKABLE:
	do_action( "wpl_component_networks_setup_start" );

	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';
	$assets_setup_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/setup/';

	// save settings?
	if( isset( $_REQUEST["enable"] ) && $_REQUEST["enable"] )
	{
		$provider_id = $_REQUEST["enable"];

		update_option( 'wpl_settings_' . $provider_id . '_enabled', 1 );
	}
?>
<script>
	jQuery( window ).load(function() {
		toggleppssopreview();
	});

	function toggleproviderkeys(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#wpl_settings_' + idp + '_enabled').val()==1)
		{
			jQuery('.wpl_tr_settings_' + idp).show();
			jQuery('.wpl_tr_ppsso_settings_' + idp).show();
			JQuery('#ppenable').hide();
		}
		else
		{
			jQuery('.wpl_tr_settings_' + idp).hide();
			jQuery('.wpl_tr_ppsso_settings_' + idp).hide();
			jQuery('.wpl_div_settings_help_' + idp).hide();
		}

		return false;
	}

	function toggleppssosettings(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return;
		}

		if(jQuery('#wpl_settings_' + idp + '_ppsso_custom').val()==1)
		{
			jQuery('.wpl_tr_ppsso_settings_' + idp).show();
		}
		else
		{
			jQuery('.wpl_tr_ppsso_settings_' + idp).hide();
		}

		return false;
	}

	function toggleproviderhelp(idp)
	{
		if(typeof jQuery=="undefined")
		{
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		jQuery('.wpl_div_settings_help_' + idp).toggle();

		return false;
	}

	function toggleppssopreview(){
		var previewSSOButton = document.getElementById("previewSSObutton");
		previewSSOButton.className = '';

		var customiseDropdown = document.getElementById("wpl_settings_PixelPin_ppsso_custom");
		var customiseValue = customiseDropdown.value;

		var buttonColourDropdown = document.getElementById("wpl_settings_PixelPin_ppsso_colour");
		var buttonColourValue = buttonColourDropdown.value;

		var buttonSizeDropdown = document.getElementById("wpl_settings_PixelPin_ppsso_size");
		var buttonSizeValue = buttonSizeDropdown.value;

		var showButtonTextDropdown = document.getElementById("wpl_settings_PixelPin_ppsso_show_text");
		var showButtonTextValue = showButtonTextDropdown.value;

		var buttonText = document.getElementById("wpl_settings_PixelPin_ppsso_text");
		var buttonTextValue = buttonText.value;
		
		if(customiseValue === '0'){
			jQuery('#previewSSObutton').addClass('ppsso-btn');
			previewSSOButton.innerHTML = 'Log In With <span class="ppsso-logotype">PixelPin</span>';
		} else {
			if(showButtonTextValue === '0'){
				jQuery('#previewSSObutton').addClass('ppsso-btn ' + buttonColourValue + ' ' + buttonSizeValue);
				previewSSOButton.innerHTML = '';
			} else {
				jQuery('#previewSSObutton').addClass('ppsso-btn ' + buttonColourValue + ' ' + buttonSizeValue);
				previewSSOButton.innerHTML = buttonTextValue + ' <span class="ppsso-logotype">PixelPin</span>';
			}
		}
	}
</script>
<?php
	foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item ):
		$provider_id                = isset( $item["provider_id"]       ) ? $item["provider_id"]       : '';
		$provider_name              = isset( $item["provider_name"]     ) ? $item["provider_name"]     : '';

		$require_client_id          = isset( $item["require_client_id"] ) ? $item["require_client_id"] : '';
		$require_api_key            = isset( $item["require_api_key"]   ) ? $item["require_api_key"]   : '';
		$default_api_scope          = isset( $item["default_api_scope"] ) ? $item["default_api_scope"] : '';
		$provide_email              = isset( $item["provide_email"]     ) ? $item["provide_email"]     : '';

		$provider_new_app_link      = isset( $item["new_app_link"]      ) ? $item["new_app_link"]      : '';
		$provider_userguide_section = isset( $item["userguide_section"] ) ? $item["userguide_section"] : '';

		$provider_callback_url      = "" ;

		if( ! ( ( isset( $item["default_network"] ) && $item["default_network"] ) || get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) )
		{
			continue;
		}

		// default endpoint_url
		$endpoint_url = WORDPRESS_PIXELPIN_LOGIN_HYBRIDAUTH_ENDPOINT_URL;

		if( isset( $item["callback"] ) && $item["callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . '?hauth.done=' . $provider_id . '</span>';
		}

		if( isset( $item["custom_callback"] ) && $item["custom_callback"] )
		{
			$provider_callback_url  = '<span style="color:green">' . $endpoint_url . 'endpoints/' . strtolower( $provider_id ) . '.php</span>';
		}

		$setupsteps = 0;
?>
		<a name="setup<?php echo strtolower( $provider_id ) ?>"></a>
		<div class="stuffbox" id="namediv">
			<h3>
				<label class="wp-neworks-label">
					<img alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" style="vertical-align: top;width:16px;height:16px;" /> <?php _wpl_e( $provider_name, 'wordpress-pixelpin-login' ) ?>
				</label>
			</h3>
			<div class="inside">
				<table class="form-table editcomment">
					<tbody>
						<tr>
							<td style="width:125px"><?php _wpl_e("Enabled", 'wordpress-pixelpin-login') ?>:</td>
							<td>
								<select
									name="<?php echo 'wpl_settings_' . $provider_id . '_enabled' ?>"
									id="<?php echo 'wpl_settings_' . $provider_id . '_enabled' ?>"
									onChange="toggleproviderkeys('<?php echo $provider_id; ?>')"
								>
									<option value="1" <?php if(   get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
									<option value="0" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
								</select>
							</td>
							<td style="width:260px">&nbsp;</td>
						</tr>

						<?php if ( $provider_new_app_link ){ ?>
							<?php if ( $require_client_id ){ // key or id ? ?>
								<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wpl_e("Client ID", 'wordpress-pixelpin-login') ?>:</td>
									<td><input pattern=".{30,30}" require title="The Client ID is exactly 30 characters long" dir="ltr" type="text" name="<?php echo 'wpl_settings_' . $provider_id . '_app_id' ?>" value="<?php echo get_option( 'wpl_settings_' . $provider_id . '_app_id' ); ?>" ></td>
									<td><a href="http://developer.pixelpin.io/developeraccount.php" target="_blank">Need A Client ID? Create a PixelPin Developer Account.</a></td>
								</tr>
							<?php } else { ?>
								<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wpl_e("Client Key", 'wordpress-pixelpin-login') ?>:</td>
									<td><input pattern=".{30,30}" require title="The Client Key is exactly 30 characters long" dir="ltr" type="text" name="<?php echo 'wpl_settings_' . $provider_id . '_app_key' ?>" value="<?php echo get_option( 'wpl_settings_' . $provider_id . '_app_key' ); ?>" ></td>
									<td><a href="http://developer.pixelpin.io/developeraccount.php" target="_blank">Need A Client ID? Create a PixelPin Developer Account.</a></td>
								</tr>
							<?php }; ?>

							<?php if( ! $require_api_key ) { ?>
								<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wpl_e("Client Secret", 'wordpress-pixelpin-login') ?>:</td>
									<td><input pattern=".{30,30}" require title="The Client Secret is exactly 30 characters long" dir="ltr" type="text" name="<?php echo 'wpl_settings_' . $provider_id . '_app_secret' ?>" value="<?php echo get_option( 'wpl_settings_' . $provider_id . '_app_secret' ); ?>" ></td>
									<td><a href="http://developer.pixelpin.io/developeraccount.php" target="_blank">Need A Client Secret? Create a PixelPin Developer Account.</a></td>
								</tr>
							<?php } ?>
								
							<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
								<td style="width:125px"><?php _wpl_e("Show User's Gender?", 'wordpress-pixelpin-login') ?>:</td>
								<td>
									<select
										name="<?php echo 'wpl_settings_' . $provider_id . '_gender_enabled' ?>"
										id="<?php echo 'wpl_settings_' . $provider_id . '_gender_enabled' ?>"
										onChange="toggleproviderkeys('<?php echo $provider_id; ?>')"
									>
										<option value="1" <?php if(   get_option( 'wpl_settings_' . $provider_id . '_gender_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
										<option value="0" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_gender_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
									</select>
								</td>
								<td style="width:260px">&nbsp;</td>
							</tr>

							<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
								<td style="width:125px"><?php _wpl_e("Require User's Phone Number?", 'wordpress-pixelpin-login') ?>:</td>
								<td>
									<select
										name="<?php echo 'wpl_settings_' . $provider_id . '_phone_enabled' ?>"
										id="<?php echo 'wpl_settings_' . $provider_id . '_phone_enabled' ?>"
										onChange="toggleproviderkeys('<?php echo $provider_id; ?>')"
									>
										<option value="1" <?php if(   get_option( 'wpl_settings_' . $provider_id . '_phone_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
										<option value="0" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_phone_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
									</select>
								</td>
								<td style="width:260px">&nbsp;</td>
							</tr>

							<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
								<td style="width:125px"><?php _wpl_e("Require User's Postal Address?", 'wordpress-pixelpin-login') ?>:</td>
								<td>
									<select
										name="<?php echo 'wpl_settings_' . $provider_id . '_address_enabled' ?>"
										id="<?php echo 'wpl_settings_' . $provider_id . '_address_enabled' ?>"
										onChange="toggleproviderkeys('<?php echo $provider_id; ?>')"
									>
										<option value="1" <?php if(   get_option( 'wpl_settings_' . $provider_id . '_address_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
										<option value="0" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_address_enabled' ) ) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
									</select>
								</td>
								<td style="width:260px">&nbsp;</td>
							</tr>

							<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
									<td><?php _wpl_e("Redirect URI", 'wordpress-pixelpin-login') ?>:</td>
									<td><input readonly dir="ltr" type="text" name="<?php echo 'wpl_settings_' . $provider_id . '_redirect_uri' ?>" value="<?php echo strip_tags( $provider_callback_url ); ?>" ></td>
								</tr>

							<?php if( get_option( 'wpl_settings_development_mode_enabled' ) ) { ?>
								<?php if( $default_api_scope ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wpl_e("Application Scope", 'wordpress-pixelpin-login') ?>:</td>
										<td><input dir="ltr" type="text" name="<?php echo 'wpl_settings_' . $provider_id . '_app_scope' ?>" value="<?php echo get_option( 'wpl_settings_' . $provider_id . '_app_scope' ); ?>" ></td>
									</tr>
								<?php } ?>

								<?php if( $provider_callback_url ) { ?>
									<tr valign="top" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
										<td><?php _wpl_e("Callback URL", 'wordpress-pixelpin-login') ?>:</td>
										<td><input dir="ltr" type="text" name="" value="<?php echo  strip_tags( $provider_callback_url ); ?>" readonly="readonly"></td>
									</tr>
								<?php } ?>
							<?php } ?>
						<?php } // if require registration ?>
					</tbody>
				</table>
				<table class="form-table editcomment">
					<tbody>
						<?php if ( $provider_new_app_link ){ ?>
							<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) echo 'style="display:none"'; ?> class="wpl_tr_settings_<?php echo $provider_id; ?>" >
								<td style="width:200px"><?php _wpl_e("Do you want to customise the Log In Button?", 'wordpress-pixelpin-login') ?>:</td>
								<td>
									<select
										name="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_custom' ?>"
										id="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_custom' ?>"
										onChange="toggleppssosettings('<?php echo $provider_id; ?>'); toggleppssopreview();"
									>
										<option value="1" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' )) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
										<option value="0" <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' )) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
									</select>
								</td>
								<td style="width:260px">&nbsp;</td>
							</tr>
								<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' ) ) echo 'style="display:none"'; ?> class="wpl_tr_ppsso_settings_<?php echo $provider_id; ?>" >
									<td style="width:200px"><?php _wpl_e("Log in Button Colour", 'wordpress-pixelpin-login') ?>:</td>
									<td>
										<select
											name="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_colour' ?>"
											id="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_colour' ?>"
											onChange="toggleppssosettings('<?php echo $provider_id; ?>');  toggleppssopreview();"
										>
											<option value="" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_colour' ) === "" ) echo "selected"; ?> ><?php _wpl_e("Default (Purple)", 'wordpress-pixelpin-login') ?></option>
											<option value="ppsso-cyan" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_colour' ) === "ppsso-cyan"  ) echo "selected"; ?> ><?php _wpl_e("Cyan", 'wordpress-pixelpin-login') ?></option>
											<option value="ppsso-pink" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_colour' ) === "ppsso-pink"  ) echo "selected"; ?> ><?php _wpl_e("Pink", 'wordpress-pixelpin-login') ?></option>
											<option value="ppsso-white" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_colour' ) === "ppsso-white"  ) echo "selected"; ?> ><?php _wpl_e("White (For Dark Backgrounds)", 'wordpress-pixelpin-login') ?></option>
										</select>
									</td>
									<td style="width:260px">&nbsp;</td>
								</tr>
								<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' ) ) echo 'style="display:none"'; ?> class="wpl_tr_ppsso_settings_<?php echo $provider_id; ?>" >
									<td style="width:200px"><?php _wpl_e("Log in Button Size", 'wordpress-pixelpin-login') ?>:</td>
									<td>
										<select
											name="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_size' ?>"
											id="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_size' ?>"
											onChange="toggleppssosettings('<?php echo $provider_id; ?>');  toggleppssopreview();"
										>
											<option value="ppsso-logo-lg" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_size' ) === "ppsso-logo-lg" ) echo "selected"; ?> ><?php _wpl_e("Default (Large)", 'wordpress-pixelpin-login') ?></option>
											<option value="ppsso-md ppsso-logo-md" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_size' ) === "ppsso-md ppsso-logo-md"  ) echo "selected"; ?> ><?php _wpl_e("Medium", 'wordpress-pixelpin-login') ?></option>
											<option value="ppsso-sm ppsso-logo-sm" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_size' ) === "ppsso-sm ppsso-logo-sm"  ) echo "selected"; ?> ><?php _wpl_e("Small", 'wordpress-pixelpin-login') ?></option>
										</select>
									</td>
									<td style="width:260px">&nbsp;</td>
								</tr>
								<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' ) ) echo 'style="display:none"'; ?> class="wpl_tr_ppsso_settings_<?php echo $provider_id; ?>" >
									<td style="width:200px"><?php _wpl_e("Show Log in Button Text?", 'wordpress-pixelpin-login') ?>:</td>
									<td>
										<select
											name="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_show_text' ?>"
											id="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_show_text' ?>"
											onChange="toggleppssosettings('<?php echo $provider_id; ?>');  toggleppssopreview();"
										>
											<option value="1" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_show_text' ) === "1" ) echo "selected"; ?> ><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
											<option value="0" <?php if( get_option( 'wpl_settings_' . $provider_id . '_ppsso_show_text' ) === "0" ) echo "selected"; ?> ><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
										</select>
									</td>
									<td style="width:260px">&nbsp;</td>
								</tr>
								<tr <?php if( ! get_option( 'wpl_settings_' . $provider_id . '_ppsso_custom' ) ) echo 'style="display:none"'; ?> valign="top" class="wpl_tr_ppsso_settings_<?php echo $provider_id; ?>" >
									<td><?php _wpl_e("Log in Button Text (Can be empty)", 'wordpress-pixelpin-login') ?>:</td>
									<td><input dir="ltr" type="text" onChange="toggleppssopreview();" id="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_text' ?>" name="<?php echo 'wpl_settings_' . $provider_id . '_ppsso_text' ?>" value="<?php if (get_option( 'wpl_settings_' . $provider_id . '_ppsso_text' )) { echo get_option( 'wpl_settings_' . $provider_id . '_ppsso_text' ); } ?>" ></td>
								</tr>
						<?php } // if require registration ?>
					</tbody>
				</table>
				<?php if ( get_option( 'wpl_settings_' . $provider_id . '_enabled' ) ) : ?>
					<?php if (  $provider_id == "Steam" ) : ?>
						<div class="fade updated">
							<p>
								<b><?php _wpl_e("Notes", 'wordpress-pixelpin-login') ?>:</b>
							</p>
							<p>
								      1. <?php echo sprintf( _wpl__("<b>%s</b> do not require an external application, however if the Web API Key is provided, then WPL will be able to get more information about the connected %s users", 'wordpress-pixelpin-login'), $provider_name , $provider_name ) ?>.
								<br />2. <?php echo sprintf( _wpl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-pixelpin-login'), $provider_name ) ?>.

								<?php _wpl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-pixelpin-login&wplp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-pixelpin-login') ?>.
							</p>
						</div>
					<?php elseif ( $provider_new_app_link && strlen( trim( get_option( 'wpl_settings_' . $provider_id . '_app_secret' ) ) ) == 0 ) : ?>
					<?php elseif ( in_array( $provider_id, array( "Twitter", "Identica", "Tumblr", "Goodreads", "500px", "Vkontakte", "Gowalla", "Steam" ) ) ) : ?>
						<div class="fade updated">
							<p>
								<b><?php _wpl_e("Note", 'wordpress-pixelpin-login') ?>:</b>

								<?php echo sprintf( _wpl__("<b>%s</b> do not provide their user's email address and by default a random email will then be generated for them instead", 'wordpress-pixelpin-login'), $provider_name ) ?>.

								<?php _wpl_e('To change this behaviour and to force new registered users to provide their emails before they get in, goto <b><a href="options-general.php?page=wordpress-pixelpin-login&wplp=bouncer">Bouncer</a></b> and enable <b>Profile Completion</b>', 'wordpress-pixelpin-login') ?>.
							</p>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<br />
				<div
					class="wpl_div_settings_help_<?php echo $provider_id; ?>"
					style="<?php if( isset( $_REQUEST["enable"] ) && ! isset( $_REQUEST["settings-updated"] ) && $_REQUEST["enable"] == $provider_id ) echo "-"; // <= lolz ?>display:none;"
				>
				</div>
			</div>
		</div>
<?php
	endforeach;
?>
	<input type="submit" class="button-primary" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" />
<?php
	// HOOKABLE:
	do_action( "wpl_component_networks_setup_end" );
}


// --------------------------------------------------------------------
