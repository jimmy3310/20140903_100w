<?php
	session_start();

	include_once("/var/www/conf/config.php");
	include_once("/var/www/class/LDB2.class.php");
	include_once '/var/www/class/smarty.class.php';
	include_once '/var/www/class/cache.class.php';
	include_once '/var/www/func/public.func.php';
	include_once '/var/www/class/svcSoapClient.php';
	include_once '/var/www/class/sid_encrypt.php';

	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	
	
	
	if (strtolower($_GET['loginFormCaptcha']) == strtolower($_SESSION['captcha']['code'] )) {
		
					$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("SELECT count(*) num from MEMBER_DATA where PERSON_ID='%s' " , $db_eimi->esc($_GET['customerID']));
					$db_result = $db_eimi->getRow($sql);
		
	
	


				/*抓卡友是否存在*/
				$memberData2= new svcClient();
                $memberData2->mti='0200';
                $memberData2->procCode='9557';
                $memberData2->personId=$_GET['customerID'];
                $memberData2->inqType='P';
                $memberData2->traceNo=$memberData2->getTraceNo();
                $result= $memberData2->getCardInfo();
	
	
				$logger->debug($result); 
	
			if ($db_result['num']>=1) {
			
				echo 2;
			
			} else if ($result['respCode']=='00' && count($result['cardInfoArea']) >= 1) {
			
				$_SESSION["updateMemberFlag"]=1;
					$logger->debug($svcErrorCode[$result['respCode']]); 
					$logger->debug('SVC已存在'); 
					echo 3;
					
			} else {
				$_SESSION["uid"]=$_GET['customerID'];
				$_SESSION["personalType"]=$_GET['personalType'];
				$logger->debug($svcErrorCode[$result['respCode']]); 
				$logger->debug('官網不存在'); 
				echo 1;
				
			
			}
		
	} else {

		// echo '辦識碼錯誤';
		echo 4;
	}


?>