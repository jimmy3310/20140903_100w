<?php

session_start();

require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
 
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );
require_once( 'Facebook/GraphUser.php' );

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\GraphNodes\GraphUser;
 
$appid = '641483439300510'; // your AppID
$secret = '3dfa2057d45df7d5e23c8d623050a1e7'; // your secret
 
// Initialize app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication($appid ,$secret);
 
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper( 'http://www.1card.com.tw/mp/ff.php' );
 
try
{
  // In case it comes from a redirect login helper
  $session = $helper->getSessionFromRedirect();
}
catch( FacebookRequestException $ex )
{
  // When Facebook returns an error
  echo $ex;
}
catch( Exception $ex )
{
  // When validation fails or other local issues
  echo $ex;
}
 
// see if we have a session in $_Session[]
if( isset($_SESSION['token']))
{
    // We have a token, is it valid?
    $session = new FacebookSession($_SESSION['token']);
    try
    {
        $session->Validate($appid ,$secret);
    }
    catch( FacebookAuthorizationException $ex)
    {
        // Session is not valid any more, get a new one.
        $session ='';
    }
}
 
// see if we have a session
if ( isset( $session ) )
{  
    // set the PHP Session 'token' to the current session token
    $_SESSION['token'] = $session->getToken();
    // SessionInfo
    $info = $session->getSessionInfo(); 
    // getAppId
    echo "Appid: " . $info->getAppId() . "<br />";
    // session expire data
    $expireDate = $info->getExpiresAt()->format('Y-m-d H:i:s');
    echo 'Session expire time: ' . $expireDate . "<br />";
    // session token
    echo 'Session Token: ' . $session->getToken() . "<br />";

	
	echo "<br />";
	echo "<br />";
	
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  $graphObject = $response->getGraphObject()->asArray();
	
 //$access_token = $session->getToken();
 
  $request2 = new FacebookRequest( $session  , 'GET', '/me/friends' );
  $response2 = $request2->execute();
  $graphObject2 = $response2->getGraphObject()->asArray();	
	
			// $response = (new FacebookRequest($session, 'POST', '/me/feed', array('message' => 'testing'	)))->execute()->getGraphObject()->asArray();
			 
			
			// print_r( $response );

  
  // print profile data
  echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
	
 echo '<img src="http://graph.facebook.com/'.$graphObject['id'].'/picture"></a>';	
	

	$friendsList=array();
		for ($i=0;$i< count($graphObject2['data']);$i++) {
		
		
		

				foreach ($graphObject2['data'][$i] as $key => $value) {
				
					if ($key=='id'){
						array_push($friendsList,$value);
				
					}
				}
		
		
		}
	
	
	print_r($graphObject2['data']);
	
	
	
}
else
{
  // show login url
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}
?>
