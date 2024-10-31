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

function wpl_component_buddypress()
{
	// HOOKABLE: 
	do_action( "wpl_component_buddypress_start" ); 

	include "wpl.components.buddypress.setup.php";
	include "wpl.components.buddypress.sidebar.php";

	if( ! function_exists( 'bp_has_profile' ) ){
		include "wpl.components.buddypress.notfound.php";

		return wpl_component_buddypress_notfound();
	}
?>
<form method="post" id="wpl_setup_form" action="options.php"> 
	<?php settings_fields( 'wpl-settings-group-buddypress' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					<?php
						wpl_component_buddypress_setup();
					?> 
				</td>
				<td width="10"></td>
				<td width="400">
					<?php 
						wpl_component_buddypress_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wpl_component_buddypress_end" );
}

wpl_component_buddypress();

// --------------------------------------------------------------------	
