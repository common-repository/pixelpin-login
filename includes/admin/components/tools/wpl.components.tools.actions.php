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

function wpl_component_tools_sections()
{
	$sections = array(
		'auth_playground'    => 'wpl_component_tools_auth_playground'    ,
		'diagnostics'        => 'wpl_component_tools_diagnostics'        ,
		'system_information' => 'wpl_component_tools_system_information' ,
		'repair_wpl_tables'  => 'wpl_component_tools_repair_wpl_tables'  ,
		'debug_mode'         => 'wpl_component_tools_debug_mode'         ,
		'development_mode'   => 'wpl_component_tools_development_mode'   ,
		'uninstall'          => 'wpl_component_tools_uninstall'          ,
	);

	$sections = apply_filters( 'wpl_component_tools_alter_sections', $sections );

	foreach( $sections as $section => $action )
	{
		add_action( 'wpl_component_tools_sections', $action );
	}

	// HOOKABLE:
	do_action( 'wpl_component_tools_sections' );
}

// --------------------------------------------------------------------	

function wpl_component_tools_auth_playground()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Authentication Playground", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('Authentication Playground will let you authenticate with the enabled pixelpin networks without creating any new user account. This tool will also give you a direct access to pixelpin networks apis via a lightweight console', 'wordpress-pixelpin-login') ?>. 
		</p>

		<a class="button-primary"  href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=auth-paly'); ?>"><?php _wpl_e("Go to the authentication playground", 'wordpress-pixelpin-login') ?></a>  
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_diagnostics()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("WordPress PixelPin Login Diagnostics", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('This tool will check for the common issues and for the minimum system requirements', 'wordpress-pixelpin-login') ?>.
		</p>

		<a class="button-primary" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=tools&do=diagnostics'); ?>"><?php _wpl_e("Run WordPress PixelPin Login Diagnostics", 'wordpress-pixelpin-login') ?></a>  
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_system_information()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("System information", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('This tool will gather and display your website and server info. Please include these information when posting support requests, it will help me immensely to better understand any issues', 'wordpress-pixelpin-login') ?>. 
		</p>

		<a class="button-primary"  href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=tools&do=sysinfo'); ?>"><?php _wpl_e("Display your system information", 'wordpress-pixelpin-login') ?></a>  
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_repair_wpl_tables()
{
?>
<a name="repair-tables"></a>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Repair WPL tables", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('This will attempt recreate WPL databases tables if they do not exist and will also add any missing field', 'wordpress-pixelpin-login') ?>. 
		</p>

		<a class="button-primary" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=tools&do=repair'); ?>"><?php _wpl_e("Repair WordPress PixelPin Login databases tables", 'wordpress-pixelpin-login') ?></a>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_debug_mode()
{
	$wpl_settings_debug_mode_enabled = get_option( 'wpl_settings_debug_mode_enabled' ); 
?>
<a name="debug-mode"></a>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Debug mode", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside">
		<p>
			<?php _wpl_e('The <b>Debug mode</b> is an internal development tool built to track every action made by WPL during the authentication proces, which can be useful when debugging this plugin but note that it is highly technical and not documented', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('When Debug mode is enabled and set to <code>Log actions in a file</code>, WPL will attempt to generate its log files under <em>/wp-content/uploads/wordpress-pixelpin-login</em>', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('When Debug mode is enabled and set to <code>Log actions to database</code>, will create a new database table <code>wplwatchdog</code> and insert all actions names and arguments', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('For more information, refer to WPL documentation under Advanced Troubleshooting &gt; <a href="http://miled.github.io/wordpress-social-login/troubleshooting-advanced.html" target="_blank">Debug Mode</a>', 'wordpress-pixelpin-login') ?>.
		</p>

		<form method="post" id="wpl_setup_form" action="options.php">  
			<?php settings_fields( 'wpl-settings-group-debug' ); ?>

			<select name="wpl_settings_debug_mode_enabled">
				<option <?php if(    ! $wpl_settings_debug_mode_enabled ) echo "selected"; ?> value="0"><?php _wpl_e("Disabled", 'wordpress-pixelpin-login') ?></option> 
				<option <?php if( $wpl_settings_debug_mode_enabled == 1 ) echo "selected"; ?> value="1"><?php _wpl_e("Enabled &mdash; Log actions in a file", 'wordpress-pixelpin-login') ?></option>
				<option <?php if( $wpl_settings_debug_mode_enabled == 2 ) echo "selected"; ?> value="2"><?php _wpl_e("Enabled &mdash; Log actions to database", 'wordpress-pixelpin-login') ?></option>
			</select>

			<input type="submit" class="button-primary" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" />

			<?php if( $wpl_settings_debug_mode_enabled ): ?>
				<a class="button-secondary" href="options-general.php?page=wordpress-pixelpin-login&wplp=watchdog"><?php _wpl_e('View WPL logs', 'wordpress-pixelpin-login') ?></a>
			<?php endif; ?>
		</form>
	</div>
</div>	
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_development_mode()
{
	$wpl_settings_development_mode_enabled = get_option( 'wpl_settings_development_mode_enabled' ); 
?>
<a name="dev-mode"></a>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Development mode", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('When <b>Development Mode</b> is enabled, this plugin will display a debugging area on the footer of admin interfaces. <b>Development Mode</b> will also try generate and display a technical reports when something goes wrong. This report can help you figure out the root of the issues you may runs into', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('Please, do not enable <b>Development Mode</b>, unless you are a developer or you have basic PHP knowledge', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('For security reasons, <b>Development Mode</b> will auto switch to <b>Disabled</b> each time the plugin is <b>reactivated</b>', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('It\'s highly recommended to keep the <b>Development Mode</b> <b style="color:#da4f49">Disabled</b> on production as it would be a security risk otherwise', 'wordpress-pixelpin-login') ?>.
		</p>

		<p>
			<?php _wpl_e('For more information, refer to WPL documentation under Advanced Troubleshooting &gt; <a href="http://miled.github.io/wordpress-social-login/troubleshooting-advanced.html" target="_blank">Development Mode</a>', 'wordpress-pixelpin-login') ?>.
		</p>

		<form method="post" id="wpl_setup_form" action="options.php" <?php if( ! $wpl_settings_development_mode_enabled ) { ?>onsubmit="return confirm('Do you really want to enable Development Mode?\n\nPlease confirm that you have read and understood the abovementioned by clicking OK.');"<?php } ?>>  
			<?php settings_fields( 'wpl-settings-group-development' ); ?>

			<select name="wpl_settings_development_mode_enabled">
				<option <?php if( ! $wpl_settings_development_mode_enabled ) echo "selected"; ?> value="0"><?php _wpl_e("Disabled", 'wordpress-pixelpin-login') ?></option> 
				<option <?php if(   $wpl_settings_development_mode_enabled ) echo "selected"; ?> value="1"><?php _wpl_e("Enabled", 'wordpress-pixelpin-login') ?></option>
			</select>

			<input type="submit" class="button-danger" value="<?php _wpl_e("Save Settings", 'wordpress-pixelpin-login') ?>" />
		</form>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	

function wpl_component_tools_uninstall()
{
?>
<div class="stuffbox">
	<h3>
		<label><?php _wpl_e("Uninstall", 'wordpress-pixelpin-login') ?></label>
	</h3>
	<div class="inside"> 
		<p>
			<?php _wpl_e('This will permanently delete all Wordpress PixelPin Login tables and stored options from your WordPress database', 'wordpress-pixelpin-login') ?>. 
			<?php _wpl_e('Once you delete WPL database tables and stored options, there is NO going back. Please be certain', 'wordpress-pixelpin-login') ?>. 
		</p>

		<a class="button-danger" href="<?php echo wp_nonce_url( 'options-general.php?page=wordpress-pixelpin-login&wplp=tools&do=uninstall'); ?>" onClick="return confirm('Do you really want to Delete all Wordpress PixelPin Login tables and options?\n\nPlease confirm that you have read and understood the abovementioned by clicking OK.');"><?php _wpl_e("Delete all Wordpress PixelPin Login tables and options", 'wordpress-pixelpin-login') ?></a>
	</div>
</div>
<?php
}

// --------------------------------------------------------------------	
