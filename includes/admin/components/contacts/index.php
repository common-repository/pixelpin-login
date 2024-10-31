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
* Wannabe Contact Manager module
*/

// --------------------------------------------------------------------

function wpl_component_contacts()
{
	// HOOKABLE: 
	do_action( 'wpl_component_contacts_start' );

	include 'wpl.components.contacts.list.php';
	include 'wpl.components.contacts.settings.setup.php';
	include 'wpl.components.contacts.settings.sidebar.php';

	if( isset( $_REQUEST['uid'] ) && $_REQUEST['uid'] )
	{
		$user_id = (int) $_REQUEST['uid'];

		wpl_component_contacts_list( $user_id );
	}
	else
	{
?>
<form method="post" id="wpl_setup_form" action="options.php"> 
	<?php settings_fields( 'wpl-settings-group-contacts-import' ); ?> 

	<div class="metabox-holder columns-2" id="post-body">
		<table width="100%"> 
			<tr valign="top">
				<td>
					<?php
						wpl_component_contacts_settings_setup();
					?> 
				</td>
				<td width="10"></td>
				<td width="400">
					<?php 
						wpl_component_contacts_settings_sidebar();
					?>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php 
	}

	// HOOKABLE: 
	do_action( 'wpl_component_contacts_end' );
}

wpl_component_contacts();

// --------------------------------------------------------------------	
