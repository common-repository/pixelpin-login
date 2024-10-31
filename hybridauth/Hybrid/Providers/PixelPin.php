<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

/**
 * Hybrid_Providers_PixelPin provider adapter based on OAuth2 protocol
 * 
 * http://hybridauth.sourceforge.net/userguide/IDProvider_info_PixelPin.html
 */

define( 'WP_USE_THEMES', false );

/** Absolute path to the WordPress directory. */
/** Sets up WordPress vars and included files. */
/** Absolute path to the WordPress directory. */

$route = getcwd();

$remove = strstr($route, 'wp-content/plugins/');

$pluginName1 = str_replace("wp-content/plugins/", "", $remove);

$pluginName2 = str_replace("/hybridauth", "", $pluginName1);

$root = str_replace("wp-content/plugins/" . $pluginName2 . "/hybridauth", "", getcwd() . "/wp-load.php");

/** Sets up WordPress vars and included files. */
require_once($root);

class Hybrid_Providers_PixelPin extends Hybrid_Provider_Model_OAuth2
{ 
    /**
     * IDp wrappers initializer 
     */
	function initialize() 
	{
		parent::initialize();

		$this->api->api_base_url  = "https://login.pixelpin.io/connect/";
		$this->api->authorize_url = "https://login.pixelpin.io/connect/authorize";
		$this->api->token_url     = "https://login.pixelpin.io/connect/token"; 

		$this->api->sign_token_name = "access_token";
		
		$addressEnabled = get_option('wpl_settings_pixelpin_address_enabled');
		
		$phoneEnabled = get_option('wpl_settings_pixelpin_phone_enabled');

		if ($addressEnabled == '1' && $phoneEnabled == '1')
		{
			$scope = "openid email profile phone address";
		}
		else
		{
			if ($addressEnabled == '1')
			{
				$scope = "openid email profile address";
			}
			else
			{
				if ($phoneEnabled == '1')
				{
					$scope = "openid email profile phone";
				}
				else
				{
					$scope = "openid email profile";
				}
			}
			if ($phoneEnabled == '1')
			{
				$scope = "openid email profile phone";
			}
			else
			{
				if ($addressEnabled == '1')
				{
					$scope = "openid email profile address";
				}
				else
				{
					$scope = "openid email profile";
				}
			}
		}

		$this->scope = $scope;
		
	}

	public function request( $url, $params = array(), $type="GET", $http_headers = null )
	{
		if( $type == "GET" ){
		    $url = $url . ( strpos( $url, '?' ) ? '&' : '?' ) . http_build_query($params, '', '&');
		}

		$this->http_info = array();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL            , $url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1 );
		curl_setopt($ch, CURLOPT_TIMEOUT        , $this->api->curl_time_out );
		curl_setopt($ch, CURLOPT_USERAGENT      , $this->api->curl_useragent );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $this->api->curl_connect_time_out );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , $this->api->curl_ssl_verifypeer );
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , $this->api->curl_ssl_verifyhost );

	        if (is_array($http_headers)) {
        	    $header = array();
	            foreach($http_headers as $key => $parsed_urlvalue) {
        	        $header[] = "$key: $parsed_urlvalue";
            	    }

		    curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
        	}
		else
                {
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->api->curl_header );
		}

		if($this->api->curl_proxy){
		    curl_setopt( $ch, CURLOPT_PROXY        , $this->api->curl_proxy);
		}

		if( $type == "POST" ){
			curl_setopt($ch, CURLOPT_POST, 1); 
			if($params) curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($params) );
		}

		$response = curl_exec($ch);

		$this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ch));

		curl_close ($ch);

		//-
		Hybrid_Error::deleteApiError();

		if( $this->http_code != 200 )
		{
		    Hybrid_Error::setApiError( $this->http_code . ". $url :" . preg_replace('/\s+/', ' ', $response ) );
		}

		if( defined( 'WORDPRESS_PIXELPIN_LOGIN_DEBUG_API_CALLS' ) )
		{
		    do_action( 'wpl_log_provider_api_call', 'OAuth2', $url, $type, $params, $this->http_code, $this->http_info, $response );
		}
		//-

		return $response; 
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{

		$http_headers = array();
		$http_headers['Authorization'] = 'Bearer ' . $this->api->access_token;
		$parameters = array();
		$parameters[$this->api->sign_token_name] = $this->api->access_token;
		
		$response = null;
		$response = $this->request( "https://login.pixelpin.io/connect/userinfo", $parameters, "POST", $http_headers );
		if( $response && $this->api->decode_json ){
			$response = json_decode( $response ); 
		}

		

		if ( ! isset( $response->sub ) ){
			throw new Exception( "User profile request failed! {$this->providerId} didn't return a subject.", 6 );
		}

        $this->user->profile->identifier    	= $response->sub;

		$firstName = (isset($response->given_name) ? $response->given_name : false);
		if($firstName === false)
		{
			$this->user->profile->firstName 		= '';
		}
		else
		{
			$this->user->profile->firstName     	= $response->given_name;
		}

		$lastName = (isset($response->family_name) ? $response->family_name : false);
		if($lastName === false)
		{
			$this->user->profile->lastName      	= '';
		}
		else
		{
			$this->user->profile->lastName      	= $response->family_name;
		}

		$nickname = (isset($response->nickname) ? $response->nickname : false);
		if($nickname === false)
		{
			$this->user->profile->nickname		    = '';
		}
		else
		{
			$this->user->profile->nickname			= $response->nickname;
		}

		$gender = (isset($response->gender) ? $response->gender : false);
		if ($gender === false){
			$this->user->profile->gender 		= '';
		}
		else
		{
			$this->user->profile->gender 		= $response->gender;
		}

		$birthdate = (isset($response->birthdate) ? $response->birthdate : false);
		if ($birthdate === false)
		{
			$this->user->profile->birthdate 	= '';	
		}
		else
		{
			$this->user->profile->birthdate		= $response->birthdate;

		}

		$phone_number = (isset($response->phone_number) ? $response->phone_number : false);
		if ($phone_number === false){
			$this->user->profile->phoneNumber 	= '';
		}
		else
		{
			$this->user->profile->phoneNumber	= $phone_number;
		}

		$displayName = (isset($response->displayName) ? $response->displayName : false);
		if($displayName	=== false){
			$this->user->profile->displayName	    = '';
		}
		else
		{
			$this->user->profile->displayName   	= $response->given_name;
		}
		

		$email = (isset($response->email) ? $response->email : false);
		if($email === false){
			$this->user->profile->email         = '';
		}
		else
		{
			$this->user->profile->email         	= $response->email;
		}

		$emailVerified	 = (isset($response->emailVerified) ? $response->emailVerified : false);
		if($emailVerified === false){
			$this->user->profile->emailVerified	= '';
		}
		else
		{
	    	$this->user->profile->emailVerified 	= $response->email;
	    }

	    $address = (isset($response->address) ? $response->address : false);
	    if ($address === false){
	        $this->user->profile->address    	= '';
		    $this->user->profile->country       = '';
		    $this->user->profile->region        = '';
		    $this->user->profile->city          = '';
		    $this->user->profile->zip           = '';
	    }
	    else
	    {
		    $address = $response->address;

		    $decodeAddress = json_decode($address);

		    if($decodeAddress->{"street_address"} !== '')
		    {
		    	$streetAddress2 = $decodeAddress->{"street_address"};
		    	$streetAddress = (string)$streetAddress2;
		    	$this->user->profile->address = $streetAddress;
		    }
		    else
		    {
		    	$this->user->profile->address = '';
		    }

		    if($decodeAddress->{"locality"} !== '')
		    {
		    	$townCity2 = $decodeAddress->{"locality"};
		    	$townCity = (string)$townCity2;
		    	$this->user->profile->city = $townCity;

		    }
		    else
		    {
		    	$this->user->profile->city = '';
		    }

		    if($decodeAddress->{"region"} !== '')
		    {
		    	$region2 = $decodeAddress->{"region"};
		    	$region = (string)$region2;
		    	$this->user->profile->region = $region;
		    }
		    else
		    {
		    	$this->user->profile->region = '';
		    }

		    if($decodeAddress->{"postal_code"} !== '')
		    {
		    	$postalCode2 = $decodeAddress->{"postal_code"};
		    	$postalCode = (string)$postalCode2;
		    	$this->user->profile->zip = $postalCode;
		    }
		    else
		    {
		    	$this->user->profile->zip = '';
		    }

		    if($decodeAddress->{"country"} !== '')
		    {
				$country2 = $decodeAddress->{"country"};
				$country = (string)$country2;
				$this->user->profile->country = $country;
		    }
		    else
		    {
		    	$this->user->profile->country = '';
		    }
	    }

		return $this->user->profile;
	}
}
