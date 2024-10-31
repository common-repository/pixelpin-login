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

function wpl_component_tools_sidebar()
{
	$sections = array(
		'what_is_this' => 'wpl_component_tools_sidebar_what_is_this',
	);

	$sections = apply_filters( 'wpl_component_tools_sidebar_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_tools_sidebar_sections', $action );
	}

	// HOOKABLE: 
	do_action( 'wpl_component_tools_sidebar_sections' );
}

// --------------------------------------------------------------------	

function wpl_component_tools_sidebar_what_is_this()
{
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("WordPress PixelPin Login Tools", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wpl_e( 'Here you can found a set of tools to help you find and hopefully fix any issue you may encounter', 'wordpress-pixelpin-login') ?>.
			</p>
			<p>
				<?php _wpl_e( 'You can also delete all Wordpress PixelPin Login tables and stored options from the <b>Uninstall</b> section down below', 'wordpress-pixelpin-login') ?>.
			</p>
		</div> 
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
