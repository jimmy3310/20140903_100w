<?php

	/* 快速發卡資料補登 */

	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include("/var/www/class/memberPwd.php");
	include_once '/var/www/func/public.func.php';
	include_once '/var/www/class/svcSoapClient.php';
	include_once '/var/www/class/sid_encrypt.php';

	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");

  
  
  

  
	/*資料庫有區分，故程式作切割*/
  	$homePhone=array();
	$officePhone=array();
	$town=array();
	$town=explode("-",$_POST['member_add_03_town']);
	
	if (stripos($_POST['homePhone'], '-')) {
	
		$homePhone=explode("-", $_POST['homePhone']);
		$officePhone=explode("-",$_POST['officePhone']);
		
	} else {
	
		$homePhone[0]='';
		$homePhone[1]=$_POST['homePhone'];
		$officePhone[0]='';
		$officePhone[1]=$_POST['officePhone'];
	}
	
	$currentDataTime=getCurrentDataTime();
	$ip=getClientIp();
	$db_eimi = new database2($DSN_MYSQL);
	$db_eimi->query("START TRANSACTION;");
	
		$sql = sprintf("insert into eimi.MEMBER_DATA (ID,CUST_ID,LOC_NAME,ENG_NAME,GENDER,PERSON_TYPE,PERSON_ID,BIRTHDAY,MOBILE,TEL_HOME_1,TEL_HOME_2,TEL_OFFICE_1, TEL_OFFICE_2, TEL_OFFICE_3, ZIP_CODE, COUNTRY, ADDRESS_CITY, ADDRESS_TOWNSHIP, ADDRESS_OTHER, EMAIL, SPARE_EMAIL, RECMD_PERSON_ID, EDUCATION, MARRIAGE, FAMILY_NUM, ANNUAL_INCOME, OCCUPATION, RECV_NEWSLETTER_FLAG, RECV_E_BILLING_FLAG, RECV_P_INTERVIEW_FLAG, RECV_SMS_FLAG, RECV_DM_FLAG, FB_ID, STATUS, NICK_NAME, CLIENT_FILE, SERVER_FILE, IMAGE_MAGICK1, IMAGE_MAGICK2, IMAGE_MAGICK3, IS_SMARTPHONE, MOBILE_ERR_FLAG, EMAIL_ERR_FLAG, ADDRESS_ERR_FLAG, TEL_HOME_ERR_FLAG, SMS_VERIFY_FLAG, EMAIL_VERIFY_FLAG, SMS_VERIFIED_DATE, EMAIL_VERIFIED_DATE, MEMBER_CREATE_DATE, MEMBER_START_DATE, DATA_COMPLETE_DATE, RECOVER_PAPER, CHANNEL_SOURCE, SHOP_CODE, VIP_FLAG, VIP_VALID_DATE, MEM_JOIN_IP, RECMD_ID, CREATE_ID, CREATE_NAME, CREATE_DATE, UPDATE_ID, UPDATE_NAME, UPDATE_DATE ,APPLY_ITEM) values (null,null,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s','%s',%d,%d,%d,%d,%d,'%s','%s','%s','%s','%s','%s','%s',%d,'%s','%s',%d,null,'%s','%s','%s','%s','%s','%s','%s','%s','%s') " ,$db_eimi->esc($_POST['chineseName']),$db_eimi->esc($_POST['engName']),$db_eimi->esc($_POST['radioGender']),$_POST['radioCountry3'],strtoupper($_POST['member_add_03_uid']),$_POST['selectYear03'].'/'.$_POST['selectMon03'].'/'.$_POST['selectDay03'],$db_eimi->esc($_POST['cellPhone']),$homePhone[0],$homePhone[1],$officePhone[0],$officePhone[1],'',$_POST['member_add_03_zip'],'1',$_POST['member_add_03_city'],$town[0],$db_eimi->esc($_POST['member_add_03_address']),$db_eimi->esc($_POST['email']),$db_eimi->esc($_POST['bakEmail']),$db_eimi->esc($_POST['recommandCode']),$db_eimi->esc($_POST['education']),$db_eimi->esc($_POST['marriage']),'0',$db_eimi->esc($_POST['annual_income']),$db_eimi->esc($_POST['member_03_add_job']),getTF($_POST['edm']),getTF($_POST['ebill']),getTF($_POST['obCall']),getTF($_POST['sms']),getTF($_POST['paper']),'','2',$db_eimi->esc($_POST['nickName']),'','','','','',$_POST['smartPhone'],'0','0','0','0','Y','N',$currentDataTime,$currentDataTime,$currentDataTime,$currentDataTime,$currentDataTime,0,'','','0',$ip,'','','',$currentDataTime,'','',$currentDataTime,'V');
		
		
		
		 $memberPwd=new PWD ();
		 $memberPwd->pwdString=$_POST['password5'];
		 $mmberPwdEnc=$memberPwd->getEnc();


		


		
		

		
		

					
					$logger->debug($sql);
					$logger->debug($_POST);
					

					
					
					
					$db_result = $db_eimi->query($sql);
					$lastInsertId=$db_eimi->getOne("select LAST_INSERT_ID()");
					$logger->debug($lastInsertId);
					
					$sql2 = sprintf("insert into eimi.MEMBER_FB_DATA (ID, PASSWORD, PD_UPD_DATE, FORCED_CH_PWD,CREATE_DATE, UPDATE_DATE ) values (LAST_INSERT_ID(),'%s','%s',%d,'%s','%s')",$mmberPwdEnc,$currentDataTime,0,$currentDataTime,$currentDataTime);
					$logger->debug($sql2);
					$db_result = $db_eimi->query($sql2);
					
				
		for ($i=1;$i<=10;$i++){
			$string="interest_01_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}			
		

		for ($i=1;$i<=10;$i++){
			$string="interest_02_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}	


		for ($i=1;$i<=4;$i++){
			$string="interest_03_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}



		for ($i=1;$i<=12;$i++){
			$string="interest_04_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}	
		
		for ($i=1;$i<=6;$i++){
			$string="interest_05_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}			


		for ($i=1;$i<=4;$i++){
			$string="interest_06_".sprintf("%02d", $i);
				if (!empty($_POST[$string])) {
				 $insertString=explode("_",$string);
				
				$sql3=sprintf("insert into eimi.MEMBER_INTEREST (MD_ID, MAIN_CD, SUB_CD, CREATE_DATE, UPDATE_DATE) values ('%s','%s','%s','%s','%s')",$lastInsertId,$insertString[1],$insertString[2],$currentDataTime,$currentDataTime);
					$logger->debug($sql3);
					$db_result = $db_eimi->query($sql3);
				}
		
		
		}			
		$db_eimi->query('COMMIT;');
		
	

	
	
					$currentDataTime=getCurrentDataTime();
					$code=randomPassword(4);
					$db_eimi = new database2($DSN_MYSQL);
					$db_eimi->query("START TRANSACTION;");
					$sql2 = sprintf("INSERT INTO eimi.CONTACT_HISTORY(MD_ID,KIND,TYPE,CODE,CREATE_DATE,UPDATE_DATE)  values (%d,'%s','%s','%s','%s','%s')",$lastInsertId,'EMAIL',$db_eimi->esc($_POST['email']),$code,$currentDataTime,$currentDataTime);
					$logger->debug($sql2);
					$db_result = $db_eimi->query($sql2);
					$db_eimi->query("COMMIT;");
			 

			 	$EMAIL=$db_eimi->esc($_POST['email']);
				$LOC_NAME=urlencode($db_eimi->esc($_POST['chineseName']));
			 
			 	$test = new cSidEncrypt();
				$test->cSidEncrypt();
				$url=$test->sid_encode(strtoupper($_POST['member_add_03_uid']));
				
				
				
				$emailUrl=MAIL_URL."verifyEmail.php?code=$url";
				$url = "mail.php?email={$EMAIL}&url={$emailUrl}&userName={$LOC_NAME}";
				$url = MAIL_URL . $url;
				$logger->debug($url);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);
	
	
				$memberData3= new svcClient();	
				$memberData3->mti='0200';
				$memberData3->procCode='9337';
				$memberData3->mobile=trim($_POST['cellPhone']);
				$memberData3->name='';
				$memberData3->email='';
				$memberData3->cardNo='';
				$memberData3->traceNo=$memberData3->getTraceNo();	
				$memberinf=$memberData3->getPersonalInfo();
	
	
				$db_eimi->query("update MEMBER_DATA set CUST_ID='".$memberinf['mbrData'][0]['custId']."' , RECMD_ID='".$memberinf['mbrData'][0]['personCode']."' where PERSON_ID='".strtoupper($_POST['member_add_03_uid'])."' ");
	
				$logger->debug($svcErrorCode[$memberinf['respCode']]); 
				$logger->debug($memberinf);
	
					
					$sql2 = sprintf("select CITY_CHINESE_NAME CITY from CITY where CITY_NO='%s' group by 1",trim($_POST['member_add_03_city']));
					$city_result = $db_eimi->getRow($sql2);
	
	
			$locTownship=explode("-",trim($_POST['member_add_03_town']));
			
			$memberData2= new svcClient();	
			$memberData2->mti='0200';
			$memberData2->procCode='9327';
			$memberData2->personId=strtoupper($_POST['member_add_03_uid']);
			$memberData2->traceNo=$memberData2->getTraceNo();
			$memberData2->personType=trim($_POST['radioCountry3']);
			$memberData2->name=trim($_POST['chineseName']);
			$memberData2->engName=trim($_POST['engName']);
			$memberData2->birthday=$_POST['selectYear03'].$_POST['selectMon03'].$_POST['selectDay03'];
			$memberData2->gender=trim($_POST['radioGender']);
			$memberData2->mobile=trim($_POST['cellPhone']);
			$memberData2->email=trim($_POST['email']);
			$memberData2->region='1';
			$memberData2->zip=trim($_POST['member_add_03_zip']);
			$memberData2->city=$city_result['CITY'];
			$memberData2->township=$locTownship[1];
			$memberData2->address=trim($_POST['member_add_03_address']);
			$memberData2->custId=$memberinf['mbrData'][0]['custId'];
			$memberData2->homeNoArea=$homePhone[0];
			$memberData2->homeNo=$homePhone[1];
			$memberData2->compNoArea=$officePhone[0];
			$memberData2->compNo=$officePhone[1];
			$memberData2->compNoExt='';
			$memberData2->personCode='';
			$memberData2->recmdPersonCode=trim($_POST['recommandCode']);
			$memberData2->school=trim($_POST['education']);
			$memberData2->marriage=trim($_POST['marriage']);
			$memberData2->FamilyNo='';
			$memberData2->annualIncome=trim($_POST['annual_income']);
			$memberData2->job=trim($_POST['member_03_add_job']);
			$memberData2->mobileTrack='';
			$memberData2->emailTrack='';
			$memberData2->addressTrack='';
			$memberData2->homeNoTrack='';
			$memberData2->status='1';
			$memberData2->signature='0';
			$memberData2->vipFlag='0';
			$memberData2->vipValidDate='';
			$memberData2->dataCmpDate='';
			$memberData2->regChn='3';
			$memberData2->regOwner='';
			$memberData2->cardNo='';
			$memberData2->expiryDate='';
			$memberData2->cardType='';
			$memberData2->actionFlag='M';
			$resultSvc=$memberData2->userInfoChange();	

			$logger->debug($svcErrorCode[$resultSvc['respCode']]); 
			$logger->debug($resultSvc);
	
	
		$_SESSION["userId"]=$lastInsertId;
		$_SESSION["userPhone"]=$_POST['cellPhone'];
		$_SESSION["userName"]=$_POST['chineseName'];
		$_SESSION["emailCode"]=$code;
		

	
	if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&  @$_SESSION["uid"] ){




	}

?>