<?php

	/* 新會員注冊 */

	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include_once '/var/www/func/public.func.php';
	include_once '/var/www/class/svcSoapClient.php';
	include_once '/var/www/class/sid_encrypt.php';
	include_once '/var/www/class/sms.php';

	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	
 
  
  
	
				$memberData3= new svcClient();	
				$memberData3->mti='0200';
				$memberData3->procCode='9337';
				$memberData3->personId='A188000169';
				$memberData3->mobile='';
				$memberData3->name='';
				$memberData3->email='';
				$memberData3->cardNo='';
				$memberData3->traceNo=$memberData3->getTraceNo();	
				$memberinf=$memberData3->getPersonalInfo();
				$logger->debug($svcErrorCode[$memberinf['respCode']]); 
				$logger->debug($memberinf);
				
				
				

?>