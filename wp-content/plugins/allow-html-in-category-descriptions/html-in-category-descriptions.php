<?php
/*
Plugin Name: HTML in Category Descriptions
Version: 1.2.1
Plugin URI: http://wordpress.org/extend/plugins/allow-html-in-category-descriptions/developers/
Description: Allows you to add HTML code in category descriptions
Author: Arno Esterhuizen
Author URI: arno.esterhuizen@gmail.com
*/

// Disables Kses only for textarea saves
foreach (array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description') as $filter) {
	remove_filter($filter, 'wp_filter_kses');
}

// Disables Kses only for textarea admin displays
foreach (array('term_description', 'link_description', 'link_notes', 'user_description') as $filter) {
	remove_filter($filter, 'wp_kses_data');
}