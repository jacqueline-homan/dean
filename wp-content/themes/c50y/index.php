<?php

get_header();

if (have_posts()){
	?><div class="main-holder"><?php
		?><div class="main-t"><?php
			?><div class="main-b"><?php
				?><div id="content"><?php
				   
				           
				           
				   
					?><div class="text-holder"><?php
						?><div class="text"><h1>LATEST POSTS</h1></div><?php
						?><div id="comments"><?php
							while (have_posts()){
								the_post();
								?><div class="comment-block"><?php
									?><div class="meta"><? if (has_post_thumbnail()){
											?><div class="visual"><?php
												the_post_thumbnail('small');
											?></div><?php
										} else {
											?><div class="visual"><img src="/wp-content/themes/c50y/images/leaf-small.gif" width="118" height="103" /></div><?
										}
										?></div><?php
								
									/*?><div class="meta"><?php
										?><strong><?php the_author_posts_link();?></strong><?php
										?><em><?php echo get_the_date();?></em><?php
									?></div><?php*/
									?><div class="text"><?php
										?><h2><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a></h2><?php
										the_excerpt();
									?></div><?php
								?></div><?php
							}
						?></div><?php
					?></div><?php
				?></div><?php
				
				
				the_single_sidebar();
				
			?></div><?php
		?></div><?php
	?></div><?php
}


?><div class="photos gallery"></div><?php
				?><div class="box"><?php
				        ?><h4>LATEST NEWS</h4><?php
				           ?><div class="visual"></div><?php
				           ?><img class="attachment-small-wp-post-image" width="118" height="103" alt="License Plate" src="http://www.in4h.org/wp-content/uploads/2013/08/2013-118x103.jpg">
				           </img><?php
				           ?><div class="text"></div><?php
				           ?><h5>Supporters Encouraged to Buy Indiana 4-H License Plates</h5>
				               <p>4-H Plates Reinstated August 6, 2013 - Beginning A...</p>
				               <a class="more" title="Supporters Encouraged to Buy Indiana 4-H License Plates" href="http://www.in4h.org/supporters-encouraged-to-buy-Indiana-4-H-license-plates/">
				               	read more
				               </a><?php
get_footer();