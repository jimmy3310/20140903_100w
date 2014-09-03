<?php
session_start();
include_once '/var/www/conf/config.php';
include_once '/var/www/class/smarty.class.php';
include_once '/var/www/class/cache.class.php';
include("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';
include("/var/www/class/sid_encrypt.php");

	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	
	
include("/var/www/html/mp/class/simple-php-captcha.php");





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


 
$appid = '641483439300510'; 
$secret = '3dfa2057d45df7d5e23c8d623050a1e7'; 
 

FacebookSession::setDefaultApplication($appid ,$secret);
 

$helper = new FacebookRedirectLoginHelper( 'http://www.1card.com.tw/mp/index.php' );
 
try {
  
  $session = $helper->getSessionFromRedirect();
}
catch( FacebookRequestException $ex )
{
 
  echo $ex;
}
catch( Exception $ex )
{
 
  echo $ex;
}
 

if( isset($_SESSION['token']))
{
    
    $session = new FacebookSession($_SESSION['token']);
    try
    {
        $session->Validate($appid ,$secret);
    }
    catch( FacebookAuthorizationException $ex)
    {
        
        $session ='';
    }
}

if ( isset( $session ) ) {  
   
    $_SESSION['token'] = $session->getToken();
   
    $info = $session->getSessionInfo(); 

    $expireDate = $info->getExpiresAt()->format('Y-m-d H:i:s');


  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  $graphObject = $response->getGraphObject()->asArray();
  
  $request2 = new FacebookRequest( $session  , 'GET', '/me/friends' );
  $response2 = $request2->execute();
  $graphObject2 = $response2->getGraphObject()->asArray();	
  
  
  
  	$friendsList=array();
	$friendsName=array();
		for ($i=0;$i< count($graphObject2['data']);$i++) {		
		

				foreach ($graphObject2['data'][$i] as $key => $value) {
				
					if ($key=='id'){
						array_push($friendsList,$value);
				
					} elseif ($key=='name') {
						array_push($friendsName,$value);
					
					}
				}
		
		
	}
  
  //$logger->debug($friendsList);
  
  /* FB_ID */
  $_SESSION["facebook"]=$graphObject['id'];

		$db_eimi = new database2($DSN_MYSQL);
					$sql = "SELECT count(*) CNT from MEMBER_DATA where FB_ID='".$graphObject['id']."' "  ;
					$db_result = $db_eimi->getRow($sql);
					$logger->debug($db_result);
	
			if ($db_result['CNT']>=1) {
			
			
			
					$sql = "
					SELECT a.ID, a.CUST_ID, a.SMS_VERIFY_FLAG, a.EMAIL_VERIFY_FLAG,MOBILE,EMAIL,PERSON_ID
					FROM eimi.MEMBER_DATA a where a.FB_ID= '".$graphObject['id']."' ";
					
					$db_result2 = $db_eimi->getRow($sql);
					
					
						$_SESSION["uid"]=$db_result2['PERSON_ID'];
						
						
						$test = new cSidEncrypt();
						$test->cSidEncrypt();
						//$test->sid_encode(strtoupper($_GET['customerID']));

						setcookie("uid", $test->sid_encode(strtoupper(trim($db_result2['PERSON_ID']))) );
						setcookie("smsFlag", $db_result2['SMS_VERIFY_FLAG'] );
						setcookie("emailFlag",  $db_result2['EMAIL_VERIFY_FLAG']);
						setcookie("mobile",  $db_result2['MOBILE']);
						setcookie("email",  $db_result2['EMAIL']);
						setcookie("uuid",  $db_result2['ID']);
						
						if ($_COOKIE['logout']==1) {
						
						setcookie("facebookReg", 0);
						
						} else {
						 setcookie("facebookReg", 0);
						}
				
			} else {
			
			 setcookie("facebookReg", 1);
			
			}
	
	
	
	
	
	
	
	} else {
	
			if ($_COOKIE['logout']==1) {
				setcookie("facebookReg", 0);
				setcookie("logout", 0);
			}
	}

$_SESSION['captcha'] = simple_php_captcha(array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZ23456789',
	'color' => '#666',
	'angle_min' => 0,
	'angle_max' => 7,
	'shadow' => true,
	'shadow_color' => '#fff',
	'shadow_offset_x' => -1,
	'min_font_size' => 30,
	'max_font_size' => 30,
	'shadow_offset_y' => 1
));

$id	= $_GET['cd_id'];








$cache = new cache_page(CACHE_ROOT, 'index' , CACHE_EXPIRY);
	
if ($_GET['cache'] == 'no') {
	$cache->cache_clean();
}
	
$cData = $cache->cache_get();

if ($cData != '') {
	echo $cData;
	exit;
} else {


	$memberId=getMemberNumber();
	$pointId=getPointNumber();
	
	
	
	$smarty = new ezSmarty;
	$smarty->assign('memberId1',  $memberId[0]);
	$smarty->assign('memberId2',  $memberId[1]);
	$smarty->assign('memberId3',  $memberId[2]);
	$smarty->assign('memberId4',  $memberId[3]);
	$smarty->assign('memberId5',  $memberId[4]);
	$smarty->assign('memberId6',  $memberId[5]);
	$smarty->assign('memberId7',  $memberId[6]);
	
	$smarty->assign('pointId1',  $pointId[0]);
	$smarty->assign('pointId2',  $pointId[1]);
	$smarty->assign('pointId3',  $pointId[2]);
	$smarty->assign('pointId4',  $pointId[3]);
	$smarty->assign('pointId5',  $pointId[4]);
	$smarty->assign('pointId6',  $pointId[5]);	
	$smarty->assign('pointId7',  $pointId[6]);
	$smarty->assign('pointId8',  $pointId[7]);
	$smarty->assign('pointId9',  $pointId[8]);
	$smarty->assign('captcha',  $_SESSION['captcha']['image_src']  );

	$smarty->assign('facebookLink',  $helper->getLoginUrl()  );
	
	$smarty->assign('fbFans',  getFbFan()  );
	
	if (isset( $session )){
		$fbFriendsResult=getFbFriend($friendsList,$friendsName);
		if (!empty($fbFriendsResult)) {
			$smarty->assign('fbFriends',  $fbFriendsResult  );			
		} else {
			$smarty->assign('fbFriends',  '<div class="photo"><img src="images/fb_07.gif" alt="" width="113" height="113" /><span>name</span></div>
        <div class="photo"><img src="images/fb_09.gif" alt="" width="113" height="113" /><span>name</span></div>'  );
		}
	
	} else {
	
	$smarty->assign('fbFriends',  '<div class="photo"><img src="images/fb_07.gif" alt="" width="113" height="113" /><span>name</span></div>
        <div class="photo"><img src="images/fb_09.gif" alt="" width="113" height="113" /><span>name</span></div>'  );	
	
	}		
	
	// $smarty->assign('r1',  'required');
	
	
	$html = $smarty->fetch('index.html');
	
	

	// $cache->cache_save($html);
	echo $html;
}

?>
