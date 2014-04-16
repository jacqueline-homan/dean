<?php

get_header();

?><div class="main-holder"><?php
	?><div class="main-t"><?php
		?><div class="main-b"><?php
			?><div id="content"><?php
				?><div class="text-holder"><?php
					?><div class="text"><h1><?php
						$post = $posts[0];
						if (is_category()){
							?>Archive for the &laquo;<?php single_cat_title();?>&raquo; Category<?php
						} elseif (is_tag()){
							?>Posts Tagged &laquo;<?php single_tag_title();?>&raquo;<?php
						}
						elseif (is_day()) echo 'Archive for '.get_the_time('F jS, Y');
						elseif (is_month()) echo 'Archive for '.get_the_time('F, Y');
						elseif (is_year()) echo 'Archive for '.get_the_time('Y');
						elseif (is_author()) echo 'Author Archive';
						elseif (isset($_GET['paged']) && !empty($_GET['paged'])) echo 'Blog Archives';
					?></h1></div><?php
					?><div id="comments"><?php
						if (have_posts()){
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
						}
						else {
							?><div class="text"><?php
								?><h2>NOT FOUND</h2><?php
								?><p>Sorry, but you are looking for something that isn't here.</p><?php
							?></div><?php
						}
					?></div><?php
				?></div><?php
			?></div><?php
			
			the_single_sidebar();
			
		?></div><?php
	?></div><?php
?></div><?php

get_footer();