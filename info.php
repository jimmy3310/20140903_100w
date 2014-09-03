<?php
include_once '/var/www/conf/config.php';
include_once '/var/www/class/smarty.class.php';
include_once '/var/www/class/cache.class.php';
include_once '/var/www/func/public.func.php';


$id	= $_GET['cd_id'];

$cache = new cache_page(CACHE_ROOT, 'info' , CACHE_EXPIRY);
	
if ($_GET['cache'] == 'no') {
	$cache->cache_clean();
}
	
$cData = $cache->cache_get();

if ($cData != '') {
	echo $cData;
	exit;
} else {


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
	

	
	$html = $smarty->fetch('info.html');
	// $cache->cache_save($html);
	echo $html;
}

?>
