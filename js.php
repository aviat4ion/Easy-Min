<?php
//Change as needed
$base_path = $_SERVER['DOCUMENT_ROOT'];
$cache_file = $base_path.'/cache/cache.js';

require('./config/jshrink.php');

//This GZIPs the js for transmission to the user
//making file size smaller and transfer rate quicker
ob_start("ob_gzhandler");

//Creative rewriting
$pi = $_SERVER['PATH_INFO'];
$pia = explode('/', $pi);

$pia_len = count($pia);
$i = 1;

while($i < $pia_len)
{
	$j = $i+1;
	$j = (isset($pia[$j])) ? $j : $i;
	
	$_GET[$pia[$i]] = $pia[$j];
	
	$i = $j + 1;
};

//Include the js groups
$groups = require("./config/js_groups.php");

$js = '';
$modified = array();

if(isset($groups[$_GET['g']]))
{
	foreach($groups[$_GET['g']] as $file)
	{
		$new_file = realpath($base_path.$file);
		$js .= file_get_contents($new_file);
		$modified[] = filemtime($new_file);
	}
}

//Add this page too
$modified[] = filemtime($base_path."js.php");

$cache_modified = 0;

//Add the cache file
if(is_file($cache_file))
{
	$cache_modified = filemtime($cache_file);
}

//Get the latest modified date
rsort($modified);
$last_modified = $modified[0];

$requested_time=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) 
	? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
	: time();

if($last_modified === $requested_time)
{
	header("HTTP/1.1 304 Not Modified");
	exit();
}

if(!isset($_GET['debug']) && ($cache_modified < $last_modified))
{
	$js = trim(JShrink::minify($js, array('flaggedComments' => false)));
	file_put_contents($cache_file, $js);
}
else
{
	$js = file_get_contents($cache_file);
}

header("Content-Type: application/x-javascript; charset=utf8");
header("Cache-control: public, max-age=691200, must-revalidate");
header("Last-Modified: ".gmdate('D, d M Y H:i:s', $last_modified)." GMT");
header("Expires: ".gmdate('D, d M Y H:i:s', (filemtime($base_path.'js.tsp') + 691200))." GMT");

echo $js;

ob_end_flush();
//end of js.php
