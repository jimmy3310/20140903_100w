<?php
include_once '/var/www/conf/config.php';
include_once '/var/www/class/smarty.class.php';
include_once '/var/www/class/cache.class.php';
include_once '/var/www/func/public.func.php';
include_once '/var/www/class/svcSoapClient.php';
include_once '/var/www/class/sid_encrypt.php';

	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");



	if (empty($_COOKIE['uid']) || $_COOKIE['smsFlag'] != 'Y' || $_COOKIE['emailFlag'] != 'Y' ) {
	
		header( 'Location: '. HOMEPAGE ) ;
		return true;
	
	}

	
	$logger->debug($_COOKIE['uid']);
	$test = new cSidEncrypt();
	$test->cSidEncrypt();
	// $test->sid_decode($_COOKIE['uid']);

	
	$cache = new cache_page(CACHE_ROOT, 'point'.$_COOKIE['uid'] , CACHE_EXPIRY);
	
	if (@$_GET['cache'] == 'no') {
		$cache->cache_clean();
	}
		
	$cData = $cache->cache_get();

	if ($cData != '') {
		echo $cData;
		exit;
	} else {


	$dateSvc = date('Ymd');
	$dateHtml = date('Y/m/d');
		$memberData2= new svcClient();	
		
		$memberData2->mti='0200';
		$memberData2->procCode='9527';
		$memberData2->personId=$test->sid_decode($_COOKIE['uid']);
		// $memberData2->personId='A123456789';
		$memberData2->inqType='P';
		// $memberData2->cardNo='9999888877776666';
		// $memberData2->cardNo='';
		$memberData2->inqSdate='20110101';
		$memberData2->inqEdate=$dateSvc ;
		$memberData2->expiryDate='99991231';
		$memberData2->traceNo=$memberData2->getTraceNo();
	
		$memberTnx=$memberData2->getTransactionList();
		
		
		/*取餘額 
		以此設定bonus id為32開頭的點數且最多只會有兩筆有效的紅利餘額資料，	一筆是今年到期，一筆是明年到期，
		*/
		 $memberData= new svcClient();	
		 $memberData->mti='0200';
		 $memberData->procCode='9517';
		 $memberData->personId=$test->sid_decode($_COOKIE['uid']);
		 $memberData->inqType='P';
		 $memberData->traceNo=$memberData->getTraceNo();
	
		 $totalPoint=$memberData->getRemainingPoint();
		
		$logger->debug($totalPoint);
		//print_r($totalPoint['balArea']);
		
		$tPoints=0;
		
		/**/
		if ($totalPoint['respCode']=='00') {
		for ($i=0;$i<count($totalPoint['balArea']);$i++){
		
			if (preg_match("/^32/", $totalPoint['balArea'][$i]['bonusId']) ) {
			
				$tPoints+=$totalPoint['balArea'][$i]['qty'];
			
			
			}
		}
		}
		
		
	
	$memberId=getMemberNumber();
	$pointId=getPointNumber();
	
	
	
	$smarty = new ezSmarty;
	$smarty->assign('memberId1',  $memberId[0]);
	$smarty->assign('memberId2',  $memberId[1]);
	$smarty->assign('memberId3',  $memberId[2]);
	$smarty->assign('memberId4',  $memberId[3]);
	$smarty->assign('memberId5',  $memberId[4]);
	$smarty->assign('memberId6',  $memberId[5]);
	$smarty->assign('memberId7',  $memberId[6]);
	
	$smarty->assign('pointId1',  $pointId[0]);
	$smarty->assign('pointId2',  $pointId[1]);
	$smarty->assign('pointId3',  $pointId[2]);
	$smarty->assign('pointId4',  $pointId[3]);
	$smarty->assign('pointId5',  $pointId[4]);
	$smarty->assign('pointId6',  $pointId[5]);	
	$smarty->assign('pointId7',  $pointId[6]);
	$smarty->assign('pointId8',  $pointId[7]);
	$smarty->assign('pointId9',  $pointId[8]);
	$smarty->assign('points',  number_format($tPoints));
	$smarty->assign('currentDate',  $dateHtml);
	
	
	
	$smarty->assign('TnxData',  $memberTnx['txnArea']);
	
	$html = $smarty->fetch('point.html');
	//$cache->cache_save($html);
	echo $html;
}

?>
