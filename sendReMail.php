<?php

	
	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include("/var/www/class/sid_encrypt.php");
	include_once '/var/www/func/public.func.php';
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("sms");

	

	
	
	if (strtolower(@$_GET['reMailCaptcha']) == strtolower(@$_SESSION['captcha']['code'] )) {
	

	
		
		$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("
					SELECT a.ID, a.CUST_ID, a.SMS_VERIFY_FLAG, a.EMAIL_VERIFY_FLAG,EMAIL,LOC_NAME
					FROM eimi.MEMBER_DATA a inner join MEMBER_FB_DATA b on a.ID = b.ID
					where a.PERSON_ID= '%s' 
					and year(BIRTHDAY)= '%s'  and  MONTH(BIRTHDAY)= '%s'  and  day(BIRTHDAY)= '%s'  ", $_SESSION["uid"],$db_eimi->esc($_GET['reMailYear']),$db_eimi->esc($_GET['reMailMon']),$db_eimi->esc($_GET['reMailDay']));
					$db_result = $db_eimi->getRow($sql);
					$db_eimi->cls();
		$logger->debug($sql);
	

	
				$db_result['EMAIL']='matthew.wang@eimi.com.tw';
				$db_result['LOC_NAME']=urlencode('測試中文許功');
				
			 if (!empty($db_result['EMAIL'])) {
			 
			 
					$currentDataTime=getCurrentDataTime();
					$code=randomPassword(4);
					$db_eimi = new database2($DSN_MYSQL);
					$db_eimi->query("START TRANSACTION;");
					$sql2 = sprintf("INSERT INTO eimi.CONTACT_HISTORY(MD_ID,KIND,TYPE,CODE,CREATE_DATE,UPDATE_DATE)  values (%d,'%s','%s','%s','%s','%s')",$db_result['ID'],'EMAIL',$db_result['EMAIL'],$code,$currentDataTime,$currentDataTime);
					$db_result = $db_eimi->query($sql2);
					$db_eimi->query("COMMIT;");
			 
			 	// $test = new cSidEncrypt();
				// $test->cSidEncrypt();
				// $url=$test->sid_decode($_COOKIE['uid']);
				$url=$_COOKIE['uid'];
				$logger->debug($url);
				
				$emailUrl=MAIL_URL."verifyEmail.php?code=$url";
				$url = "mail.php?email={$db_result['EMAIL']}&url={$emailUrl}&userName={$db_result['LOC_NAME']}";
				$url = MAIL_URL . $url;
				$logger->debug($url);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
	
				
				echo 1;
			
			} else {
				
				 echo '帳號EMAIL有問題';
			
			 }
		
	} else {
		$logger->debug('辦識碼錯誤'."\t".$_GET['reMailCaptcha']."\t".$_SESSION['captcha']['code']."\t". $_SESSION["uid"]);
		echo '辦識碼錯誤';
			
	}
	

	
	


?>