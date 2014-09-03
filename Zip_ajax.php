<?php





	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	
	
	require_once('log4php/Logger.php');
	Logger::configure('/var/www/properties/log4php.properties');
	$logger = Logger::getLogger("api");
	
	$splitString=explode('-',$_POST["TNo"]);
	$TCity=$_POST["TCity"];
	
	$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("SELECT AREA FROM eimi.CITY where TOWN_CHINESE_NAME ='%s' and CITY_NO='%s' group by 1 order by CITY_NO" , $splitString[1],$TCity);
					$db_result = $db_eimi->getCol($sql);
					$db_eimi->cls();
	
		$logger->debug($sql);
	

			if (count($db_result) > 0) {
				
				foreach ($db_result as $value) {
					echo  $value;
				}
			} else {
				echo "無資料";
			}
	
	



?>
