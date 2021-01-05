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

function dump($dumpedVar, $dumpInfo = "dumped Variable") {
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

function hostName(bool $full = false): string
{
    $name = getHostname();
    if (!$full && $name && strpos($name, '.') !== false) {
        $name = substr($name, 0, strpos($name, '.'));
    }
    return $name;
}

function getWebServerName(): ?string
{
    return $_SERVER['SERVER_NAME'] ?? null;
}

function getHostTimeout($mp): int
{
    $duration = $mp->__get('duration');
    $reason   = $mp->__get('shutdow_reason');
    if ($duration /*&& $reason && $reason !== 'stop' && $reason !== 'restart'*/) {
        return $duration;
    }
    return -1;
}

function getURL(): ?string
{
    //$_SERVER['REQUEST_URI'] => '/base/?MadelineSelfRestart=1755455420394943907'
    $url = null;
    if (PHP_SAPI === 'cli') {
        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
    return $url;
}
