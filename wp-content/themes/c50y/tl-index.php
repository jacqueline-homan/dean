<?php

get_header();
/*
Template Name:Home
*/

the_sidebar('home-header');

if (have_posts()){

	the_post();
	
	$_attachments = get_post_attachments_ids(get_the_ID());
	if (!empty($_attachments)){
		?><div class="photos gallery"><?php
			?><ul><?php
				
				if (isset($_attachments[0])){
					?><li><?php
						?><span class="visual"><?php
							$_src = wp_get_attachment_image_src($_attachments[0], 'full');
							?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
								echo wp_get_attachment_image($_attachments[0], 'home-1');
								?><span class="zoom">&nbsp;</span><?php
							?></a><?php
						?></span><?php
						if (isset($_attachments[1])){
							?><span class="visual"><?php
								$_src = wp_get_attachment_image_src($_attachments[1], 'full');
								?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
									echo wp_get_attachment_image($_attachments[1], 'home-2');
									?><span class="zoom">&nbsp;</span><?php
								?></a><?php
							?></span><?php
						}
					?></li><?php
				}
				
				if (isset($_attachments[2])){
					?><li><?php
						?><span class="visual"><?php
							$_src = wp_get_attachment_image_src($_attachments[2], 'full');
							?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
								echo wp_get_attachment_image($_attachments[2], 'home-3');
								?><span class="zoom">&nbsp;</span><?php
							?></a><?php
						?></span><?php
					?></li><?php
				}
				
				if (isset($_attachments[3])){
					?><li><?php
						?><span class="visual"><?php
							$_src = wp_get_attachment_image_src($_attachments[3], 'full');
							?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
								echo wp_get_attachment_image($_attachments[3], 'home-4');
								?><span class="zoom">&nbsp;</span><?php
							?></a><?php
						?></span><?php
						if (isset($_attachments[4])){
							?><span class="visual"><?php
								$_src = wp_get_attachment_image_src($_attachments[4], 'full');
								?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
									echo wp_get_attachment_image($_attachments[4], 'home-5');
									?><span class="zoom">&nbsp;</span><?php
								?></a><?php
							?></span><?php
						}
					?></li><?php
				}
				
				if (isset($_attachments[5])){
					?><li><?php
						?><span class="visual"><?php
							$_src = wp_get_attachment_image_src($_attachments[5], 'full');
							?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
								echo wp_get_attachment_image($_attachments[5], 'home-6');
								?><span class="zoom">&nbsp;</span><?php
							?></a><?php
						?></span><?php
						if (isset($_attachments[6])){
							?><span class="visual"><?php
								$_src = wp_get_attachment_image_src($_attachments[6], 'full');
								?><a href="<?php echo $_src[0];?>" class="cboxElement"><?php
									echo wp_get_attachment_image($_attachments[6], 'home-7');
									?><span class="zoom">&nbsp;</span><?php
								?></a><?php
							?></span><?php
						}
					?></li><?php
				}
				
			?></ul><?php
		?></div><?php
	}
	unset($_attachments);

}

get_footer();