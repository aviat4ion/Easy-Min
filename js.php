<?php
//Get config files
require('./config/config.php');
require('./config/jshrink.php');

//Include the js groups
$groups = require("./config/js_groups.php");

//The name of this file
$this_file = $js_root.'js.php';

// --------------------------------------------------------------------------

/**
 * Get Files
 * 
 * Concatonates the javascript files for the current
 * group as a string
 * @return string
 */
function get_files()
{
	global $groups, $js_root;

	$js = '';
	
	foreach($groups[$_GET['g']] as $file)
	{
		$new_file = realpath($js_root.$file);
		$js .= file_get_contents($new_file);
	}
	
	return $js;
}

// --------------------------------------------------------------------------

//Creative rewriting of /g/groupname to ?g=groupname
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

$js = '';
$modified = array();

// --------------------------------------------------------------------------

//Aggregate the last modified times of the files
if(isset($groups[$_GET['g']]))
{
	$cache_file = $js_root.'/cache/'.$_GET['g'];
	
	foreach($groups[$_GET['g']] as $file)
	{
		$new_file = realpath($js_root.$file);
		$modified[] = filemtime($new_file);
	}
	
	//Add this page too
	$modified[] = filemtime($this_file);
	
	$cache_modified = 0;
	
	//Add the cache file
	if(is_file($cache_file))
	{
		$cache_modified = filemtime($cache_file);
	}
}
else //Nothing to display? Just exit
{
	die("You must specify a group that exists");
}

// --------------------------------------------------------------------------

//Get the latest modified date
rsort($modified);
$last_modified = $modified[0];

$requested_time=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) 
	? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
	: time();

// If the browser's cached version is up to date, 
// don't resend the file
if($last_modified === $requested_time)
{
	header("HTTP/1.1 304 Not Modified");
	exit();
}

// --------------------------------------------------------------------------

//Determine what to do: rebuild cache, send files as is, or send cache.
if($cache_modified < $last_modified)
{
	$js = trim(JShrink::minify(get_files(), array('flaggedComments' => false)));
	$cs = file_put_contents($cache_file, $js);
	
	//Make sure cache file gets created/updated
	if($cs === FALSE)
	{
		die("Cache file was not created. Make sure you have the correct folder permissions.");
	}
}
else if(isset($_GET['debug']))
{
	$js = get_files();
}
else
{
	$js = file_get_contents($cache_file);
}

// --------------------------------------------------------------------------

//This GZIPs the js for transmission to the user
//making file size smaller and transfer rate quicker
ob_start("ob_gzhandler");

header("Content-Type: application/x-javascript; charset=utf8");
header("Cache-control: public, max-age=691200, must-revalidate");
header("Last-Modified: ".gmdate('D, d M Y H:i:s', $last_modified)." GMT");
header("Expires: ".gmdate('D, d M Y H:i:s', (filemtime($this_file) + 691200))." GMT");

echo $js;

ob_end_flush();
//end of js.php
