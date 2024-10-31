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
* WPL tools
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

// --------------------------------------------------------------------

function wpl_component_tools()
{
	// HOOKABLE:
	do_action( "wpl_component_tools_start" );

	include "wpl.components.tools.actions.php"; 
	include "wpl.components.tools.sidebar.php";

	$action = isset( $_REQUEST['do'] ) ? $_REQUEST['do'] : null ;

	if( in_array( $action, array( 'diagnostics', 'sysinfo', 'uninstall' , 'repair' ) ) )
	{
		if( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'] ) )
		{
			include "wpl.components.tools.actions.job.php";

			do_action( 'wpl_component_tools_do_' . $action );
		}
		else
		{
			?>
				<div style="margin: 4px 0 20px;" class="fade error wpl-error-db-tables">
					<p>
						<?php _wpl_e('The URL nonce is not valid', 'wordpress-pixelpin-login') ?>! 
					</p>
				</div>	
			<?php
		}
	}
	else
	{
		?> 
			<div class="metabox-holder columns-2" id="post-body">
				<table width="100%"> 
					<tr valign="top">
						<td> 
							<?php
								wpl_component_tools_sections();
							?>
						</td>
						<td width="10"></td>
						<td width="400">
							<?php 
								wpl_component_tools_sidebar();
							?>
						</td>
					</tr>
				</table>
			</div>
		<?php
	}

	// HOOKABLE: 
	do_action( "wpl_component_tools_end" );
}

// --------------------------------------------------------------------	

wpl_component_tools();
