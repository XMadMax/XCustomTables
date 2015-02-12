<?php
if (!defined('XCT_MEDIA_UPLOAD_BASEPATH'))
{
    $uploads_dir = wp_upload_dir();
    define('XCT_MEDIA_UPLOAD_BASEPATH',$uploads_dir['baseurl']);
}

if (!defined('XCTAB_LANG')) 
	define('XCTAB_LANG','xctab');

if (!defined('XCTAB_LANG_LANG')) 
	define('XCTAB_LANG_LANG',get_locale());

