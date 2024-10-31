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

function wpl_component_networks()
{
	// HOOKABLE: 
	do_action( "wpl_component_networks_start" );

	include "wpl.components.networks.setup.php";
	include "wpl.components.networks.sidebar.php"; 

	wpl_admin_welcome_panel();
?>

<form method="post" id="wpl_setup_form" action="options.php"> 
	<?php settings_fields( 'wpl-settings-group' ); ?>

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td> 
					<div id="post-body-content">
						<?php
							wpl_component_networks_setup();
						?>
						<a name="wplsettings"></a> 
					</div>
				</td>
				<td width="10"></td>
				<td width="400">
					<?php
						wpl_component_networks_sidebar();
					?>
				</td>
			</tr>
		</table> 
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wpl_component_networks_end" );
}

wpl_component_networks();

// --------------------------------------------------------------------	
