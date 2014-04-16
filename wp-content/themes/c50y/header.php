<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes();?>>

<head><title><?php bloginfo('name'); wp_title(' &raquo; ', true, 'left');?></title>

<meta http-equiv="Content-Type" content="<?php echo get_option('html_type');?>; charset=<?php bloginfo('charset');?>"/>
<meta name="google-site-verification" content="wq4INjZ0fF5x0byfhBjJPxXJhSzX8WGxqhiFLaDlXVQ" />
<link rel="stylesheet" href="<?php echo get_stylesheet_uri();?>" type="text/css" media="all"/>

<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="<?php TDU();?>/ie.css"/><![endif]-->

<?php wp_head(); ?></head><body><div id="wrapper"><div class="wrapper-holder"><?php

		?><div class="wrapper-frame"><?php

			?><div id="header"><?php

				?><div class="holder"><?php

					?><strong class="logo"><a href="<?php bloginfo('url');?>"><?php bloginfo('name');?></a></strong><?php



					?><div id="attleadin">presented by AT&amp;T:</div><a href="http://www.att.com"><img src="http://in4h.org/wp-content/uploads/2011/10/sponsor.logo_.att_.png" id="attlogo" width="111" height="67" alt="Rethink Possible" border="0" /></a><?

					

					the_sidebar('socials');

					

					wp_nav_menu(array(

						'menu' => 'main-menu',

						'container' => '',

						'menu_id' => 'nav',

						'menu_class' => 'nav',

						'depth' => 2,

						'walker' => new Walker_Main_Menu()

					));

					

				?></div></div><div id="main">