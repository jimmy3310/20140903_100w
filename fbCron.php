<?php

	include("/var/www/conf/config.php");
	include("/var/www/class/LDB2.class.php");
	include_once '/var/www/func/public.func.php';
	
	
function fetch_fb_fans($fanpage_name, $no_of_retries = 10, $pause = 500000 /* 500ms */){
    $ret = array();
    $matches = array();
    $url = 'http://www.facebook.com/plugins/fan.php?connections=100&id=' . $fanpage_name;
    $context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:22.0) Gecko/20100101 Firefox/22.0')));
	
	
    for($a = 0; $a < $no_of_retries; $a++){
        $like_html = file_get_contents($url, false, $context);
		// print  $like_html ;
        preg_match_all('{href="https?://www\.facebook\.com/([a-zA-Z0-9._-]+)" data-jsid="anchor" target="_blank"}', $like_html, $matches);
        if(empty($matches[1])){
            // failed to fetch any fans - convert returning array, cause it might be not empty
            return array_keys($ret);
        }else{
            // merge profiles as array keys so they will stay unique
            $ret = array_merge($ret, array_flip($matches[1]));
        }
        // don't get banned as flooder
        usleep($pause);
    }
    return array_keys($ret);
}
//http://graph.facebook.com/yuyulai1976/picture


$result = fetch_fb_fans('662250033860826', 2, 400000);


print_r($result);
$db_eimi = new database2($DSN_MYSQL);
	
	
	foreach ($result as $value) {
	
	$sql2 = "REPLACE  INTO MATT_FB_FAN  VALUES (null,'".$value."')";
		
	$db_result = $db_eimi->query($sql2);
		
	}	




?>