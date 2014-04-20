</div></div></div><?php
	?><div id="footer"><?php
		?><div class="footer-holder"><?php
			?><div class="footer-frame"><?php
				?><div class="container"><?php
					
					confirm_sidebar('bottom', '<div class="section">');
					
					?><div class="boxes"><?php
						?><div class="boxes-t"><?php
							?><div class="boxes-c"><?php
								
								//the_sidebar('bottom-2-left');
								
								/*query_posts(array(
									'post_type' => 'page',
									'post_status' => 'publish',
									'posts_per_page' => 1,
									'meta_key' => 'featured',
									'meta_value' => 'true'
								));*/
								query_posts(array(
									'post_type' => 'post',
									'post_status' => 'publish',
									'posts_per_page' => 1,
									'category_name' => 'Hall of Fame'
								));
								
								if (have_posts()){
									the_post();
									
									?><div class="box"><?php
										
										?><h4>LATEST NEWS</h4><?php
										
										if (has_post_thumbnail()){
											?><div class="visual"><?php
												the_post_thumbnail('small');
											?></div><?php
										}
										
										?><div class="text"><?php
											?><h5><?php the_title();?></h5><?php
											
											$_excerpt = get_the_excerpt(); //get_post_meta(get_the_ID(), 'excerpt', true);
											if (!empty($_excerpt)){
												?><p><?php echo $_excerpt;?></p><?php
											}
											unset($_excerpt);
											
											?><a href="<?php the_permalink();?>" class="more" title="<?php the_title_attribute();?>">read more</a><?php
										?></div><?php
										
									?></div><?php
								}
								
								wp_reset_query();
								
								the_sidebar('bottom-2-right');
								
							?></div><?php
						?></div><?php
						?><div class="boxes-b">&nbsp;</div><?php
					?></div><?php
					
					the_sidebar('ftr');
					
				?></div><?php
			?></div><?php
		?></div><?php
	?></div><?php
?></div><?php wp_footer();?></body></html>