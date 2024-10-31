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
* WPL Tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_help_sidebar()
{
	// HOOKABLE: 
	do_action( "wpl_component_help_sidebar_start" );
?>
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("About WordPress PixelPin Login", 'wordpress-pixelpin-login') ?> <?php echo wpl_get_version(); ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wpl_e('WordPress PixelPin Login is a free and open source plugin made by the community, for the community', 'wordpress-pixelpin-login') ?>.
			</p> 
			<p>
				<?php _wpl_e('For more information about WordPress PixelPin Login, refer to our online user guide', 'wordpress-pixelpin-login') ?>.
			</p> 
		</div> 
	</div> 
</div> 
<div class="postbox">
	<div class="inside">
		<h3><?php _wpl_e("Thanks", 'wordpress-pixelpin-login') ?></h3>

		<div style="padding:0 20px;">
			<p>
				<?php _wpl_e('Big thanks to everyone who have contributed to WordPress PixelPin Login by submitting Patches, Ideas, Reviews and by Helping in the support forum', 'wordpress-pixelpin-login') ?>.
			</p> 
		</div> 
	</div> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wpl_component_help_sidebar_end" );
}

// --------------------------------------------------------------------	
