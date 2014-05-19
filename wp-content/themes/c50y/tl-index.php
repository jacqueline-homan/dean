<?php

get_header();
/*
Template Name:Home
*/

the_sidebar('home-header');

if (have_posts()){

	the_post();
	//echo "hello Dean";
	$_attachments = get_post_attachments_ids(get_the_ID());
	//$_attachments = "";
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
				
			?></ul><?php
		?></div><?php
	}
	unset($_attachments);
}
?>

<!-- Sidebar -->
<div id="home-sidebar">
	
	<div class="widget recent-posts-widget-area">
		<?php
			the_sidebar('bottom-2-left');
		?>
	</div>
	
	<?php							
	// make query to wp for
	query_posts(
		array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'category_name' => 'Hall of Fame'
		)
	);
				
	// enter the loop
	if (have_posts()) {
		the_post();			
	?>
	<div class="widget">
		<h4>LATEST NEWS</h4>
		<?php			
			if (has_post_thumbnail()){
				?><div class="visual"><?php
					the_post_thumbnail('small');
				?></div><?php
			}
		?>
		<div class="text">
			<h5><?php the_title();?></h5>
			<?php
			$_excerpt = get_the_excerpt(); //get_post_meta(get_the_ID(), 'excerpt', true);
			if (!empty($_excerpt)){
				?><p><?php echo $_excerpt;?></p><?php
			}
			unset($_excerpt);
			?>
			<a href="<?php the_permalink();?>" class="more" title="<?php the_title_attribute();?>">read more</a>
		</div>
	</div>
	<?php
	} // end the loop

	wp_reset_query();
	?>
	
</div> <!-- .box    End Sidebar -->

<?php    
get_footer();
?>