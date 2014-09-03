<?php 

session_start();
include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';
include("/var/www/class/sid_encrypt.php");
	
require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("sms");
	


	
	

			
			
				

			
				$test = new cSidEncrypt();
				$test->cSidEncrypt();
				$userid=$test->sid_decode($_GET['code']);
				
			
			$currentDataTime=getCurrentDataTime();
			$code=randomPassword(6);
			$db_eimi = new database2($DSN_MYSQL);
			
			$sql2 = sprintf("SELECT ID CNT  FROM eimi.MEMBER_DATA where PERSON_ID='%s' ",$userid);
			$logger->debug($sql2);
	
			$db_result = $db_eimi->getRow($sql2);
			
			
			
			
			if (count($db_result['CNT'])>=1) {
	
					$userid=$db_result['CNT'];
			
		
					 $db_eimi->query("update MEMBER_DATA set EMAIL_VERIFY_FLAG='Y',EMAIL_VERIFIED_DATE=now() where ID= $userid ");
					 $db_eimi->query("update CONTACT_HISTORY set STATUS='S',UPDATE_DATE=now() , KIND='EMAIL' where MD_ID= $userid ORDER BY ID DESC LIMIT 1");
				
		
			
			
			}
			
	$smarty = new ezSmarty;

	$html = $smarty->fetch('confirm_ok.html');
	// $cache->cache_save($html);
	echo $html;
	

?>	