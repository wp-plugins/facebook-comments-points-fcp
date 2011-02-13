<?php 

include("../../../wp-config.php");

global $nested,$comments_holder, $total_comments, $fcp_comments_per_page, $id,$ip,$show_user_post_ico,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$fcp_commentator_name_size,$theme_like_ico,$theme_admin_comment_style,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_language_more_records,$fcp_load_effect,$fcp_theme,$sav_id;

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

$id 	= (int)$_REQUEST['id'];
$ip 	= $_SERVER['REMOTE_ADDR'];
$type 	= mysql_real_escape_string($_REQUEST['type']);

$show_user_post_ico = true;

$sav_id =$id ;

if($type == 'all')
{
	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY comment_date ");
}
elseif($type == 'liked')
{
	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY fcp_likes_count desc");
}
elseif($type == 'recent')
{
	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY comment_date desc limit 40");
}
elseif($type == 'userpost')
{
	$show_user_post_ico = false;
	$pid 	    = (int)$_REQUEST['pid'];
	$comment_author_email = $wpdb->get_var("SELECT comment_author_email FROM $wpdb->comments WHERE comment_ID = '$id' AND comment_approved = '1'");
	
	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$pid' AND comment_author_email = '$comment_author_email' AND comment_approved = '1' ORDER BY comment_date desc");
}
elseif($type == 'paging')
{
	$next 	    = (int)$_REQUEST['next'];
	$pid 		= (int)$_REQUEST['id'];
	
	//echo "SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY comment_date desc limit $next,$fcp_comments_per_page";
	$total_comments_paging = $wpdb->get_var("SELECT count(*) FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1'");
	$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY comment_date desc limit $next,$fcp_comments_per_page");
	
}

$total_comments = count($comments);
$comments = array_slice($comments, 0, $total_comments);

if($type == 'paging'){
	//$comments = array_reverse($comments);
}
$GLOBALS['comments_data'] = array();

$nested = 3;

function fcp_comments(&$c, $deep_id = -1, $color = true) 
{
	global $nested,$id,$wpdb,$ip,$show_user_post_ico,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$fcp_commentator_name_size,$theme_like_ico,$theme_admin_comment_style,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_language_more_records,$fcp_load_effect,$sav_id;

	$comments_data = $GLOBALS['comments_data'];	
	?>

	<li id="comment-<?php echo $c->comment_ID ?>" class="fadein">
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
				?>
				</div>
				
				<div class="fcp-actions" style="margin-left:7px; width:73%">
				<?php
				
				global $user_ID, $post;
				get_currentuserinfo();
				
				if (user_can_edit_post_comments($user_ID, $pid)) 
				{
					edit_comment_link('Edit', '',(($GLOBALS['nested_comment_limit'] < $nested)?'&nbsp;&nbsp;': ''));
				}
					
				if ($GLOBALS['nested_comment_limit'] < $nested) 
				{
					if ( get_option("comment_registration") && !$user_ID )
						echo '<a href="'. get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() .'">Log in to Reply</a>';
					else
						echo '<a href="javascript:changeFormPosition('.$c->comment_ID.')" title="Comment">Comment</a>';
				}
				
				$userip_status = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."fcp_likes_ip WHERE comment_ID = '$c->comment_ID' and userip = '$ip'");
				
				if(count($userip_status) == 0)
				{
					echo '<a href="javascript:fCpLike('.$c->comment_ID.')" class="fcp-like-click-'.$c->comment_ID.'" title="'.$fcp_language_like.'">'.$fcp_language_like.'</a>';
					echo '<a href="javascript:fCpUnLike('.$c->comment_ID.')" style="display:none" class="fcp-unlike-click-'.$c->comment_ID.'" title="Unlike">Unlike</a>';
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
				echo '<label style="float:left; margin-top:1px;" class="fcp-likes-stat-'.$c->comment_ID.'">'.$c->fcp_likes_count.'</label>
				<label class="fcp-people-like">'.$fcp_language_people.'</label>';?>
				
				
				<?php
				if(@$fcp_show_user_post_ico && @$show_user_post_ico){?>
				<label style="float:left;">
				<a href="javascript:fcpUserCom(<?php echo $c->comment_ID?>,<?php echo $sav_id?>)" style="float:left; width:26px; display:block;" id="fcp-user-comm-logo-<?php echo $c->comment_ID?>" class="fcp-comments-by-this-user" title="<?php echo $fcp_language_by_this_user?>">
				<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/fcp-user.png" alt="" border="0" />
				</a>
				</label>
				<?php
				}?>
				<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/loader.gif' class="fcp-this-user-loader<?php echo $c->comment_ID?>" style="margin-top:8px;float:left; display:none" alt='' />
				
				<?php
				if(@$fcp_show_twitter_ico){?>
				<label style="float:left;">
				<a href="http://twitter.com/home?status=reading comments on <?php echo get_permalink( $sav_id ); ?> " style="float:left; width:26px; margin-left:3px;display:block; margin-top:1px" title="<?php echo $fcp_language_tweet_this?>">
				<img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/tw.png" alt="" border="0" />
				</a>
				</label>
				<?php
				}?>
				
				<?php
				if (user_can_edit_post_comments($user_ID, $sav_id) || ($GLOBALS['nested_comment_limit'] < $nested))
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
}?>

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
	endif;
?>

<?php
	$t_showing_r = ($fcp_comments_per_page+$_REQUEST['next']);
	if($total_comments_paging > $t_showing_r){?>
	
	<div class="fcp-paging-wrap-<?php echo $t_showing_r?>" style="display:none">
		<br clear="all" />
		<div id="fcp_paging" align="right">
			<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/loader.gif' class="fcp-paging-loader" style="margin-top:15px;display:none " alt='' />
			<input type="hidden" name="fcp_paging_id" id="fcp_paging_id" value="<?php echo $t_showing_r?>" />
			<input type="hidden" name="fcp_paging_pid" id="fcp_paging_pid" value="<?php echo $pid?>" />
			<a href="javascript:void(0)" id="fcp_next_rec"><< <?php echo $fcp_language_more_records?></a>
		</div>
		<br clear="all" />
	</div>
<?php
}?>
<script type="text/javascript">

jQuery(document).ready(function(){

	var Timer  = '';
	var selecter = 0;
	var Main =0;
	clearTimeout(Timer); 
	loadFCPresult(selecter);
	
});
	
function loadFCPresult ( selecter )
{	
	$j('li.fadein:eq(' + selecter + ')').stop().animate({
		opacity  : '1.0',
		
	},350,function(){
		$j('.fcp-paging-wrap-'+<?php echo $t_showing_r?>).fadeIn();
	});
	
	selecter++;
	var Func = function(){ loadFCPresult(selecter); };
	Timer = setTimeout(Func, 150);
}

</script>
