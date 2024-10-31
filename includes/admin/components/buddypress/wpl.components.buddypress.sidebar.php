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

function wpl_component_buddypress_sidebar()
{
	$sections = array(
		'what_is_this' => 'wpl_component_buddypress_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wpl_component_buddypress_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_buddypress_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wpl_component_buddypress_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wpl_component_buddypress_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("BuddyPress integration", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wpl_e( 'WPL can be now fully integrated with your <a href="https://buddypress.org" target="_blank">BuddyPress</a> installation. When enabled, user avatars display should work right out of the box with most WordPress themes and your BuddyPress installation', 'wordpress-pixelpin-login') ?>.
			</p> 

			<p>
				<?php _wpl_e( 'WPL also comes with BuddyPress xProfiles mappings. When this feature is enabled, WPL will try to automatically fill in Buddypress users profiles from their pixelpin networks profiles', 'wordpress-pixelpin-login') ?>.
			</p> 
		</div>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
