<?php






	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	
	
	
	// $db_eimi = new database2($DSN_MYSQL);
					// $sql = sprintf("SELECT TOWN_NO,TOWN_CHINESE_NAME FROM eimi.CITY where CITY_NO ='%s' group by 2 order by CITY_NO" , $db_eimi->esc($_POST["CNo"]));
						
					// $db_result = $db_eimi->getAll($sql);
					// $db_eimi->cls();
	

	
	

	
	
	if (empty($_POST["CNo"])) {
		

		echo "<option value=''>請選擇</option>";

	} else {
		
		
			echo "<option value=''>請選擇</option>";
			for($i=1;$i<=12;$i++){
			
				echo "<option value='".sprintf("%02d",$i)."'>".sprintf("%02d",$i)."</option>";
			}
		
	}





?>
