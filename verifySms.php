<?php 

session_start();
include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';

require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("sms");
	


	
	

			
			
				
			@$smsCode = trim($_GET['smsCode']);
			@$userid =  $_COOKIE["uuid"];
			@$userPhone =  $_COOKIE["mobile"];
			
			$logger->debug($userPhone);
			$logger->debug($userid);
			$logger->debug($smsCode);
			
			
			$currentDataTime=getCurrentDataTime();
			
			$db_eimi = new database2($DSN_MYSQL);
			
			$sql2 = sprintf("SELECT a.CODE FROM eimi.CONTACT_HISTORY a where a.MD_ID = %d and a.KIND='SMS' and a.TYPE='%s' order by a.ID desc limit 1)",$userid,$userPhone);
			$logger->debug($sql2);
			$db_result = $db_eimi->getRow($sql2);
			$logger->debug($db_result['CODE']);
			
			if (trim($smsCode)==trim($db_result['CODE'])) {
					 $db_eimi->query("update MEMBER_DATA set SMS_VERIFY_FLAG='Y',SMS_VERIFIED_DATE=now() where ID= ".$userid ." " );
					 $db_eimi->query("update CONTACT_HISTORY set STATUS='S',UPDATE_DATE=now()  where KIND='SMS' and MD_ID= ".$userid." ORDER BY ID DESC LIMIT 1");
					echo 1;
					
			} else {
				
					echo 0;
					
			
			}
	

?>	