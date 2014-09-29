<?php

function fetchDir($dir) { 
	$arr=array();
    foreach(glob($dir.DIRECTORY_SEPARATOR.'*') as $file) { 
        if(is_dir($file)) { 
        	$arr=array_merge($arr,fetchDir($file));
        } else{
        	$arr[]=$file;
        }
    } 
    return $arr;
}

function getFileName($baseDir,$path){
	return str_replace($baseDir.DIRECTORY_SEPARATOR,"",$path);
}

if (count($argv)<4){
	echo "\nargv err\n\n";
	return -1;
}

$baseDir = $argv[1];
$version=$argv[2];
$fileListName=$argv[3];

$files = fetchDir($baseDir);

$list = "local list = {\n\tver = \"".$version."\",\n\tstage = {\n";
foreach ($files as $key => $value) {
	$fileName = getFileName($baseDir,$value);
	if (strcmp($fileListName,$fileName)==0) {
		continue;
	}elseif(strcmp("update.zip",$fileName)==0){
		$list .= "\t\t{name=\"" . $fileName . "\", code=\"". md5_file($value) . "\", act=\"load\"},\n";
	}else{
		$list .= "\t\t{name=\"" . $fileName . "\", code=\"". md5_file($value) . "\"},\n";
	}
}
$list .= "\t},\n\tremove={\n\t},\n}\nreturn list";

file_put_contents($baseDir.DIRECTORY_SEPARATOR.$fileListName, $list);