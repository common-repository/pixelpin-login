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

function wpl_component_users_profiles( $user_id )
{
	// HOOKABLE:
	do_action( "wpl_component_users_profiles_start" );

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/16x16/';

	$linked_accounts = wpl_get_stored_hybridauth_user_profiles_by_user_id( $user_id );

	// is it a WPL user?
	if( ! $linked_accounts )
	{
?>
<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
	<?php _wpl_e( "This's not a WPL user!", 'wordpress-pixelpin-login' ); ?>.
</div>
<?php
		return;
	}

	# http://hybridauth.sourceforge.net/userguide/Profile_Data_User_Profile.html
	$ha_profile_fields = array(
		array( 'field' => 'identifier'  , 'label' => _wpl__( "Provider user ID" , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The Unique user's ID on the connected provider. Depending on the provider, this field can be an number, Email, URL, etc", 'wordpress-pixelpin-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wpl__( "Profile URL"      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "Link to the user profile on the provider web site"                                                                      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wpl__( "Website URL"      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User website, blog or web page"                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wpl__( "Photo URL"        , 'wordpress-pixelpin-login'), 'description' => _wpl__( "Link to user picture or avatar on the provider web site"                                                                , 'wordpress-pixelpin-login') ),
		array( 'field' => 'displayName' , 'label' => _wpl__( "Display name"     , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User Display name. If not provided by pixelpin network, WPL will return a concatenation of the user first and last name"  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'description' , 'label' => _wpl__( "Description"      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "A short about me"                                                                                                       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'firstName'   , 'label' => _wpl__( "First name"       , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's first name"                                                                                                      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'lastName'    , 'label' => _wpl__( "Last name"        , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's last name"                                                                                                       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'gender'      , 'label' => _wpl__( "Gender"           , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's gender. Values are 'female', 'male' or blank"                                                                    , 'wordpress-pixelpin-login') ),
		array( 'field' => 'language'    , 'label' => _wpl__( "Language"         , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's language"                                                                                                        , 'wordpress-pixelpin-login') ),
		array( 'field' => 'age'         , 'label' => _wpl__( "Age"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User' age. Note that WPL do not calculate this field. We return it as it was provided"                                  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wpl__( "Birth day"        , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The day in the month in which the person was born. Not to confuse it with 'Birth date'"                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wpl__( "Birth month"      , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The month in which the person was born"                                                                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wpl__( "Birth year"       , 'wordpress-pixelpin-login'), 'description' => _wpl__( "The year in which the person was born"                                                                                  , 'wordpress-pixelpin-login') ),
		array( 'field' => 'email'       , 'label' => _wpl__( "Email"            , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's email address.", 'wordpress-pixelpin-login') ),
		array( 'field' => 'phone'       , 'label' => _wpl__( "Phone"            , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's phone number"                                                                                                    , 'wordpress-pixelpin-login') ),
		array( 'field' => 'address'     , 'label' => _wpl__( "Address"          , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's address"                                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'country'     , 'label' => _wpl__( "Country"          , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's country"                                                                                                         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'region'      , 'label' => _wpl__( "Region"           , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's state or region"                                                                                                 , 'wordpress-pixelpin-login') ),
		array( 'field' => 'city'        , 'label' => _wpl__( "City"             , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's city"                                                                                                            , 'wordpress-pixelpin-login') ),
		array( 'field' => 'zip'         , 'label' => _wpl__( "Zip"              , 'wordpress-pixelpin-login'), 'description' => _wpl__( "User's zipcode"                                                                                                         , 'wordpress-pixelpin-login') ),
	);

	$user_data = get_userdata( $user_id );

	add_thickbox();

	$actions = array(
		'edit_details'  => '<a class="button button-secondary thickbox" href="' . admin_url( 'user-edit.php?user_id=' . $user_id . '&TB_iframe=true&width=1150&height=550' ) . '">' . _wpl__( 'Edit user details', 'wordpress-pixelpin-login' ) . '</a>',
		'show_contacts'  => '<a class="button button-secondary" href="' . admin_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=contacts&uid=' . $user_id ) . '">' . _wpl__( 'Show user contacts list', 'wordpress-pixelpin-login' ) . '</a>',
	);

	// HOOKABLE:
	$actions = apply_filters( 'wpl_component_users_profiles_alter_actions_list', $actions, $user_id );
?>
<style>
	table td, table th { border: 1px solid #DDDDDD; }
	table th label { font-weight: bold; }
	.form-table th { width:120px; text-align:right; }
	p.description { font-size: 11px ! important; margin:0 ! important;}
</style>

<script>
	function confirmDeleteWPLUser()
	{
		return confirm( <?php echo json_encode( _wpl__("Are you sure you want to delete the user's pixelpin profiles and contacts?\n\nNote: The associated WordPress user won't be deleted.", 'wordpress-pixelpin-login') ) ?> );
	}
</script>

<div style="margin-top: 15px;padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
 	<h3 style="margin:0;"><?php echo sprintf( _wpl__("%s's pixelpin profiles", 'wordpress-pixelpin-login'), $user_data->display_name ) ?></h3>

	<p style="float: <?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>;margin-top:-23px">
		<?php
			echo implode( ' ', $actions );
		?>
	</p>
</div>

<div style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
	<table class="wp-list-table widefat">
		<tr><th width="200"><label><?php _wpl_e("Wordpress User ID", 'wordpress-pixelpin-login'); ?></label></th><td><?php echo $user_data->ID; ?></td></tr>
		<tr><th width="200"><label><?php _wpl_e("Username", 'wordpress-pixelpin-login'); ?></label></th><td><?php echo $user_data->user_login; ?></td></tr>
		<tr><th><label><?php _wpl_e("Display name", 'wordpress-pixelpin-login'); ?></label></th><td><?php echo $user_data->display_name; ?></td></tr>
		<tr><th><label><?php _wpl_e("E-mail", 'wordpress-pixelpin-login'); ?></label></th><td><a href="mailto:<?php echo $user_data->user_email; ?>" target="_blank"><?php echo $user_data->user_email; ?></a></td></tr>
		<tr><th><label><?php _wpl_e("Website", 'wordpress-pixelpin-login'); ?></label></th><td><a href="<?php echo $user_data->user_url; ?>" target="_blank"><?php echo $user_data->user_url; ?></a></td></tr>
		<tr><th><label><?php _wpl_e("Registered", 'wordpress-pixelpin-login'); ?></label></th><td><?php echo $user_data->user_registered; ?></td></tr>
		</tr>
	 </table>
</div>

<?php
	foreach( $linked_accounts AS $link )
	{
?>
<div style="margin-top:15px;padding: 5px 20px 20px; border: 1px solid #ddd; background-color: #fff;">

<h4><img src="<?php echo $assets_base_url . strtolower( $link->provider ) . '.png' ?>" style="vertical-align:top;width:16px;height:16px;" /> <?php _wpl_e("User profile", 'wordpress-pixelpin-login'); ?> <small><?php echo sprintf( _wpl__( "as provided by %s", 'wordpress-pixelpin-login'), $link->provider ); ?> </small></h4>

<table class="wp-list-table widefat">
	<?php
		$profile_fields = (array) $link;

		foreach( $ha_profile_fields as $item )
		{
			$item['field'] = strtolower( $item['field'] );
		?>
			<tr>
				<th width="200">
					<label><?php echo $item['label']; ?></label>
				</th>
				<td>
					<?php
						if( isset( $profile_fields[ $item['field'] ] ) && $profile_fields[ $item['field'] ] )
						{
							$field_value = $profile_fields[ $item['field'] ];

							if( in_array( $item['field'], array( 'profileurl', 'websiteurl', 'email' ) ) )
							{
								?>
									<a href="<?php if( $item['field'] == 'email' ) echo 'mailto:'; echo $field_value; ?>" target="_blank"><?php echo $field_value; ?></a>
								<?php
							}
							elseif( $item['field'] == 'photourl' )
							{
								?>
									<a href="<?php echo $field_value; ?>" target="_blank"><img width="36" height="36" align="left" src="<?php echo $field_value; ?>" style="margin-right: 5px;" > <?php echo $field_value; ?></a>
								<?php
							}
							else
							{
								echo $field_value;
							}

							?>
								<p class="description">
									<?php echo $item['description']; ?>.
								</p>
							<?php
						}
					?>
				</td>
			</tr>
		<?php
		}
	?>
</table>
</div>
<?php
	}

	// HOOKABLE:
	do_action( "wpl_component_users_profiles_end" );
}

// --------------------------------------------------------------------
