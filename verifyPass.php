<?php 
session_start();
include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';
include("/var/www/class/sid_encrypt.php");
include("/var/www/class/memberPwd.php");
include_once '/var/www/conf/config.php';

require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("api");
	

	

			

				
	

				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
			
				
					$db_eimi = new database2($DSN_MYSQL);	
					
					$memberPwd=new PWD ();
					$memberPwd->pwdString= trim($_POST['newPassword']);
					$mmberPwdEnc=$memberPwd->getEnc();
					
				
						
						$sql2 = sprintf("UPDATE MEMBER_FB_DATA A ,MEMBER_DATA B SET A.PASSWORD='%s' ,B.UPDATE_DATE=NOW() WHERE  A.ID=B.ID AND B.PERSON_ID='%s'  ",$mmberPwdEnc,$_SESSION['changeUid']);
						$logger->debug($sql2);
						$db_result = $db_eimi->query($sql2);
						$db_eimi->query("update CONTACT_HISTORY set STATUS='S',UPDATE_DATE=now() , KIND='FORGOT' where CREATE_ID='".$_SESSION['changeUid']."' ORDER BY ID DESC LIMIT 1");
						
						if ($db_result) {
						

							echo '<script type="text/javascript">alert("變更成功，系統將會導至登入網頁");</script>';	
							echo '<script type="text/javascript">window.location.href="'.HOMEPAGE.'";</script>';							
							
							// header("Location: ". HOMEPAGE);
						} else {
							echo '<script type="text/javascript">alert("變更失敗，請重新輸入一次");</script>';	
						
						}

					

			
				
				} else {
			
				
				


			
				$test = new cSidEncrypt();
				$test->cSidEncrypt();
				$userid=$test->sid_decode($_GET['code']);
				$logger->debug($userid);
			
			
				$db_eimi = new database2($DSN_MYSQL);			
				$sql2 = sprintf("SELECT COUNT(*) CNT FROM eimi.CONTACT_HISTORY WHERE CREATE_ID='%s' AND KIND='FORGOT' AND CREATE_DATE <= DATE_SUB(now(),INTERVAL 1 day)   ",$userid);
				$logger->debug($sql2);
				$db_result = $db_eimi->getRow($sql2);
			
				if ($db_result['CNT'] >= 1) { 
			
						$_SESSION['changeUid']=$userid;
						$smarty = new ezSmarty;
						
						$smarty->assign('url', htmlentities($_SERVER['PHP_SELF']));
						$html = $smarty->fetch('re_pass_confirm.html');				
						echo $html;
				}
				
					header('HTTP/1.0 404 Not Found');
				
				}
?>	