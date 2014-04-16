<?php

$_defaults = array(
	
	'constants',
	'classes',
	
	'linetabs',
	'shortcode'
	
);

function include_files($_files, $_base = ''){
	foreach ($_files as $_file) include(TEMPLATEPATH.'/'.INCLUDE_SUBFOLDER.'/'.$_base.$_file.'.php');
}

include_files($_defaults);