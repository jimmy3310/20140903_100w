<?php





	session_start();

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	
	
	
	$splitString=addslashes($_GET["TNo"]);
	$TCity=addslashes($_GET["TCity"]);

	$db_eimi = new database2($DSN_MYSQL);
					$sql = "select date_format(LAST_DAY('".$TCity."/".$splitString."/01'),'%d') CNT from dual" 					;
					
					$db_result = $db_eimi->getCol($sql);
					print $sql;
					print_r($db_result);
	
	

			for($i=1;$i<=$db_result[0];$i++){
					echo "<option value='".sprintf("%02d",$i)."'>".sprintf("%02d",$i)."</option>";
			} 
	



?>
