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

function wpl_component_buddypress_notfound()
{
	// HOOKABLE:
	do_action( "wpl_component_buddypress_notfound_start" );
?> 
<style>
#wpl_div_warn { 
	padding: 10px;  
	border: 1px solid #ddd; 
	background-color: #fff; 
	
	width: 55%;
	margin: 0px auto;
	margin-top:30px;
}
</style>
<div id="wpl_div_warn">
	<h3 style="margin:0px;"><?php _wpl_e("BuddyPress plugin not found!", 'wordpress-pixelpin-login') ?></h3> 

	<hr />

	<p>
		<?php _wpl_e('<a href="https://buddypress.org/" target="_blank">BuddyPress</a> was not found on your website. The plugin is be either not installed or disabled', 'wordpress-pixelpin-login') ?> .
	</p>

	<p>
		<?php _wpl_e("If you believe you've found a problem with <b>WordPress PixelPin Login</b>, be sure to let us know so we can fix it", 'wordpress-pixelpin-login') ?>.
	</p>

	<hr />

	<div>
		<a class="button-secondary" href="https://github.com/PixelPinPlugins/WordPress-Social-Login/issues" target="_blank"><?php _wpl_e( "Report as bug", 'wordpress-pixelpin-login' ) ?></a>
		<a class="button-primary" href="options-general.php?page=wordpress-pixelpin-login&wplp=components" style="float:<?php if( is_rtl() ) echo 'left'; else echo 'right'; ?>"><?php _wpl_e( "Check enabled components", 'wordpress-pixelpin-login' ) ?></a>
	</div> 
</div>
<?php
	// HOOKABLE: 
	do_action( "wpl_component_buddypress_notfound_end" );
}

// --------------------------------------------------------------------	
