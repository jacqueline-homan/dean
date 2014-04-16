<?php

get_header();

if (have_posts()){

	the_post();
	//the_single_attachments();
	
	?><div class="main-holder"><?php
		?><div class="main-t"><?php
			?><div class="main-b"><?php
				?><div id="content"><?php
					?><h1><? the_title(); ?></h1><?php
					the_content();
				?></div><?php
				
				the_page_sidebar();
				
			?></div><?php
		?></div><?php
	?></div><?php
	
}

get_footer();