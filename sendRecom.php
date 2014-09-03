<?php




	include("/var/www/conf/config.php");
	include_once '/var/www/class/smarty.class.php';
	include_once("/var/www/class/phpMailer/class.phpmailer.php");    
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	$logger->debug($_POST);
	
	
	

	
	


	for ($i=0;$i<count($_POST['recomFriendName']);$i++) {
	
			if (empty($_POST['recomFriendEmail'][$i]) || empty($_POST['recomFriendName'][$i])) { continue;}
			
			$smarty = new ezSmarty;
			$smarty->caching = false; 
			$smarty->assign('senderName', trim($_POST['recomName']));
			$smarty->assign('recName',  trim($_POST['recomFriendName'][$i]));
			$smarty->assign('recomCode', trim($_POST['recomCode']));		
			$html = $smarty->fetch('edm.html');
			

			
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
				  
			$mail->Subject = "我是你的好朋友 ".$_POST['recomName']." ,介紹你一個超級好康活動，快加入得易卡吧！不只馬上送你得易卡點數1000點，還可以再抽百萬點唷";  
			$mail->Body =$html;
			$mail->IsHTML(true);      
		 

			$mail->AddAddress($_POST['recomFriendEmail'][$i]);  
			$mail->Send();
	}
	
	echo count($_POST['recomFriendName']);
?>