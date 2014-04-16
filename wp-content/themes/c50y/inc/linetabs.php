<?php

function remove_linetabs($text){ return str_replace(array("\n", "\r", "\t"), '', $text); }

add_filter('the_content', 'remove_linetabs');
add_filter('get_the_content', 'remove_linetabs');
add_filter('the_excerpt', 'remove_linetabs');
add_filter('get_the_excerpt', 'remove_linetabs');