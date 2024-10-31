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

function wpl_component_contacts_settings_setup()
{
	$sections = array(
	);

	$sections = apply_filters( 'wpl_component_buddypress_setup_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_contacts_settings_setup_sections', $action );
	}	
?>
<div>
	<?php
		// HOOKABLE: 
		do_action( 'wpl_component_contacts_settings_setup_sections' );
	?>

	<br />

	<div style="margin-left:5px;margin-top:-20px;"> 
		<input type="submit" class="button-primary" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" /> 
	</div>
</div>
<?php
}


