<?php
require_once('../../../wp-config.php');

global  $comments_per_page,$user_identity, $user_email, $user_url,$comment, $comments, $post, $wpdb, $user_ID,$ip,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$fcp_commentator_name_size,$theme_like_ico,$theme_admin_comment_style,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_theme;

switch($fcp_theme){
	case 1:
		$theme_like_ico = "like.png";
		 break;
	case 2:
		$theme_like_ico = "like-2.png";
		 break;
	case 3:
		$theme_like_ico = "like-3.png";
		 break;
	case 4:
		$theme_like_ico = "like-3.png";
		 break;
	case 5:
		$theme_like_ico = "like-2.png";
		 break;
	case 6:
		$theme_like_ico = "like-3.png";
		 break;
	case 7:
		$theme_like_ico = "like-3.png";
		 break;
	case 8:
		$theme_like_ico = "like-3.png";
		 break;
}

ignore_user_abort(true);

foreach($_POST as $c=>$v) {
	$_POST[$c] = rawurldecode($v);
}
$ip = $_SERVER['REMOTE_ADDR'];
$comment_post_ID = (int) $_POST['comment_post_ID'];

$post_status = $wpdb->get_var("SELECT comment_status FROM $wpdb->posts WHERE ID = '$comment_post_ID'");

if ( empty($post_status) ) {

	do_action('comment_id_not_found', $comment_post_ID);
	throwError('The post you are trying to comment on, does not curently exist in the database.');
	
} elseif ( 'closed' ==  $post_status ) {

	do_action('comment_closed', $comment_post_ID);
	throwError(__('Sorry, comments are closed for this item.'));
}

$comment_author       = trim($_POST['author']);
$comment_author_email = trim($_POST['email']);
$comment_author_url   = trim($_POST['url']);
$comment_content      = trim($_POST['fcp_comments_textarea']);
$comment_content	  = preg_replace("/javascript/i", '', $comment_content);

get_currentuserinfo();

if ( $user_ID ) :
	$comment_author       = addslashes($user_identity);
	$comment_author_email = addslashes($user_email);
	$comment_author_url   = addslashes($user_url);
else :
	if ( get_option('comment_registration') )
		throwError(__('Sorry, you must be logged in to post a comment.'));
endif;

$comment_type = '';

if ( get_settings('require_name_email') && !$user_ID ) {
	if ( strlen($comment_author_email) < 3 || $comment_author == '')
		throwError(__('Error: Please fill the required fields (name, email)'));
	elseif ( !is_email($comment_author_email))
		throwError(__('Error: Please enter a valid email address.'));
}

if ($comment_content == '' )
	throwError(__('Error: Please type a comment to submit.'));

$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'user_ID');

$new_comment_ID = wp_new_comment($commentdata);

if ( !$user_ID ) :
        setcookie('comment_author_' . COOKIEHASH, stripslashes($comment_author), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
        setcookie('comment_author_email_' . COOKIEHASH, stripslashes($comment_author_email), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
        setcookie('comment_author_url_' . COOKIEHASH, stripslashes($comment_author_url), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
endif;

function throwError($s) {
	header('HTTP/1.0 500 Internal Server Error');
	echo $s;
	exit;
}

$comment = $wpdb->get_row("SELECT * FROM {$wpdb->comments} WHERE comment_ID = " . $new_comment_ID);

$post->comment_status = $wpdb->get_var("SELECT comment_status FROM {$wpdb->posts} WHERE ID = {$comment_post_ID}");
$comments = array($comment);

foreach ($comments as $c) 
{?>
	<li id="comment-<?php echo $c->comment_ID ?>"  class="<?php echo (($c->comment_author_email== get_the_author_email()) ? $theme_admin_comment_style : "")?>">
	
		<div class="fcp-comments-body">
		
			<div class="fcp-gravatar">
				<?php if(function_exists('get_avatar'))echo get_avatar($c->comment_author_email, '50',''); ?>
			</div>
				
			<div class="fcp-comment-text">
		
				<div class="fcp-user-name"> <?php comment_author_link(  $c->comment_ID ); ?> </div>
				<br clear="all" />
				
				<?php echo $c->comment_content;?>
				<br clear="all" />
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
					echo "few seconds ago";	?>
			</div>
			
			<div class="fcp-actions" style="margin-left:7px; width:73%">
			<?php
			
			global $user_ID, $post;
			
			get_currentuserinfo();
			
			if (user_can_edit_post_comments($user_ID, $comment_post_ID)) 
			{
				//$deleteurl = get_bloginfo("siteurl") . '/wp-admin/comment.php?action=deletecomment&amp;p=' . $c->comment_post_ID . '&amp;c=' . $c->comment_ID;
				
				//$deleteurl = wp_nonce_url($deleteurl, 'delete-comment_'.$c->comment_ID);	
				
				//echo "<a href='$deleteurl' onclick='ajaxShowPost(\"$deleteurl\", \"comment-{$c->comment_ID}\", \"\", \"alert(\\\"comment is deleted\\\")\", \"delete\");return false;'>Delete</a>&nbsp;.&nbsp;";
				
				edit_comment_link('Edit', '',((@$GLOBALS['nested_comment_limit'] < $nested)?'&nbsp;.&nbsp;': ''));
			}
			
			if ( get_option("comment_registration") && !$user_ID )
				echo '<a href="'. get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() .'">Log in to Reply</a>';
			else
				echo '<a href="javascript:changeFormPosition('.$c->comment_ID.')" title="'.$fcp_language_comments.'">'.$fcp_language_comments.'</a>';
				
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
			<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/loader.gif' class="fcp-comment-loader-<?php echo $c->comment_ID?>" style="margin-top:8px; margin-left:6px;float:left; display:none" alt='' />
			
				<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/<?php echo $theme_like_ico?>' class="likeimg" alt='' />
				
				<?php
				echo '<label style="float:left; margin-top:1px;" class="fcp-likes-stat-'.$c->comment_ID.'">'.$c->fcp_likes_count.'</label> <label class="fcp-people-like">'.$fcp_language_people.'</label>';?>
				
				<?php
				if(@$fcp_show_user_post_ico){?>
				<label style="float:left;">
				<a href="javascript:fcpUserCom(<?php echo $c->comment_ID?>,<?php echo $id?>)" style="float:left; width:30px; display:block;" id="fcp-user-comm-logo-<?php echo $c->comment_ID?>" class="fcp-comments-by-this-user" title="<?php echo $fcp_language_by_this_user?>">
				<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/fcp-user.png" alt="" border="0" />
				</a>
				</label>
				<?php
				}?>
				<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/loader.gif' class="fcp-this-user-loader<?php echo $c->comment_ID?>" style="margin-top:8px;float:left; display:none" alt='' />
				
				<?php
				if(@$fcp_show_twitter_ico){?>
				<label style="float:left;">
				<a href="http://twitter.com/home?status=reading comments on <?php echo get_permalink( $id ); ?> " style="float:left; width:30px; margin-left:3px;display:block; margin-top:1px" title="<?php echo $fcp_language_tweet_this?>">
				<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/tw.png" alt="" border="0" />
				</a>
				</label>
				<?php
				}?>
				
			
			<?php
			if (user_can_edit_post_comments($user_ID, $id) || ($GLOBALS['nested_comment_limit'] < $nested))
			echo '</div>';
		
			echo '</div><div style="clear:both;margin:0px; padding:0px"></div>'; // fcp-comments-body?>
			
		</div>
	</li>
	
<?php
}
?>
