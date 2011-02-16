<?php 

global $nested,$comments_holder,$wpdb,$total_comments, $comments_per_page,$ip,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$theme_like_ico,$fcp_commentator_name_size,$fcp_comments_per_page,$theme_form_close_ico,$theme_admin_comment_style,$fcp_header_box_bg_color,$fcp_header_box_link_color,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_language_more_records,$fcp_load_effect,$fcp_header_logo_link;
	
	/// themes  1 default
	
	$comments_holder = $comments;
	$comments_per_page = $fcp_comments_per_page;
	
	$GLOBALS['comments_data'] = array();
	$total_comments = count($comments);
	$comments = array_slice($comments, -$comments_per_page);
	$comments = array_reverse($comments);
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if ('fcp_comments_temp.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('You are accessing the page directly!');
        if (!empty($post->post_password)) {
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) 
			{?>
				<p class="nocomments">This post is password protected. Enter the password to view comments.<p>
				<?php
				return;
            }
        }
	if(!$tablecomments && $wpdb->comments)
		$tablecomments = $wpdb->comments;
	
	$nested = 3;
	
	function fcp_comments(&$c, $deep_id = -1) 
	{
		global $nested,$comments_holder,$wpdb,$total_comments, $comments_per_page,$ip,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$fcp_commentator_name_size,$theme_like_ico,$theme_admin_comment_style,$theme_form_close_ico,$fcp_comments_per_page,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_language_more_records,$fcp_load_effect;
		
		$comments_data = $GLOBALS['comments_data'];
		
		//if ($c->comment_author_email== get_the_author_email())
		//	echo "<style>#fcp-comments-wrap .fcp-comments-box li{background:url(".bloginfo('siteurl')."img/corner.png) bottom left no-repeat;}</style>";?>
		
		<li id="comment-<?php echo $c->comment_ID;?>" class="<?php echo (($c->comment_author_email== get_the_author_email()) ? $theme_admin_comment_style : "")?>">
			
			<div class="fcp-comments-body">
			
				<div class="fcp-gravatar">
					<?php if(function_exists('get_avatar'))echo get_avatar($c->comment_author_email, '50', ''); ?>
				</div>
					
				<div class="fcp-comment-text">
				
					<div class="fcp-user-name"> <?php comment_author_link(  $c->comment_ID ); ?> </div>
					
					<br clear="all" />
					<?php comment_text();?>
					
					<div class="fcp-date">
					<?php 
					$strTime = strtotime($c->comment_date);
					$CurTime = time();
					
					$remTime = $CurTime-$strTime;
					
					$days = floor($remTime / (60 * 60 * 24));
					$remainder = $remTime % (60 * 60 * 24);
					$hours = floor($remainder / (60 * 60));
					$remainder = $remainder % (60 * 60);
					$minutes = floor($remainder / 60);
					$seconds = $remainder % 60;
					
					//  2011-01-20 12:02:55
					
					if($days == 1)
						{echo "Yesterday at ";echo date('H:i a', $strTime);}
					elseif($days > 1)
						{ echo date('F d Y', $strTime);}
					elseif($days == 0 && $hours == 0 && $minutes == 0)
						echo "few seconds ago";		
					elseif($days == 0 && $hours == 0)
						echo $minutes.' minutes ago';
					elseif($days == 0 && $hours>0 )
						{echo $hours ." hours before";}
					else
						echo "few seconds ago";	
					//echo mysql2date('Y.m.d H:i', $c->comment_date);?>
					
					</div>
					
					<div class="fcp-actions" style="margin-left:7px; width:73%;">
					<?php
					
					global $user_ID, $post,$ip;
					get_currentuserinfo();
					
					if (user_can_edit_post_comments($user_ID, $post->ID)) 
					{
						edit_comment_link('Edit', '',(($GLOBALS['nested_comment_limit'] < $nested)?'&nbsp;&nbsp;': ''));
					}
						
					if ($GLOBALS['nested_comment_limit'] < $nested) 
					{
						if ( get_option("comment_registration") && !$user_ID )
							echo '<a href="'. get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() .'">Log in to Reply</a>';
						else
							echo '<a href="javascript:changeFormPosition('.$c->comment_ID.')" title="'.$fcp_language_comments.'">'.$fcp_language_comments.'</a>';
					}
					
					$userip_status = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fcp_likes_ip WHERE comment_ID = '$c->comment_ID' and userip = '$ip'");
					
					if(count($userip_status) == 0)
					{
						echo '<a href="javascript:fCpLike('.$c->comment_ID.')" class="fcp-like-click-'.$c->comment_ID.'" title="'.$fcp_language_like.'">'.$fcp_language_like.'</a>';
						echo '<a href="javascript:fCpUnLike('.$c->comment_ID.')" style="display:none" class="fcp-unlike-click-'.$c->comment_ID.'" title="'.$fcp_language_unlike.'">'.$fcp_language_unlike.'</a>';
					}
					else
					{
						echo '<a href="javascript:fCpLike('.$c->comment_ID.')" style="display:none" class="fcp-like-click-'.$c->comment_ID.'" title="'.$fcp_language_like.'">'.$fcp_language_like.'</a>';
						echo '<a href="javascript:fCpUnLike('.$c->comment_ID.')" class="fcp-unlike-click-'.$c->comment_ID.'" title="'.$fcp_language_unlike.'">'.$fcp_language_unlike.'</a>';
					}
					?>
					
					<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/loader.gif' class="fcp-comment-loader-<?php echo $c->comment_ID?>" style="margin-top:8px; margin-left:6px;float:left; display:none" alt='' />
					
					<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/<?php echo $theme_like_ico?>' class="likeimg" alt='' />
					
					<?php
					echo '<label style="float:left; margin-top:1px;" class="fcp-likes-stat-'.$c->comment_ID.'">'.$c->fcp_likes_count.'</label> <label class="fcp-people-like">'.$fcp_language_people.'</label>';?>
					
					<?php
					if(@$fcp_show_user_post_ico){?>
					
					<label style="float:left;width:30px;">
						<a href="javascript:fcpUserCom(<?php echo $c->comment_ID?>,<?php echo $post->ID?>)" style="float:left; width:30px; display:block;" id="fcp-user-comm-logo-<?php echo $c->comment_ID?>" class="fcp-comments-by-this-user" title="<?php echo $fcp_language_by_this_user?>">
							<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/fcp-user.png" alt="" border="0" />
						</a>
					</label>
					<?php
					}?>
					
					<label style="float:left;">
						<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/loader.gif' class="fcp-this-user-loader<?php echo $c->comment_ID?>" style="margin-top:8px;float:left; display:none" alt='' />
					</label>
					
					<?php
					if(@$fcp_show_twitter_ico){?>
					
					<label style="float:left;width:30px;">
						<a href="http://twitter.com/home?status=reading comments on <?php echo get_permalink( $post->ID ); ?> " style="float:left; width:30px; margin-left:3px;display:block; margin-top:1px" title="<?php echo $fcp_language_tweet_this?>">
							<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/tw.png" alt="" border="0" />
						</a>
					</label>
					<?php
					}?>
					
					<?php
					if (user_can_edit_post_comments($user_ID, $post->ID) || ($GLOBALS['nested_comment_limit'] < $nested))
					echo '</div>';
				
					echo '</div><div style="clear:both;margin:0px; padding:0px"></div>'; // fcp-comments-body?>
					
				</div>
				
			<?php
				
			if ($comments_data[$c->comment_ID]) 
			{
				$id = $c->comment_ID;
				
				if($GLOBALS['nested_comment_limit'] < $nested )
				echo '<ul>';
				
				$first_c = true;
				
				foreach($comments_data[$id] as $c) 
				{
					if ($first_c){$first_c=false;continue;}
					
					$GLOBALS['nested_comment_limit']++;
					
					if($GLOBALS['nested_comment_limit'] == $nested)
						fcp_comments($c, $c->comment_ID);
					else
						fcp_comments($c, $deep_id);
						
					$GLOBALS['nested_comment_limit']--;
				}
					
				if($GLOBALS['nested_comment_limit'] < $nested )
				echo '</ul>';
			}
			
		echo '</li>';
	}
	
	$style1 = " style='width:".$fcp_panel_width.";'";
	
	//if(@$fcp_links_color)
	//{,$fcp_header_box_bg_color,$fcp_header_box_link_color;
		echo "<style>#fcp-comments-wrap .fcp-actions a{color:".$fcp_links_color.";} #fcp-comments-wrap .fcp-comments-box li .fcp-comments-body {background-color:".$fcp_body_bgcolor."} #fcp-comments-wrap .fcp-comments-box .fcp-comment-text {color:".$fcp_body_text_color.";font-size:".$fcp_body_text_size."} #fcp-comments-wrap .fcp-comments-box .fcp-comment-text .fcp-user-name {color:".$fcp_commentator_name_color.";font-size:".$fcp_commentator_name_size."}  #fcp-comments-wrap .fcp-comments-box .fcp-comment-text .fcp-user-name  a{color:".$fcp_commentator_name_color.";font-size:".$fcp_commentator_name_size."} #fcp-comments-wrap .fcp-comments-box li.fcp-navigations{background: ".$fcp_header_box_bg_color.";} #fcp-comments-wrap .fcp-comments-box li.fcp-navigations a{background: ".$fcp_header_box_link_color.";}</style>";
	//}
?>

<div id="fcp-comments-wrap" <?php echo @$style1?>>

<ul class="fcp-comments-box" id="comments">
	
	<li class="fcp-navigations">
	
		<div style="margin-top:2px; padding-top:3px; height:39px;">
			<!-- show all -->
			<div class="fcp-collapsed-box" id="recent-<?php echo $id; ?>" align="left" style="width:14%; margin-left:5px;">
				<a href="javascript: void(0)" class="fcpnew">
					<?php echo $fcp_language_most_recent?> 
				</a>
			</div>
			
			<div class="fcp-liked-box" id="liked-<?php echo $id;?>" align="left" style="width:12%">
				<a href="javascript: void(0)" class="fcpliked">
					<?php echo $fcp_language_most_liked?> 
				</a>
			</div>
			
			<!-- show all -->
			<div class="fcp-collapsed-box" id="collapsed-<?php echo $id; ?>" align="left" style="width:28%;">
				<a href="javascript: void(0)" class="fcpall">
					<?php echo $fcp_language_all_comments?> (<?php echo @$total_comments?>) 
				</a>
			</div>
			
			<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/loader.gif' class="fcp-header-loader" style="margin-top:15px; margin-left:6px;float:left;display:none " alt='' />
			
			<div class="fcp-sort-box-image">
			
				<?php if(@$fcp_header_logo_link){?>
					<a href="http://www.99points.info/plugins/facebook-comments-points-fcp_wp_plugin/" target="_blank">
					<?php
				}?>
				<img src="<?php bloginfo('siteurl'); ?>/wp-content/plugins/facebook-comments-points-fcp/img/fcpico.png" title="Facebook Comments Point (FCP) WP-Plugin" border="0" alt="" />
				<?php if(@$fcp_header_logo_link){?>
				</a>
				<?php
				}?>
			</div>
			
		</div>
	</li>
	
	<div class="fcp-comments-inner-container">
		
		<?php
		
		if ($comments) :
			foreach ($comments as $c) 
			{
				$GLOBALS['comments_data'][$c->comment_ID][] = $c;
				if (isset($GLOBALS['comments_data'][$c->comment_parent]))
					$GLOBALS['comments_data'][$c->comment_parent][] = $c;
				else 
					$GLOBALS['comments_data'][0][] = $c;
			}
			$GLOBALS['nested_comment_limit'] = 0;
			foreach($GLOBALS['comments_data'][0] as $rec) 
			{
				$GLOBALS['comment'] = &$rec;
				fcp_comments($GLOBALS['comment'], '-1');
			}
		else:
		endif;?>
		
		<?php
	    if($total_comments > $comments_per_page){?>
			<div class="fcp-paging-wrap-<?php echo $comments_per_page?>">
				<br clear="all" />
				<div id="fcp_paging" align="right">
					<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/loader.gif' class="fcp-paging-loader" style="margin-top:15px;display:none " alt='' />
					<input type="hidden" name="fcp_paging_id" id="fcp_paging_id" value="<?php echo $comments_per_page?>" />
					<input type="hidden" name="fcp_paging_pid" id="fcp_paging_pid" value="<?php echo $post->ID?>" />
					<a href="javascript:void(0)" id="fcp_next_rec"><< <?php echo $fcp_language_more_records?></a>
				</div>
				<br clear="all" />
			</div>
		<?php
		}?>
	</div>
	
	<?php if ('open' == $post->comment_status) : ?>
	<div id="fcp-commment-form-div">
	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="../../themes/default/<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>
	
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="fcpCommentForm">
		<br/>
		<div class="fcp-close-box" align="right"><a href="javascript:void(0)" onclick="changeFormPosition(0)"></a></div>
		<?php if ( $user_ID ) : ?>
		
		<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>
		<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>
		
		<?php else : ?>
		
		<div align="left"> 
			<label><?php _e('Name'); ?></label>
			<input type="text" name="author" id="author" value="<?php echo $comment_author;?>" tabindex="11" onclick="this.select();"/>
			
			<label><?php _e('Mail');?></label>
			<input type="text" name="email" id="email" value="<?php echo $comment_author_email;?>" tabindex="12"  onclick="this.select();"/>
			
			<label><?php _e('Website'); ?></label>
			<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="13"  onclick="this.select();"/>
		</div>
		
		<?php endif; ?>
		
		<div>
			<textarea name="fcp_comments_textarea" id="fcp_comments_textarea" tabindex="14" rows="6" ></textarea>
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			<input type="hidden" name="reply_ID" id="reply_ID" value="0" />
			
			<div align="right" style="width:83%; margin-top:3px;">
				<input value="Comment" name="submit" class="fcp-buttons" type="submit" id="fcp-form-button" tabindex="15"/>
				<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook-comments-points-fcp/img/loader.gif' class="fcp-form-loader" style="margin-top:15px;display:none " alt='' />
				<input id="fcp-cancel" type="button" onclick="javascript:changeFormPosition(0)" class="fcp-buttons" style="display:none" value="Cancel" tabindex="17"/>
			</div>
			
			<br clear="all" />
		</div>
		
		<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; ?>
	</div>
	<?php endif;  ?>
	</ul>
</div>