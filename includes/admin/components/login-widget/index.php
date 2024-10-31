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
* Widget Customization
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_loginwidget()
{
	// HOOKABLE: 
	do_action( "wpl_component_loginwidget_start" );

	include "wpl.components.loginwidget.setup.php";
	include "wpl.components.loginwidget.sidebar.php";
?>
<form method="post" id="wpl_setup_form" action="options.php"> 
	<?php settings_fields( 'wpl-settings-group-customize' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					<?php
						wpl_component_loginwidget_setup();
					?> 
				</td>
				<td width="10"></td>
				<td width="400">
					<?php 
						wpl_component_loginwidget_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php
	// HOOKABLE: 
	do_action( "wpl_component_loginwidget_end" );
}

wpl_component_loginwidget();

// --------------------------------------------------------------------	
