<?php

get_header();
//the_single_attachments();

if (have_posts()){
	the_post();
	
	?><div class="main-holder"><?php
		?><div class="main-t"><?php
			?><div class="main-b"><?php
				?><div id="content"><?php
					?><div class="text-holder"><?php
						?><div class="text"><?php
							?><h1><? the_title(); ?></h1><?php
							the_content();
						?></div><?php
						
						comments_template();
						
					?></div><?php
				?></div><?php
				
				the_single_sidebar();
				
			?></div><?php
		?></div><?php
	?></div><?php
}

get_footer();