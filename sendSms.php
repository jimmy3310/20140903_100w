<?php

	
	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include("/var/www/class/sid_encrypt.php");
	include("/var/www/class/sms.php");
	include_once '/var/www/func/public.func.php';
	
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("sms");

	

	
	
	if (strtolower(@$_GET['reMobileCaptcha']) == strtolower(@$_SESSION['captcha']['code'] )) {
	
		/*	customerID 	reMobileCaptcha 	rePassYear 		rePassMon		rePassDay		*/
	
	
			$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("
					SELECT a.ID, a.CUST_ID, a.SMS_VERIFY_FLAG, a.EMAIL_VERIFY_FLAG,EMAIL,LOC_NAME,MOBILE
					FROM eimi.MEMBER_DATA a inner join MEMBER_FB_DATA b on a.ID = b.ID
					where a.PERSON_ID= '%s' 
					and year(BIRTHDAY)= '%s'  and  MONTH(BIRTHDAY)= '%s'  and  day(BIRTHDAY)= '%s'  ", $db_eimi->esc($_GET['customerID']),$db_eimi->esc($_GET['rePassYear']),$db_eimi->esc($_GET['rePassMon']),$db_eimi->esc($_GET['rePassDay']));
					$logger->debug($sql);
					$db_result = $db_eimi->getRow($sql);
					//$db_eimi->cls();
	
	
	
	
			if (!empty($db_result['MOBILE'])) {
	
					$sql5="select count(*) CNT from CONTACT_HISTORY where MD_ID='".$db_result['ID']."'  and KIND='SMS'";
					$logger->debug($sql5);
					$smsNumber = $db_eimi->getRow($sql5);
					$logger->debug($smsNumber);

					if ($smsNumber['CNT'] < 3 ) { 
			 
					$currentDataTime=getCurrentDataTime();
					$code=randomPassword(6);
					$db_eimi = new database2($DSN_MYSQL);
					$db_eimi->query("START TRANSACTION;");
					$sql2 = sprintf("INSERT INTO eimi.CONTACT_HISTORY(MD_ID,KIND,TYPE,CODE,CREATE_DATE,UPDATE_DATE)  values (%d,'%s','%s','%s','%s','%s')",$db_result['ID'],'SMS',$db_result['MOBILE'],$code,$currentDataTime,$currentDataTime);
					$logger->debug($sql2);
					$db_result2 = $db_eimi->query($sql2);
					
					
					$db_eimi->query("COMMIT;");
			
						// /*正式上線改*/
						$my = new SMS();
						$my->message='請在網頁上輸入驗証碼：'.$code;
						$my->cellPhone=$db_result['MOBILE'];
						$my->sendSMS();
						
					} else {
					
					
					 echo 3;
					 
					}	
			
			} else {
				
				 echo 1;
			
			 }
	
		

	
	
	} else {
	
	
	 echo 2;
	
	}
	
	


?>