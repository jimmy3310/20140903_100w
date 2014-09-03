<?php

session_start();
include_once '/var/www/conf/config.php';
include_once '/var/www/class/smarty.class.php';
include_once '/var/www/class/cache.class.php';
include_once '/var/www/func/public.func.php';
include("/var/www/class/LDB2.class.php");
include("/var/www/class/sid_encrypt.php");


 
  
 
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
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
 
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
}  
  
require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("api");
			
			
			
	if (empty($_COOKIE['uid']) || $_COOKIE['smsFlag'] != 'Y' || $_COOKIE['emailFlag'] != 'Y' ) {
	
		header( 'Location: '. HOMEPAGE ) ;
		return true;
	
	}
	
	
				$test = new cSidEncrypt();
				$test->cSidEncrypt();
				$userid=$test->sid_decode($_COOKIE['uid']);
				
				
					$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("SELECT RECMD_ID FROM eimi.MEMBER_DATA where PERSON_ID='%s'" ,$userid);
		
					$db_result = $db_eimi->getRow($sql);

					$logger->debug($sql );
			$logger->debug($db_result);
		
		

$id	= $_GET['cd_id'];

$cache = new cache_page(CACHE_ROOT, 'recommend' , 1);
	
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
	
	
	$caption='得易卡百萬大富翁';
	$picture=urlencode('http://www.1card.com.tw/branding.png');
	$redirect_uri=urlencode('http://www.1card.com.tw/mp/index.php');
	$description='介紹你一個超級好康活動，快加入得易卡吧！不只馬上送你得易卡點數1000點，還可以再抽百萬點唷，不只這樣， 推薦你的朋友來還可以增加中獎機會，推薦越多中獎機會越高！PS, 記得寫我的推薦碼'.$db_result['RECMD_ID'];
	$title='快加入得易卡吧！不只馬上送你得易卡點數1000點，還可以再抽百萬點唷!';
	$facebookFeed='https://www.facebook.com/dialog/feed?app_id=641483439300510&display=popup&caption='.$caption.'&picture='.$picture.'&redirect_uri='.$redirect_uri.'&description='.$description.'&title='.$title;


	
	
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
	$smarty->assign('recommandCode', $db_result['RECMD_ID']);
	$smarty->assign('facebookLinkRecom',  $helper->getLoginUrl()  );
	$smarty->assign('facebookFeed',  $facebookFeed  );
	

	$html = $smarty->fetch('recommend.html');
	// $cache->cache_save($html);
	echo $html;
}




?>