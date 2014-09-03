<?php



	
	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include("/var/www/class/sid_encrypt.php");
	include_once '/var/www/func/public.func.php';
	include_once("/var/www/class/phpMailer/class.phpmailer.php"); 
	include_once '/var/www/class/smarty.class.php';
	require_once('log4php/Logger.php');
	
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");

  

	
	
	if (strtolower($_GET['rePassCaptcha']) == strtolower($_SESSION['captcha']['code'] )) {
	
	$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("
					SELECT a.ID, a.CUST_ID, a.SMS_VERIFY_FLAG, a.EMAIL_VERIFY_FLAG,EMAIL,LOC_NAME,PERSON_ID
					FROM eimi.MEMBER_DATA a inner join MEMBER_FB_DATA b on a.ID = b.ID
					where a.PERSON_ID= '%s' 
					and year(BIRTHDAY)= '%s'  and  MONTH(BIRTHDAY)= '%s'  and  day(BIRTHDAY)= '%s'  ", $db_eimi->esc($_GET['customerID']),$db_eimi->esc($_GET['rePassYear']),$db_eimi->esc($_GET['rePassMon']),$db_eimi->esc($_GET['rePassDay']));
					$db_result = $db_eimi->getRow($sql);
					$db_eimi->cls();
	$logger->debug($sql);
	

	
	
	
	
			 if (!empty($db_result['EMAIL'])) {
				
				 $_SESSION["uid"]=$_GET['customerID'];
				 $_SESSION["email"]=$db_result['EMAIL'];
				 $logger->debug($db_result['EMAIL']);
				
				
				
					$currentDataTime=getCurrentDataTime();
					$code=randomPassword(4);
					$db_eimi = new database2($DSN_MYSQL);
					$db_eimi->query("START TRANSACTION;");
					$sql2 = sprintf("INSERT INTO eimi.CONTACT_HISTORY(MD_ID,KIND,TYPE,CODE,CREATE_DATE,UPDATE_DATE,CREATE_ID,UPDATE_ID,CREATE_NAME,UPDATE_NAME)  values (%d,'%s','%s','%s','%s','%s','%s','%s','%s','%s')",$db_result['ID'],'FORGOT',$db_result['EMAIL'],$code,$currentDataTime,$currentDataTime,strtoupper($db_eimi->esc($_GET['customerID'])),strtoupper($db_eimi->esc($_GET['customerID'])),$db_result['LOC_NAME'],$db_result['LOC_NAME']);
					$logger->debug($sql2);
					$db_result2 = $db_eimi->query($sql2);
					$db_eimi->query("COMMIT;");
				
					$test = new cSidEncrypt();
					$test->cSidEncrypt();
					$url=$test->sid_encode($db_result['PERSON_ID']);
					$logger->debug($sql);

					
					$emailUrl=MAIL_URL."verifyPass.php?code=$url";
					$logger->debug($emailUrl);
				
					
					$smarty = new ezSmarty;
					$smarty->caching = false; 
					$smarty->assign('userName', $db_result['LOC_NAME']);
					$smarty->assign('url',  $emailUrl);
					$html = $smarty->fetch('pass_confirm.html');
					
					

					$emailAddress=$db_result['EMAIL'];
					
					$mail= new PHPMailer();    
					$mail->IsSMTP();   
					$mail->SMTPAuth = true; 
					$mail->SMTPSecure = "tls"; 
					$mail->Host = "smtp.gmail.com"; 
					$mail->Port = 587;          
					$mail->CharSet = "utf-8";     
					$mail->Encoding = "base64";
					
					$mail->Username = "matthew.wang@eimi.com.tw";       
					$mail->Password = "asako111";       
					
					$mail->From = "serviceg@eimi.com.tw";      
					$mail->FromName = "得易卡服務小組";   
						  
					$mail->Subject = "得易卡網站會員密碼變更啟用信";  
					$mail->Body =$html;
					$mail->IsHTML(true);      
				 

					$mail->AddAddress($emailAddress);  
					$mail->Send();
					echo $db_result['EMAIL'];
				
				
				
				
				
				
				
			
			} else {
				
				 echo 1;
			
			 }
		
	} else {
		$logger->debug('辦識碼錯誤'."\t".$_GET['rePassCaptcha']."\t".$_SESSION['captcha']['code']."\t".$_GET['customerID']);
		echo 2;
			
	}




?>