<?php
	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	
	include_once '/var/www/func/public.func.php';
	include_once '/var/www/class/svcSoapClient.php';
	include_once '/var/www/class/sid_encrypt.php';
	
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	
	if (strtolower($_GET['qloginFormCaptcha']) == strtolower($_SESSION['captcha']['code'] )) {
	
	
	
	
				$memberData= new svcClient();			
				$memberData->mti='0200';
				$memberData->procCode='9357';
				$memberData->mobile=trim(@$_GET['mobileNumber']);
				$memberData->issueType='I';
				$memberData->traceNo=$memberData->getTraceNo();
				$result=$memberData->getFastIssueCard();
				
			
		
	
	
					if ($result['respCode']=='00') {
			
							if (empty($result['cardNo'])) {
							
								echo '此電話號碼尚未申請過得易卡';
							
							} else {
							
								if ($result['cardNo']==trim(@$_GET['cardNumber'])) {
										$_SESSION["cardNumber"]=$_GET['cardNumber'];
										echo 1;
								
								} else {
										$logger->debug(trim(@$_GET['mobileNumber'])."\t".trim(@$_GET['cardNumber'])."\t".$result);
										echo '卡號與行動電話不符合';
								
								}

							
							}
							/* 已是會員*/
					} elseif ($result['respCode']=='50') {
					
						$logger->debug(trim(@$_GET['mobileNumber'])."\t".$result);
						echo '已是會員';
						
					} else  {
					
						$logger->debug(trim(@$_GET['mobileNumber'])."\t".$result);
						echo '連線發生問題請稍後再試';
					
					}
	} else {

		echo '辦識碼錯誤';
	
	}


?>