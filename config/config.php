<?php

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
$document_root = $_SERVER['DOCUMENT_ROOT'];

/*
|--------------------------------------------------------------------------
| CSS Folder
|--------------------------------------------------------------------------
|
| The folder where css files exist, in relation to the document root
|
*/
$css_root = $document_root. '/css/';

/*
|--------------------------------------------------------------------------
| JS Folder
|--------------------------------------------------------------------------
|
| The folder where javascript files exist, in relation to the document root
|
*/
$js_root = $document_root. '/js/';