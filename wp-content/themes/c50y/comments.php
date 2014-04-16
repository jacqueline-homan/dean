<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))) die('Please do not load this page directly. Thanks!');
if (post_password_required()){ ?><p><strong>This post is password protected. Enter the password to view comments.</strong></p><?php return; }

function theme_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	?><div id="comment-<?php echo $comment->comment_ID;?>" class="comment-block"><?php
		?><div class="meta"><?php
			?><strong><?php the_author_link();?></strong><?php
			?><em><?php comment_date();?></em><?php
		?></div><?php
		?><div class="text"><?php
			if ($comment->comment_approved != '0')
				echo get_comment_text();
			else {
				?><p>Your comment is awaiting moderation.</p><?php
			}
		?></div><?php
}
function theme_comment_end(){ ?></div><?php }

if ((get_option('default_comment_status') == 'open') && comments_open()){
	?><div id="comments"><?php
		if (have_comments()) wp_list_comments(array('callback' => 'theme_comment', 'end-callback' => 'theme_comment_end'));
		
		?><div id="respond"><?php
			if (get_option('comment_registration') && !is_user_logged_in()){
				?><p>You must be <a href="<?php echo wp_login_url(get_permalink());?>">logged in</a> to post a comment.</p><?php
			}
			else {
				?><h2>leave a comment</h2><?php
				?><form action="<?php echo get_option('siteurl');?>/wp-comments-post.php" method="post" id="commentform"><?php
					?><fieldset><?php
						
						if (is_user_logged_in()){
							?><div class="row">Logged in as <a href="<?php echo get_option('siteurl');?>/wp-admin/profile.php"><?php echo $user_identity;?></a>.<?php
								?>&nbsp;<a href="<?php echo wp_logout_url(get_permalink());?>">Log out &raquo;</a></div><?php
						}
						else {
							?><div class="row"><?php
								?><label for="text1">Your Name:</label><?php
								?><input type="text" id="text1" name="author"/><?php
							?></div><?php
							?><div class="row"><?php
								?><label for="text2">Email (not shown):</label><?php
								?><input type="text" id="text2" name="email"/><?php
							?></div><?php
							?><div class="row"><?php
								?><label for="text3">Website:</label><?php
								?><input type="text" id="text3" name="url"/><?php
							?></div><?php
						}
						
						?><div class="row"><?php
							?><label for="text4">Comment:</label><?php
							?><textarea id="text4" cols="30" rows="10" name="comment"></textarea><?php
						?></div><?php
						?><div class="row"><?php
							?><input type="submit" value="Submit" class="btn-submit" name="submit"/><?php
						?></div><?php
						
						ob_start();
						do_action('comment_form', $post->ID);
						comment_id_fields();
						$_comment_form = remove_linetabs(ob_get_contents());
						ob_end_clean();
						echo $_comment_form;
						unset($_comment_form);
						
					?></fieldset><?php
				?></form><?php
			}
		?></div><?php
	?></div><?php
}