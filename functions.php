<?php

function rexTester($lan, $code) 
{
    
 	$url = "https://rextester.com/rundotnet/api";
    $language = $lan;
    $program  = $code;

    $data = [
        'LanguageChoice'      => $language,
        'Program'             => $program,
        'Input'               => "",
        'CompilerArgs'        => "source_file.cpp -o a.out",
    ];

	$options = array(
        	'http' => array(
            	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            	'method'  => 'POST',
            	'content' => http_build_query($data)
        	)
    	);
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $false = "falid to connect to rextester servers, please try again.";
        return $false;
    }
    
    return json_decode ($result, true);
}

function convert($size) { 
    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PT'); 
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i]; 
}

function dump($dumpedVar, $ret = true, $dumpInfo = "dumped Variable", $bool = true) {
    ob_start();
    echo " $dumpInfo : ";
    var_dump($dumpedVar);
    echo PHP_EOL;
    $rtempy = ob_get_clean();
    return $rtempy;
}

function getCpuUsage(): string
{
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        return $load[0] . '%';
    } else {
        return '_UNAVAILABLE_';
    }
}