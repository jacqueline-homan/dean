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

get_footer();