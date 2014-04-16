<?php
define('INCLUDE_SUBFOLDER', 'inc');
include(TEMPLATEPATH.'/'.INCLUDE_SUBFOLDER.'/includes.php');

automatic_feed_links(false);
remove_action('wp_head', 'wp_generator');

add_filter('excerpt_more', create_function('$str', 'return "&hellip;";'));
add_filter('excerpt_length', create_function('$length', 'return 30;'));

add_theme_support('post-thumbnails');

function pre($message){	?><pre><?php print_r($message);?></pre><?php }

register_nav_menu('main-menu', 'Main Menu');

function register_custom_sidebars(){
	$sidebar_params = array();
	$sidebar_params['name'] = 'Socials';
	$sidebar_params['id'] = 'socials';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="%2$s">';
	$sidebar_params['after_widget'] = '</div>';
	$sidebar_params['before_title'] = '<h2>';
	$sidebar_params['after_title'] = '</h2>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Home: Header';
	$sidebar_params['id'] = 'home-header';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="text-box %2$s">';
	$sidebar_params['before_title'] = '<h1 class="big-ideas">';
	$sidebar_params['after_title'] = '</h1>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Bottom';
	$sidebar_params['id'] = 'bottom';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="box %2$s">';
	$sidebar_params['before_title'] = '<div class="title"><h3>';
	$sidebar_params['after_title'] = '</h3></div>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Bottom 2: Left';
	$sidebar_params['id'] = 'bottom-2-left';
	$sidebar_params['before_title'] = '<h4>';
	$sidebar_params['after_title'] = '</h4>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Bottom 2: Right';
	$sidebar_params['id'] = 'bottom-2-right';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Footer';
	$sidebar_params['id'] = 'ftr';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="panel %2$s">';
	$sidebar_params['before_title'] = '<h3>';
	$sidebar_params['after_title'] = '</h3>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Page';
	$sidebar_params['id'] = 'page';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="panel %2$s">';
	$sidebar_params['before_title'] = '<h2 class="stay-in">';
	$sidebar_params['after_title'] = '</h2>';
	register_sidebar($sidebar_params);
	$sidebar_params['name'] = 'Single';
	$sidebar_params['id'] = 'single';
	$sidebar_params['before_widget'] = '<div id="%1$s" class="panel %2$s">';
	$sidebar_params['before_title'] = '<h4>';
	$sidebar_params['after_title'] = '</h4>';
	register_sidebar($sidebar_params);
}

register_custom_sidebars();

function TDU(){ echo get_template_directory_uri(); }

function sidebar($sidebar_name){
	ob_start();
	dynamic_sidebar($sidebar_name);
	$_sidebar = remove_linetabs(ob_get_contents());
	ob_end_clean();
	return $_sidebar;
}
function the_sidebar($sidebar_name){ echo sidebar($sidebar_name); }
function confirm_sidebar($sidebar_name, $before_sidebar = '<div id="sidebar">', $after_sidebar = '</div>'){
	$_sidebar = sidebar($sidebar_name);
	if (!empty($_sidebar)) echo $before_sidebar.$_sidebar.$after_sidebar;
}

function the_custom_title($title){
	return str_replace('|', '<br/>', $title);
}
add_filter('the_title', 'the_custom_title');

function remove_more_jump_link($link){
	$offset = strpos($link, '#more-');
	if ($offset) $end = strpos($link, '"', $offset);
	if ($end) $link = substr_replace($link, EMPTY_STRING, $offset, $end-$offset);
	return $link;
}
add_filter('the_content_more_link', 'remove_more_jump_link');

function custom_comment_text($text){
	return nl2br($text);
}
add_filter('get_comment_text', 'custom_comment_text');

function the_page_sidebar(){ confirm_sidebar('page'); }
function the_single_sidebar(){ confirm_sidebar('single', '<div id="sidebar" class="alt">'); }

function get_post_attachments_ids($post_id){
	global $wpdb;
	return $wpdb->get_col("select ID from $wpdb->posts where post_type = 'attachment' ".
		"and post_mime_type regexp 'image/(jpeg|gif|png)' and post_parent = ".$post_id." order by menu_order");
}

function the_single_attachments(){
	$_attachments = get_post_attachments_ids(get_the_ID());
	if (!empty($_attachments)){
		?><div class="photos gallery"><?php
			?><ul><?php
				foreach ($_attachments as $_attachment){
					?><li><?php
						?><span class="visual"><?php
							$_src = wp_get_attachment_image_src($_attachment, 'full');
							?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
								echo wp_get_attachment_image($_attachment, 'page-post-2');
								?><span class="zoom">&nbsp;</span><?php
							?></a><?php
						?></span><?php
					?></li><?php
				}
			?></ul><?php
		?></div><?php
	}
}

/**
 * Add shortcode to render donate form
 */
add_shortcode('donate_button', 'donate_button_handler');
function donate_button_handler($atts) 
{
$output = "<div id=\"bbox-root\"></div>"
."<script type=\"text/javascript\">"
." window.bboxInit = function () { "
." bbox.showForm('3ea8ce18-444d-4397-a4d8-8221c9dd1ff4');"
." }; "
."(function () { "
." var e = document.createElement('script'); "
." e.async = true; "
." e.src = 'https://bbox.blackbaudhosting.com/webforms/bbox-min.js'; "
." document.getElementsByTagName('head')[0].appendChild(e); "
." } ()); "
."</script>";
return $output;
}



