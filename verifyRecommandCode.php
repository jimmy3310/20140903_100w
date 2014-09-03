<?php 

session_start();
include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';
include_once '/var/www/class/svcSoapClient.php';
include_once '/var/www/class/sid_encrypt.php';

	
require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("api");
	


	
		
	if ($_REQUEST['recommandCode']) {
		$memberData2= new svcClient();	
		$memberData2->mti='0200';
		$memberData2->procCode='9337';
		$memberData2->mobile='';
		$memberData2->name='';
		$memberData2->email='';
		$memberData2->cardNo='';
		$memberData2->personCode=trim(addslashes(strtoupper($_REQUEST['recommandCode'])));
		$memberData2->traceNo=$memberData2->getTraceNo();
	
		$result=$memberData2->getPersonalInfo();
			
				
		if ($result['respCode']=='00') {
			
			echo "true";
		
		
		} else {
		
			echo "false"; 
		
		}
	} else {
	
	echo "false";
	
	}

?>	