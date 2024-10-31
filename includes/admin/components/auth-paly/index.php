<?php
/*!
* WordPress PixelPin Login
*
* 2017 PixelPin and contributors https://github.com/PixelPinPlugins/WordPress-PixelPin-Login
* 
* Original Authors of WSL
* -----------------------
* http://miled.github.io/wordpress-social-login/ | https://github.com/miled/wordpress-social-login
*  (c) 2011-2015 Mohamed Mrassi and contributors | http://wordpress.org/plugins/wordpress-social-login/
*/

/**
* Authentication Playground
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*!
	Important

	Direct access to providers apis is newly introduced into WPL and we are still experimenting, so they may change in future releases.
*/

// --------------------------------------------------------------------

function wpl_component_authtest()
{
	// HOOKABLE:
	do_action( "wpl_component_authtest_start" );

	$adapter      = null;
	$provider_id  = isset( $_REQUEST["provider"] ) ? $_REQUEST["provider"] : null;
	$user_profile = null;

	$assets_base_url = WORDPRESS_PIXELPIN_LOGIN_PLUGIN_URL . 'assets/img/';

	if( ! class_exists( 'Hybrid_Auth', false ) )
	{
		require_once WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . "hybridauth/Hybrid/Auth.php";
	}

	try
	{
		$provider = Hybrid_Auth::getAdapter( $provider_id );

		// make as few call as possible
		if( ! ( isset( $_SESSION['wpl::userprofile'] ) && $_SESSION['wpl::userprofile'] && $user_profile = json_decode( $_SESSION['wpl::userprofile'] ) ) )
		{
			$user_profile = $provider->getUserProfile();

			$_SESSION['wpl::userprofile'] = json_encode( $user_profile );
		}

		$adapter = $provider->adapter;
	}
	catch( Exception $e )
	{
	}

	$ha_profile_fields = array(
		array( 'field' => 'identifier'  , 'label' => _wpl__( "Provider user ID" , 'wordpress-pixelpin-login') ),
		array( 'field' => 'profileURL'  , 'label' => _wpl__( "Profile URL"      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'webSiteURL'  , 'label' => _wpl__( "Website URL"      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'photoURL'    , 'label' => _wpl__( "Photo URL"        , 'wordpress-pixelpin-login') ),
		array( 'field' => 'displayName' , 'label' => _wpl__( "Display name"     , 'wordpress-pixelpin-login') ),
		array( 'field' => 'description' , 'label' => _wpl__( "Description"      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'firstName'   , 'label' => _wpl__( "First name"       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'lastName'    , 'label' => _wpl__( "Last name"        , 'wordpress-pixelpin-login') ),
		array( 'field' => 'gender'      , 'label' => _wpl__( "Gender"           , 'wordpress-pixelpin-login') ),
		array( 'field' => 'language'    , 'label' => _wpl__( "Language"         , 'wordpress-pixelpin-login') ),
		array( 'field' => 'age'         , 'label' => _wpl__( "Age"              , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthDay'    , 'label' => _wpl__( "Birth day"        , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthMonth'  , 'label' => _wpl__( "Birth month"      , 'wordpress-pixelpin-login') ),
		array( 'field' => 'birthYear'   , 'label' => _wpl__( "Birth year"       , 'wordpress-pixelpin-login') ),
		array( 'field' => 'email'       , 'label' => _wpl__( "Email"            , 'wordpress-pixelpin-login') ),
		array( 'field' => 'phone'       , 'label' => _wpl__( "Phone"            , 'wordpress-pixelpin-login') ),
		array( 'field' => 'address'     , 'label' => _wpl__( "Address"          , 'wordpress-pixelpin-login') ),
		array( 'field' => 'country'     , 'label' => _wpl__( "Country"          , 'wordpress-pixelpin-login') ),
		array( 'field' => 'region'      , 'label' => _wpl__( "Region"           , 'wordpress-pixelpin-login') ),
		array( 'field' => 'city'        , 'label' => _wpl__( "City"             , 'wordpress-pixelpin-login') ),
		array( 'field' => 'zip'         , 'label' => _wpl__( "Zip"              , 'wordpress-pixelpin-login') ),
	);
?>
<style>
	.widefat td, .widefat th { border: 1px solid #DDDDDD; }
	.widefat th label { font-weight: bold; }

	.wp-pixelpin-login-provider-list { padding: 10px; }
	.wp-pixelpin-login-provider-list a {text-decoration: none; }
	.wp-pixelpin-login-provider-list img{ border: 0 none; }
</style>

<div class="metabox-holder columns-2" id="post-body">
	<table width="100%">
		<tr valign="top">
			<td>
				<?php if( ! $adapter ): ?>
					<div style="padding: 15px; margin-bottom: 8px; border: 1px solid #ddd; background-color: #fff;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
						<p><?php _wpl_e("Connect with PixelPin to get started", 'wordpress-pixelpin-login') ?>.</p>
					</div>
				<?php else: ?>
					<div class="stuffbox">
						<h3>
							<label><?php _wpl_e("Connected adapter specs", 'wordpress-pixelpin-login') ?></label>
						</h3>
						<div class="inside">
							<table class="wp-list-table widefat">
								<tr>
									<th width="200"><label><?php _wpl_e("Provider", 'wordpress-pixelpin-login') ?></label></th>
									<td><?php echo $adapter->providerId; ?></td>
								</tr>

								<?php if( isset( $adapter->openidIdentifier ) ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("OpenID Identifier", 'wordpress-pixelpin-login') ?></label></th>
										<td><?php echo $adapter->openidIdentifier; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->scope ) ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Scope", 'wordpress-pixelpin-login') ?></label></th>
										<td><?php echo $adapter->scope; ?></td>
									</tr>
								<?php endif; ?>

								<?php if( isset( $adapter->config['keys'] ) ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Application keys", 'wordpress-pixelpin-login') ?></label></th>
										<td><div style="max-width:650px"><?php echo json_encode( $adapter->config['keys'] ); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token") ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Access token", 'wordpress-pixelpin-login') ?></label></th>
										<td><div style="max-width:650px"><?php echo $adapter->token("access_token"); ?></div></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("access_token_secret") ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Access token secret", 'wordpress-pixelpin-login') ?></label></th>
										<td><?php echo $adapter->token("access_token_secret"); ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_in") ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Access token expires in", 'wordpress-pixelpin-login') ?></label></th>
										<td><?php echo (int) $adapter->token("expires_at") - time(); ?> <?php _wpl_e("second(s)", 'wordpress-pixelpin-login') ?></td>
									</tr>
								<?php endif; ?>

								<?php if( $adapter->token("expires_at") ): ?>
									<tr>
										<th width="200"><label><?php _wpl_e("Access token expires at", 'wordpress-pixelpin-login') ?></label></th>
										<td><?php echo date( DATE_W3C, $adapter->token("expires_at" ) ); ?></td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>

					<?php
						$console = false;

						if( ! isset( $adapter->openidIdentifier ) ):
					?>
						<div class="stuffbox">
							<h3>
								<label><?php _wpl_e("Connected adapter console", 'wordpress-pixelpin-login') ?></label>
							</h3>
							<div class="inside">
								<?php
									$path   = isset( $adapter->api->api_base_url ) ? $adapter->api->api_base_url : '';
									$path   = isset( $_REQUEST['console-path']   ) ? $_REQUEST['console-path']   : $path;
									$method = isset( $_REQUEST['console-method'] ) ? $_REQUEST['console-method'] : '';
									$query  = isset( $_REQUEST['console-query']  ) ? $_REQUEST['console-query']  : '';

									$response = '';

									if( $path && in_array( $method, array( 'GET', 'POST' ) ) )
									{
										$console = true;

										try
										{
											if( $method == 'GET' )
											{
												$response = $adapter->api->get( $path . ( $query ? '?' . $query : '' ) );
											}
											else
											{
												$response = $adapter->api->get( $path, $query );
											}

											$response = $response ? $response : Hybrid_Error::getApiError();
										}
										catch( Exception $e )
										{
											$response = "ERROR: " . $e->getMessage();
										}
									}
								?>
								<form action="" method="post"/>
									<table class="wp-list-table widefat">
										<tr>
											<th width="200"><label><?php _wpl_e("Path", 'wordpress-pixelpin-login') ?></label></th>
											<td><input type="text" style="width:96%" name="console-path" value="<?php echo htmlentities( $path ); ?>"><a href="https://apigee.com/providers" target="_blank"><img src="<?php echo $assets_base_url . 'question.png' ?>" style="vertical-align: text-top;" /></a></td>
										</tr>
										<tr>
											<th width="200"><label><?php _wpl_e("Method", 'wordpress-pixelpin-login') ?></label></th>
											<td><select style="width:100px" name="console-method"><option value="GET" <?php if( $method == 'GET' ) echo 'selected'; ?>>GET</option><!-- <option value="POST" <?php if( $method == 'POST' ) echo 'selected'; ?>>POST</option>--></select></td>
										</tr>
										<tr>
											<th width="200"><label><?php _wpl_e("Query", 'wordpress-pixelpin-login') ?></label></th>
											<td><textarea style="width:100%;height:60px;margin-top:6px;" name="console-query"><?php echo htmlentities( $query ); ?></textarea></td>
										</tr>
									</table>

									<br />

									<input type="submit" value="<?php _wpl_e("Submit", 'wordpress-pixelpin-login') ?>" class="button">
								</form>
							</div>
						</div>

						<?php if( $console ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _wpl_e("API Response", 'wordpress-pixelpin-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $response, true ) ); ?></textarea>
								</div>
							</div>
						<?php if( 0 ): ?>
							<div class="stuffbox">
								<h3>
									<label><?php _wpl_e("Code PHP", 'wordpress-pixelpin-login') ?></label>
								</h3>
								<div class="inside">
<textarea rows="25" cols="70" wrap="off" style="width:100%;height:210px;margin-bottom:15px;font-family: monospace;font-size: 12px;"
>include_once WORDPRESS_PIXELPIN_LOGIN_ABS_PATH . 'hybridauth/Hybrid/Auth.php';

/*!
	Important

	Direct access to providers apis is newly introduced into WPL and we are still experimenting, so they may change in future releases.
*/

try
{
    $<?php echo strtolower( $adapter->providerId ); ?> = Hybrid_Auth::getAdapter( '<?php echo htmlentities( $provider_id ); ?>' );

<?php if( $method == 'GET' ): ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->get( '<?php echo htmlentities( $path . ( $query ? '?' . $query : '' ) ); ?>' );
<?php else: ?>
    $response = $<?php echo strtolower( $adapter->providerId ); ?>->api()->post( '<?php echo htmlentities( $path ); ?>', (array) $query );
<?php endif; ?>
}
catch( Exception $e )
{
    echo "Ooophs, we got an error: " . $e->getMessage();
}</textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _wpl_e("Connected adapter debug", 'wordpress-pixelpin-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:400px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $adapter, true ) ); ?></textarea>
								</div>
							</div>
							<div class="stuffbox">
								<h3>
									<label><?php _wpl_e("PHP Session", 'wordpress-pixelpin-login') ?></label>
								</h3>
								<div class="inside">
									<textarea rows="25" cols="70" wrap="off" style="width:100%;height:350px;margin-bottom:15px;font-family: monospace;font-size: 12px;"><?php echo htmlentities( print_r( $_SESSION, true ) ); ?></textarea>
								</div>
							</div>
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>

					<?php if( ! $console ): ?>
						<div class="stuffbox">
							<h3>
								<label><?php _wpl_e("Connected user pixelpin profile", 'wordpress-pixelpin-login') ?></label>
							</h3>
							<div class="inside">
								<table class="wp-list-table widefat">
									<?php
										$user_profile = (array) $user_profile;

										foreach( $ha_profile_fields as $item )
										{
											$item['field'] = $item['field'];
										?>
											<tr>
												<th width="200">
													<label><?php echo $item['label']; ?></label>
												</th>
												<td>
													<?php
														if( isset( $user_profile[ $item['field'] ] ) && $user_profile[ $item['field'] ] )
														{
															$field_value = $user_profile[ $item['field'] ];

															if( in_array( strtolower( $item['field'] ), array( 'profileurl', 'websiteurl', 'email' ) ) )
															{
																?>
																	<a href="<?php if( $item['field'] == 'email' ) echo 'mailto:'; echo $field_value; ?>" target="_blank"><?php echo $field_value; ?></a>
																<?php
															}
															elseif( strtolower( $item['field'] ) == 'photourl' )
															{
																?>
																	<a href="<?php echo $field_value; ?>" target="_blank"><img width="36" height="36" align="left" src="<?php echo $field_value; ?>" style="margin-right: 5px;" > <?php echo $field_value; ?></a>
																<?php
															}
															else
															{
																echo $field_value;
															}
														}
													?>
												</td>
											</tr>
										<?php
										}
									?>
								</table>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td width="10"></td>
			<td width="400">
				<div class="postbox">
					<div class="inside">
						<h3><?php _wpl_e("Authentication Playground", 'wordpress-pixelpin-login') ?></h3>

						<div style="padding:0 20px;">
							<p>
								<?php _wpl_e('Authentication Playground will let you authenticate with the enabled pixelpin networks without creating any new user account', 'wordpress-pixelpin-login') ?>.
							</p>
							<p>
								<?php _wpl_e('This tool will also give you a direct access to pixelpin networks apis via a lightweight console', 'wordpress-pixelpin-login') ?>.
							</p>
						</div>
					</div>
				</div>

				</style>
				<div class="postbox">
					<div class="inside">
						<div style="padding:0 20px;">
							<p>
								<?php _wpl_e("Connect with PixelPin", 'wordpress-pixelpin-login') ?>:
							</p>

							<div style="width: 380px; padding: 10px; border: 1px solid #ddd; background-color: #fff;">
								<?php do_action( 'wordpress_pixelpin_login', array( 'mode' => 'test', 'caption' => '' ) ); ?>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
<?php
	// HOOKABLE:
	do_action( "wpl_component_authtest_end" );
}

wpl_component_authtest();

// --------------------------------------------------------------------
