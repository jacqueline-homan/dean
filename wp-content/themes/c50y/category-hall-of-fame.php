<?php

get_header();

?><div class="main-holder"><?php
	?><div class="main-t"><?php
		?><div class="main-b"><?php
			?><div id="content"><?php
				?><div class="text-holder"><?php
					?><div class="text"><h1>Hall of Fame</h1></div><?php
					echo category_description();										
					?><div id="comments">
					<?php
						if (have_posts()){
							while (have_posts()){
								the_post();
								?><div class="comment-block"><?php
									?><div class="meta"><? if (has_post_thumbnail()){
											?><div class="visual"><?php
												the_post_thumbnail('small');
											?></div><?php
										} else {
											?><div class="visual"><img src="/wp-content/themes/c50y/images/leaf-small.gif" width="75" height="38" /></div><?
										}
										?></div><?php
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
					?>
					</div>
				</div>
			</div>
			<?php the_single_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>