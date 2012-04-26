<?php
/**
 * Easy Min
 *
 * Simple minification for better website performance
 *
 * @author		Timothy J. Warren
 * @copyright	Copyright (c) 2012
 * @link 		https://github.com/aviat4ion/Easy-Min
 * @license		http://philsturgeon.co.uk/code/dbad-license
 */

// --------------------------------------------------------------------------

//Get config files
require('./config/config.php');

//Include the css groups
$groups = require("./config/css_groups.php");

//The name of this file
$this_file = 'css.php';


//Function for compressing the CSS as tightly as possible
function compress($buffer) {
    
    //Remove CSS comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    
    //Remove tabs, spaces, newlines, etc.
    $buffer = preg_replace('`\s+`', ' ', $buffer);
    $replace = array(
    	' )' => ')',
    	') ' => ')',
    	' }' => '}',
    	'} ' => '}',
    	' {' => '{',
    	'{ ' => '{',
    	', ' => ',',
    	': ' => ':',
    	'; ' => ';',
    );
    
    //Eradicate every last space!
    $buffer = trim(strtr($buffer, $replace));
    $buffer = str_replace('{ ', '{', $buffer);
    $buffer = str_replace('} ', '}', $buffer);
    
    return $buffer;
}

// --------------------------------------------------------------------------

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

// --------------------------------------------------------------------------

$css = '';
$modified = array();

// Get all the css files, and concatenate them together
if(isset($groups[$_GET['g']]))
{
	foreach($groups[$_GET['g']] as $file)
	{
		$new_file = realpath($css_root.$file);
		$css .= file_get_contents($new_file);
		$modified[] = filemtime($new_file);
	}
}

//Add this page for last modified check
$modified[] = filemtime($this_file);

//Get the latest modified date
rsort($modified);
$last_modified = $modified[0];

// If not in debug mode, minify the css
if( ! isset($_GET['debug']))
{
	$css = compress($css);
}

// Correct paths that have changed due to concatenation
// based on rules in the config file
$css = strtr($css, $path_from, $path_to);

$requested_time=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) 
	? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
	: time();

// Send 304 when not modified for faster response
if($last_modified === $requested_time)
{
	header("HTTP/1.1 304 Not Modified");
	exit();
}

//This GZIPs the CSS for transmission to the user
//making file size smaller and transfer rate quicker
ob_start("ob_gzhandler");

header("Content-Type: text/css; charset=utf8");
header("Cache-control: public, max-age=691200, must-revalidate");
header("Last-Modified: ".gmdate('D, d M Y H:i:s', $last_modified)." GMT");
header("Expires: ".gmdate('D, d M Y H:i:s', (filemtime($this_file) + 691200))." GMT");

echo $css;

ob_end_flush();
//End of css.php