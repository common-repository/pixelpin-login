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

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wpl_component_networks_sidebar()
{
	// HOOKABLE:
	do_action( "wpl_component_networks_sidebar_start" );

	$sections = array(
		'what_is_this'   => 'wpl_component_networks_sidebar_what_is_this',
		'add_more_idps'  => 'wpl_component_networks_sidebar_add_more_idps',
		'auth_button_preview' => 'wpl_component_networks_sidebar_auth_button_preview',
		'basic_insights' => 'wpl_component_networks_sidebar_basic_insights',
	);

	$sections = apply_filters( 'wpl_component_networks_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_networks_sidebar_sections', $action );
	}

	// HOOKABLE:
	do_action( 'wpl_component_networks_sidebar_sections' );
}

// --------------------------------------------------------------------

function wpl_component_networks_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("Welcome to WordPress PixelPin Login", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p style="padding:0;margin:12px 0;">
				<?php _wpl_e('<b>WordPress PixelPin Login</b> is a PixelPin implementation of <b>WordPress Social Login</b>. PixelPin Login authenticates and authorises using the OpenID Connect protocol.', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:12px 0;">
				<?php _wpl_e('<b>WordPress PixelPin Login</b> allows your website visitors and customers to register on using their existing PixelPin account, eliminating the need to fill out registration forms and remember usernames and passwords', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wpl_e('<b>WordPress PixelPin Login</b> come with a number of useful <b><a href="options-general.php?page=wordpress-pixelpin-login&wplp=components">Components</a></b> or add-ons that can be essential for your needs', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wpl_e('If you are still new to things, we recommend that you read the <b><a href="http://miled.github.io/wordpress-social-login/documentation.html" target="_blank">WPL Documentation</a></b> and to make sure your server meet the minimum system requirements by running <b><a href="http://hybridauth.com/hawp4/wp-admin/options-general.php?page=wordpress-pixelpin-login&wplp=tools">WPL Diagnostics</a></b>', 'wordpress-pixelpin-login') ?>.
			</p>
			<p style="padding:0;margin:0 0 12px;">
				<?php _wpl_e('If you run into any issue, then refer to <b><a href="http://miled.github.io/wordpress-social-login/support.html" target="_blank">Help &amp; Support</a></b>. If you find a bug, raise it on WordPress PixelPin Login\'s <b><a href="https://github.com/PixelPinPlugins/WordPress-PixelPin-Login/issues" target="_blank">Github issue tracker</a></b>', 'wordpress-pixelpin-login') ?>.
			</p>
		</div>
	</div>
</div>
<?php
}

add_action( 'wpl_component_networks_sidebar_what_is_this', 'wpl_component_networks_sidebar_what_is_this' );

// --------------------------------------------------------------------

function wpl_component_networks_sidebar_add_more_idps()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/32x32/ssoicon/';
?>

<div class="postbox" id="ppenable">
	<div class="inside">
		<h3><?php _wpl_e("Enable PixelPin", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p style="padding:0;margin:0 0 12px;">
				<?php _wpl_e('<b>Just Click</b> on the PixelPin logo to enable PixelPin', 'wordpress-pixelpin-login') ?>.
			</p>

			<div style="width: 320px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<?php
					$nb_used = count( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG );

					foreach( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG AS $item )
					{
						$provider_id   = isset( $item["provider_id"]   ) ? $item["provider_id"]   : '';
						$provider_name = isset( $item["provider_name"] ) ? $item["provider_name"] : '';

						if( isset( $item["default_network"] ) && $item["default_network"] )
						{
							continue;
						}

						if( ! get_option( 'wpl_settings_' . $provider_id . '_enabled' ) )
						{
							?>
								<a href="options-general.php?page=wordpress-pixelpin-login&wplp=networks&enable=<?php echo $provider_id ?>#setup<?php echo strtolower( $provider_id ) ?>"><img src="<?php echo $assets_base_url . strtolower( $provider_id ) . '.png' ?>" alt="<?php echo $provider_name ?>" title="<?php echo $provider_name ?>" /></a>
							<?php

							$nb_used--;
						}
					}

					if( $nb_used == count( $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG ) )
					{
						_wpl_e("PixelPin is Enabled", 'wordpress-pixelpin-login');
					}
				?>
			</div>
		</div>
	</div>
</div>

<?php
}

add_action( 'wpl_component_networks_sidebar_add_more_idps', 'wpl_component_networks_sidebar_add_more_idps' );

// --------------------------------------------------------------------

function wpl_component_networks_sidebar_auth_button_preview()
{
	if( ! wp_style_is( 'wpl-widget', 'registered' ) )
	{
		wp_register_style( "wpl-widget", "https://developer-assets.pixelpin.io/sso-buttons/sso-button.css" );

	}

	wp_enqueue_style( "wpl-widget" );
?>
<style>
.wp-pixelpin-login-provider-list { padding: 10px; }
.wp-pixelpin-login-provider-list a {text-decoration: none; }
.wp-pixelpin-login-provider-list img{ border: 0 none; }
</style>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("Button preview", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wpl_e("This is a preview of what the login button will look like", 'wordpress-pixelpin-login') ?>. 
			</p>

			<h4>Live:</h4>
			<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<?php 
					$ppsso_custom = get_option('wpl_settings_pixelpin_ppsso_custom');
					$ppsso_colour = get_option('wpl_settings_pixelpin_ppsso_colour');
					$ppsso_size = get_option('wpl_settings_pixelpin_ppsso_size');
					$ppsso_show_text = get_option('wpl_settings_pixelpin_ppsso_show_text');
					$ppsso_text = get_option('wpl_settings_pixelpin_ppsso_text');
				?>
				<?php if(get_option('wpl_settings_pixelpin_ppsso_custom')){ ?> 
					<a href="#" 
						title="<?php echo sprintf( _wpl__("Connect with PixelPin %s", 'wordpress-pixelpin-login'), $provider_name ) ?>" 
						class="ppsso-btn <?php echo $ppsso_size ?> <?php echo $ppsso_colour ?>" 
						data-provider="<?php echo $provider_id ?>">
						<?php if(get_option('wpl_settings_pixelpin_ppsso_show_text')){ echo $ppsso_text; ?> 
							<span class="ppsso-logotype">PixelPin</span> 
						<?php } ?>
					</a>
				<?php } else { ?>
					<a href="#" 
						title="<?php echo sprintf( _wpl__("Connect with PixelPin %s", 'wordpress-pixelpin-login'), $provider_name ) ?>" 
						class="ppsso-btn" 
						data-provider="<?php echo $provider_id ?>">
						Log In With <span class="ppsso-logotype">PixelPin</span> 
					</a>
				<?php } ?>
			</div> 
			<h4>Preview:</h4>
			<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
				<a href='#' id='previewSSObutton' class='ppsso-btn <?php echo $ppsso_size ?> <?php echo $ppsso_colour ?>'>
						<?php if(get_option('wpl_settings_pixelpin_ppsso_show_text')){ echo $ppsso_text; ?> 
							<span class="ppsso-logotype">PixelPin</span> 
						<?php } ?></a>
			</div> 
		</div>
	</div> 
</div> 		
<?php
}

add_action( 'wpl_component_networks_sidebar_auth_button_preview', 'wpl_component_networks_sidebar_auth_button_preview' );

// --------------------------------------------------------------------

function wpl_component_networks_sidebar_basic_insights()
{
	GLOBAL $WORDPRESS_PIXELPIN_LOGIN_PROVIDERS_CONFIG;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/32x32/ssoicon/';
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("Insights", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px">
			<?php
				$total_users     = wpl_get_wordpess_users_count();
				$total_users_wpl = wpl_get_wpl_users_count();

				if( $total_users && $total_users_wpl )
				{
					$users_conversion = ( 100 * $total_users_wpl ) / $total_users;
					?>
						<!-- Insights - conversions -->
						<h4 style="border-bottom:1px solid #ccc"><?php _wpl_e("Conversions", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<tr>
								<td width="60%"><?php _wpl_e("WP users", 'wordpress-pixelpin-login') ?></td><td><?php echo $total_users; ?></td>
							</tr>
							<tr>
								<td><?php _wpl_e("WPL users", 'wordpress-pixelpin-login') ?></td><td><?php echo $total_users_wpl; ?></td>
							</tr>
							<tr>
								<td><?php _wpl_e("Conversions", 'wordpress-pixelpin-login') ?></td><td style="border-top:1px solid #ccc">+<b><?php echo number_format($users_conversion, 2, '.', ''); ?></b> %</td>
							</tr>
						</table>

						<!-- Insights by provider -->
						<?php
							$data = wpl_get_stored_hybridauth_user_profiles_count_by_field( 'provider' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wpl_e("By provider", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								$total_profiles_wpl = 0;

								foreach( $data as $item ){
								?>
									<tr>
										<td width="60%">
											<img src="<?php echo $assets_base_url . strtolower( $item->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php _wpl_e($item->provider, 'wordpress-pixelpin-login') ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
									$total_profiles_wpl += (int) $item->items;
								}
							?>
							<tr>
								<td align="right">&nbsp;</td><td style="border-top:1px solid #ccc"><b><?php echo $total_profiles_wpl; ?></b> <?php _wpl_e("WPL profiles", 'wordpress-pixelpin-login') ?></td>
							</tr>
							<tr>
								<td align="right">&nbsp;</td><td><b><?php echo $total_users_wpl; ?></b> <?php _wpl_e("WPL users", 'wordpress-pixelpin-login') ?></td>
							</tr>
						</table>

						<!-- Insights by gender -->
						<?php
							$data = wpl_get_stored_hybridauth_user_profiles_count_by_field( 'gender' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wpl_e("By gender", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $data as $item ){
									if( ! $item->gender ) $item->gender = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->gender ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>

						<!-- Insights by age -->
						<?php
							$data = wpl_get_stored_hybridauth_user_profiles_count_by_field( 'age' );
						?>
						<h4 style="border-bottom:1px solid #ccc"><?php _wpl_e("By age", 'wordpress-pixelpin-login') ?></h4>
						<table width="90%">
							<?php
								foreach( $data as $item ){
									if( ! $item->age ) $item->age = "Unknown";
								?>
									<tr>
										<td width="60%">
											<?php echo ucfirst( $item->age ); ?>
										</td>
										<td>
											<?php echo $item->items; ?>
										</td>
									</tr>
								<?php
								}
							?>
						</table>
					<?php
				}
				else
				{
					?>
						<p>
							<?php _wpl_e("There's no data yet", 'wordpress-pixelpin-login') ?>.
						</p>
					<?php
				}
			?>
		</div>
	</div>
</div>
<?php
}

add_action( 'wpl_component_networks_sidebar_basic_insights', 'wpl_component_networks_sidebar_basic_insights' );

// --------------------------------------------------------------------
