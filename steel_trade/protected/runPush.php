<?php
	ignore_user_abort();
	set_time_limit(0);
	$interval = 1;
	do{
		$ch = curl_init();
		$timeout = 5;
		$value = "http://".$_SERVER['HTTP_HOST']."/index.php/commonForms/push";
		curl_setopt($ch, CURLOPT_URL, $value);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_content = curl_exec($ch);
		curl_close($ch);
		echo $file_content;
		sleep($interval);
	}while (true);

