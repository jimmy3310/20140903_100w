<?php






	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	
	
	
	$db_eimi = new database2($DSN_MYSQL);
					$sql = sprintf("SELECT TOWN_NO,TOWN_CHINESE_NAME FROM eimi.CITY where CITY_NO ='%s' group by 2 order by CITY_NO" , $db_eimi->esc($_POST["CNo"]));
						
					$db_result = $db_eimi->getAll($sql);
					$db_eimi->cls();
	

	
	

	
	
	if (count($db_result) > 0) {
		
			echo "<option value=''>鄉鎮區</option>";
			for($i=0;$i<count($db_result);$i++){
			
				echo "<option value='" . $db_result[$i]['TOWN_NO'].'-'.$db_result[$i]['TOWN_CHINESE_NAME']. "'>" . $db_result[$i]['TOWN_CHINESE_NAME'] . "</option>";
			}


	} else {
		echo "<option value=''>鄉鎮區</option>";
	}





?>
