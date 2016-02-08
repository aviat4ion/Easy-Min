<?php
/**
 * Easy Min
 *
 * Simple minification for better website performance
 *
 * @author		Timothy J. Warren
 * @copyright	Copyright (c) 2012-2016
 * @link 		https://git.timshomepage.net/aviat4ion/Easy-Min
 * @license		http://philsturgeon.co.uk/code/dbad-license
 */

// --------------------------------------------------------------------------

return [
	/*
	|--------------------------------------------------------------------------
	| Document Root
	|--------------------------------------------------------------------------
	|
	| The folder where the index of the website exists. In most situations,
	| this will not need to be changed.
	|
	| If the website is in a folder off of the domain name, like:
	|	http://example.com/website/
	| you will need to add that folder to the document root.
	|
	*/
	'document_root' => $_SERVER['DOCUMENT_ROOT'],

	/*
	|--------------------------------------------------------------------------
	| CSS Folder
	|--------------------------------------------------------------------------
	|
	| The folder where css files exist, in relation to the document root
	|
	*/
	'css_root' => $document_root. '/css/',

	/*
	|--------------------------------------------------------------------------
	| Path from
	|--------------------------------------------------------------------------
	|
	| Path fragment to rewrite in css files
	|
	*/
	'path_from' => '',

	/*
	|--------------------------------------------------------------------------
	| Path to
	|--------------------------------------------------------------------------
	|
	| The path fragment replacement for the css files
	|
	*/
	'path_to' => '',

	/*
	|--------------------------------------------------------------------------
	| CSS Groups file
	|--------------------------------------------------------------------------
	|
	| The file where the css groups are configured
	|
	*/
	'css_groups_file' => realpath(__DIR__ . '/css_groups.php'),

	/*
	|--------------------------------------------------------------------------
	| JS Folder
	|--------------------------------------------------------------------------
	|
	| The folder where javascript files exist, in relation to the document root
	|
	*/
	'js_root' => $document_root. '/js/',

	 /*
	|--------------------------------------------------------------------------
	| JS Groups file
	|--------------------------------------------------------------------------
	|
	| The file where the javascript groups are configured
	|
	*/
	'js_groups_file' => realpath(__DIR__ . '/js_groups.php'),

];