<?php 

session_start();
include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';
include("/var/www/class/sid_encrypt.php");
	
require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("api");
	


	
		$ip = chk_ip(getClientIp());
		
		
		if(!$ip) {
			header("HTTP/1.1 404 Not Found");exit; 
		}
		
			
			
				

			
				$test = new cSidEncrypt();
				$test->cSidEncrypt();
				$userid=$test->sid_decode($_COOKIE['uid']);
				$fbId=$_GET['fbId'];
				
					$currentDataTime=getCurrentDataTime();
					$db_eimi = new database2($DSN_MYSQL);
			

						$sql="update MEMBER_DATA set FB_ID='".$fbId."',UPDATE_DATE=now() where PERSON_ID= '".$userid."'";
						$logger->debug($sql);
						$result=$db_eimi->query($sql);

					if ($result) {
					
					
					} else {
					
							header("HTTP/1.1 404 Not Found");exit; 
					}
		
			
			
		
			

	

?>	