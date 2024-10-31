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
* BuddyPress integration.
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// --------------------------------------------------------------------

function wpl_component_buddypress_setup()
{
	$sections = array(
		'user_avatar'     => 'wpl_component_buddypress_setup_user_avatar',
		'profile_mapping' => 'wpl_component_buddypress_setup_profile_mapping',
	);

	$sections = apply_filters( 'wpl_component_buddypress_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_buddypress_setup_sections', $action );
	}
?>
<div>
	<?php
		// HOOKABLE:
		do_action( 'wpl_component_buddypress_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;">
		<input type="submit" class="button-primary" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" />
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wpl_component_buddypress_setup_user_avatar()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Users avatars", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php
				if( get_option( 'wpl_settings_users_avatars' ) == 1 ){
					_wpl_e("<b>Users avatars</b> is currently set to: <b>Display users avatars from pixelpin networks when available</b>", 'wordpress-pixelpin-login');
				}
				else{
					_wpl_e("<b>Users avatars</b> is currently set to: <b>Display the default WordPress avatars</b>", 'wordpress-pixelpin-login');
				}
			?>.
		</p>

		<p class="description">
			<?php _wpl_e("To change this setting, go to <b>Widget</b> &gt; <b>Basic Settings</b> &gt <b>Users avatars</b>, then select the type of avatars that you want to display for your users", 'wordpress-pixelpin-login') ?>.
		</p>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------

function wpl_component_buddypress_setup_profile_mapping()
{
	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';

	$wpl_settings_buddypress_enable_mapping = get_option( 'wpl_settings_buddypress_enable_mapping' );
	$wpl_settings_buddypress_xprofile_map   = get_option( 'wpl_settings_buddypress_xprofile_map' );

	# http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Profile.html
	$ha_profile_fields = array(
		array( 'field' => 'provider'    , 'label' => _wpl__( "Provider name"            , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The the provider or pixelpin network name the user used to connected"                                                     , 'wordpress-pixelpin-login') ),
		array( 'field' => 'identifier'  , 'label' => _wpl__( "Provider user Identifier" , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The Unique user's ID on the connected provider. Depending on the provider, this field can be an number, Email, URL, etc", 'wordpress-pixelpin-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wpl__( "Profile URL"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "Link to the user profile on the provider web site"                                                                      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wpl__( "Website URL"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User website, blog or web page"                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wpl__( "Photo URL"                , 'wordpress-pixelpin-login'), 'description' => _wpl__( "Link to user picture or avatar on the provider web site"                                                                , 'wordpress-pixelpin-login') ),
		array( 'field' => 'displayName' , 'label' => _wpl__( "Display name"             , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User Display name. If not provided by pixelpin network, WPL will return a concatenation of the user first and last name"  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'description' , 'label' => _wpl__( "Description"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "A short about me"                                                                                                       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'firstName'   , 'label' => _wpl__( "First name"               , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's first name"                                                                                                      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'lastName'    , 'label' => _wpl__( "Last name"                , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's last name"                                                                                                       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'gender'      , 'label' => _wpl__( "Gender"                   , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's gender. Values are 'female', 'male' or blank"                                                                    , 'wordpress-pixelpin-login') ),
		array( 'field' => 'language'    , 'label' => _wpl__( "Language"                 , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's language"                                                                                                        , 'wordpress-pixelpin-login') ),
		array( 'field' => 'age'         , 'label' => _wpl__( "Age"                      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User' age. Note that WPL do not calculate this field. We return it as it was provided"                                  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wpl__( "Birth day"                , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The day in the month in which the person was born. Not to confuse it with 'Birth date'"                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wpl__( "Birth month"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The month in which the person was born"                                                                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wpl__( "Birth year"               , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The year in which the person was born"                                                                                  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthDate'   , 'label' => _wpl__( "Birth date"               , 'wordpress-pixelpin-login'), 'description' => _wpl__( "Complete birthday in which the person was born. Format: YYYY-MM-DD"                                                     , 'wordpress-pixelpin-login') ),
		array( 'field' => 'email'       , 'label' => _wpl__( "Email"                    , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's email address. Not all of provider grant access to the user email"                                               , 'wordpress-pixelpin-login') ),
		array( 'field' => 'phone'       , 'label' => _wpl__( "Phone"                    , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's phone number"                                                                                                    , 'wordpress-pixelpin-login') ),
		array( 'field' => 'address'     , 'label' => _wpl__( "Address"                  , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's address"                                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'country'     , 'label' => _wpl__( "Country"                  , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's country"                                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'region'      , 'label' => _wpl__( "Region"                   , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's state or region"                                                                                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'city'        , 'label' => _wpl__( "City"                     , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's city"                                                                                                            , 'wordpress-pixelpin-login') ),
		array( 'field' => 'zip'         , 'label' => _wpl__( "Zip"                      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's zipcode"                                                                                                         , 'wordpress-pixelpin-login') ),
	);
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Profile mappings", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wpl_e("When <b>Profile mapping</b> is enabled, WPL will try to automatically fill in Buddypress users profiles from their pixelpin networks profiles", 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<b><?php _wpl_e('Notes', 'wordpress-pixelpin-login') ?>:</b>
		</p>

		<p class="description">
			1. <?php _wpl_e('<b>Profile mapping</b> will only work for new users. Profile mapping for returning users will implemented in future version of WPL', 'wordpress-pixelpin-login') ?>.
			<br />
			2. <?php _wpl_e('Not all the mapped fields will be filled. Some providers and pixelpin networks do not give away many information about their users', 'wordpress-pixelpin-login') ?>.
			<br />
			3. <?php _wpl_e('WPL can only map <b>Single Fields</b>. Supported fields types are: Multi-line Text Areax, Text Box, URL, Date Selector and Number', 'wordpress-pixelpin-login') ?>.
		</p>

		<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
		  <tr>
			<td width="200" align="right"><strong><?php _wpl_e("Enable profile mapping", 'wordpress-pixelpin-login') ?> :</strong></td>
			<td>
				<select name="wpl_settings_buddypress_enable_mapping" id="wpl_settings_buddypress_enable_mapping" style="width:100px" onChange="toggleMapDiv();">
					<option <?php if( $wpl_settings_buddypress_enable_mapping == 1 ) echo "selected"; ?> value="1"><?php _wpl_e("Yes", 'wordpress-pixelpin-login') ?></option>
					<option <?php if( $wpl_settings_buddypress_enable_mapping == 2 ) echo "selected"; ?> value="2"><?php _wpl_e("No", 'wordpress-pixelpin-login') ?></option>
				</select>
			</td>
		  </tr>
		</table>
		<br>
	</div>
</div>

<div id="xprofilemapdiv" class="stuffbox" style="<?php if( $wpl_settings_buddypress_enable_mapping == 2 ) echo "display:none;"; ?>">
	<h3>
		<label><?php _wpl_e("Fields Map", 'wordpress-pixelpin-login') ?></label>
	</h3>

	<div class="inside">
		<p>
			<?php _wpl_e("Here you can create a new map by placing WPL users profiles fields to the appropriate destination fields", 'wordpress-pixelpin-login') ?>.
			<?php _wpl_e('The left column shows the available <b>WPL users profiles fields</b>: These select boxes are called <b>source</b> fields', 'wordpress-pixelpin-login') ?>.
			<?php _wpl_e('The right column shows the list of <b>Buddypress profiles fields</b>: Those are the <b>destination</b> fields', 'wordpress-pixelpin-login') ?>.
			<?php _wpl_e('If you don\'t want to map a particular Buddypress field, then leave the source for that field blank', 'wordpress-pixelpin-login') ?>.
		</p>

		<hr />

		<?php
			if ( bp_has_profile() )
			{
				while ( bp_profile_groups() )
				{
					global $group;

					bp_the_profile_group();
					?>
						<h4><?php echo sprintf( _wpl__("Fields Group '%s'", 'wordpress-pixelpin-login'), $group->name ) ?> :</h4>

						<table width="100%" border="0" cellpadding="5" cellspacing="2" style="border-top:1px solid #ccc;">
							<?php
								while ( bp_profile_fields() )
								{
									global $field;

									bp_the_profile_field();
									?>
										<tr>
											<td width="270" align="right" valign="top">
												<?php
													$map = isset( $wpl_settings_buddypress_xprofile_map[$field->id] ) ? $wpl_settings_buddypress_xprofile_map[$field->id] : 0;
													$can_map_it = true;

													if( ! in_array( $field->type, array( 'textarea', 'textbox', 'url', 'datebox', 'number' ) ) ){
														$can_map_it = false;
													}
												?>
												<select name="wpl_settings_buddypress_xprofile_map[<?php echo $field->id; ?>]" style="width:255px" id="bb_profile_mapping_selector_<?php echo $field->id; ?>" onChange="showMappingConfirm( <?php echo $field->id; ?> );" <?php if( ! $can_map_it ) echo "disabled"; ?>>
													<option value=""></option>
													<?php
														if( $can_map_it ){
															foreach( $ha_profile_fields as $item ){
															?>
																<option value="<?php echo $item['field']; ?>" <?php if( $item['field'] == $map ) echo "selected"; ?> ><?php echo $item['label']; ?></option>
															<?php
															}
														}
													?>
												</select>
											</td>
											<td valign="top" align="center" width="50">
												<img src="<?php echo $assets_base_url; ?>arr_right.png" />
											</td>
											<td valign="top">
												<strong><?php echo $field->name; ?></strong>
												<?php
													if( ! $can_map_it ){
													?>
														<p class="description">
															<?php _wpl_e("<b>WPL</b> can not map this field. Supported field types are: <em>Multi-line Text Areax, Text Box, URL, Date Selector and Number</em>", 'wordpress-pixelpin-login'); ?>.
														</p>
													<?php
													}
													else{
													?>
														<?php
															foreach( $ha_profile_fields as $item ){
														?>
															<p class="description bb_profile_mapping_confirm_<?php echo $field->id; ?>" style="margin-left:0;<?php if( $item['field'] != $map ) echo "display:none;"; ?>" id="bb_profile_mapping_confirm_<?php echo $field->id; ?>_<?php echo $item['field']; ?>">
																<?php echo sprintf( _wpl__( "WPL <b>%s</b> is mapped to Buddypress <b>%s</b> field", 'wordpress-pixelpin-login' ), $item['label'], $field->name ); ?>.
																<br />
																<em><b><?php echo $item['label']; ?>:</b> <?php echo $item['description']; ?>.</em>
															</p>
														<?php
															}
														?>
													<?php
													}
												?>
											</td>
										</tr>
									<?php
								}
							?>
						</table>
					<?php
				}
			}
		?>
	</div>
</div>
<script>
	function toggleMapDiv(){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		var em = jQuery( "#wpl_settings_buddypress_enable_mapping" ).val();

		if( em == 2 ) jQuery( "#xprofilemapdiv" ).hide();
		else jQuery( "#xprofilemapdiv" ).show();
	}

	function showMappingConfirm( field ){
		if(typeof jQuery=="undefined"){
			alert( "Error: WordPress PixelPin Login require jQuery to be installed on your wordpress in order to work!" );

			return false;
		}

		var el = jQuery( "#bb_profile_mapping_selector_" + field ).val();

		jQuery( ".bb_profile_mapping_confirm_" + field ).hide();

		jQuery( "#bb_profile_mapping_confirm_" + field + "_" + el ).show();
	}
</script>
<?php
}

// --------------------------------------------------------------------
