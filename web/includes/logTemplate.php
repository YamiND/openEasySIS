<?php

function appendLog($filename, $content)
{
	$logDirectory = "../../../logs/";
	$logFile = $logDirectory . $filename;
	$content = $content . PHP_EOL;

//	file_put_contents($logFile, $content, FILE_APPEND | LOCK_EX);

	$fp = fopen($logFile, 'a');
	fwrite($fp, $content);
}

?>
