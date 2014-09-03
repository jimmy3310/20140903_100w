<?php 


include_once("/var/www/class/phpMailer/class.phpmailer.php");       
include_once '/var/www/class/smarty.class.php';
include_once("/var/www/class/LDB2.class.php");
include_once '/var/www/func/public.func.php';

require_once('log4php/Logger.php');
Logger::configure('/var/www/properties/log4php.properties');
$logger = Logger::getLogger("api");
	
	$emailAddress=$_GET['email'];
	$url=$_GET['url'];
	$userName=urldecode($_GET['userName']);

	
	$smarty = new ezSmarty;
	$smarty->caching = false; 
	$smarty->assign('userName', $userName);
	$smarty->assign('url',  $url);
	$html = $smarty->fetch('confirm.html');
	
	

	
	
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
          
    $mail->Subject = "得易卡網站會員帳號啟用信";  
    $mail->Body =$html;
    $mail->IsHTML(true);      
 

    $mail->AddAddress($emailAddress);  
	$mail->Send();
	

          
    // if(!$mail->Send()) {        

			// $logger-debug($emailAddress);
			
    // } else {        
			// $logger-debug($emailAddress); 
			
    // }    

	



?>