<?php
/*
Plugin Name: Facebook Comments Point (FCP)
Plugin URI: http://99points.info/plugins/facebook_comments_point_wp_plugin/
Version: 2.62
Description: Facebook Comments Point (FCP) is a comments plugin which adds facebook comments looks. Also Its functionality is AJAX based.
Author: Zeeshan Rasool
Author URI: http://99points.info/
*/

global $fcp_db_version,$fcp_show_user_post_ico,$fcp_show_twitter_ico,$fcp_panel_width,$fcp_links_color,$fcp_body_bgcolor,$fcp_body_text_color,$fcp_body_text_size,$fcp_commentator_name_color,$fcp_commentator_name_size,$fcp_theme,$fcp_header_box_bg_color,$fcp_header_box_link_color,$fcp_language_all_comments,$fcp_language_most_liked,$fcp_language_most_recent,$fcp_language_comments,$fcp_language_like,$fcp_language_unlike,$fcp_language_people,$fcp_language_by_this_user,$fcp_language_tweet_this,$fcp_language_more_records,$fcp_load_effect,$theme_like_ico,$fcp_header_logo_link;

$fcp_db_version = "1.0";

$fcp_show_user_post_ico = get_option('fcp_show_user_post_ico');

$fcp_show_twitter_ico = get_option('fcp_show_twitter_ico');

$fcp_panel_width = get_option('fcp_panel_width');

$fcp_links_color = get_option('fcp_links_color');

$fcp_body_bgcolor = get_option('fcp_body_bgcolor');

$fcp_body_text_color = get_option('fcp_body_text_color');

$fcp_body_text_size = get_option('fcp_body_text_size');

$fcp_commentator_name_color = get_option('fcp_commentator_name_color');

$fcp_commentator_name_size = get_option('fcp_commentator_name_size');

$fcp_comments_per_page = get_option('fcp_comments_per_page');

$fcp_theme = get_option('fcp_theme');

$fcp_language_most_recent = get_option('fcp_language_most_recent');
$fcp_language_most_liked = get_option('fcp_language_most_liked');
$fcp_language_all_comments = get_option('fcp_language_all_comments');

$fcp_language_comments = get_option('fcp_language_comments');
$fcp_language_like = get_option('fcp_language_like');
$fcp_language_unlike = get_option('fcp_language_unlike');

$fcp_language_people = get_option('fcp_language_people');
$fcp_language_by_this_user = get_option('fcp_language_by_this_user');
$fcp_language_tweet_this = get_option('fcp_language_tweet_this');

$fcp_language_more_records = get_option('fcp_language_more_records');
$fcp_load_effect = get_option('fcp_load_effect');
$fcp_header_logo_link = get_option('fcp_header_logo_link');
//$fcp_header_box_link_color = get_option('fcp_header_box_link_color');
//$fcp_header_box_bg_color = get_option('fcp_header_box_bg_color');

function fcp_install () 
{
   global $wpdb,$fcp_db_version;

   $installed_ver = get_option( "fcp_db_version" );

   if( $installed_ver != $fcp_db_version ) 
   {
	   $table_name = $wpdb->prefix . "fcp_likes_ip";
	   
	   if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
	   {
		  $sql = "CREATE TABLE " . $table_name . " (
				  id int(11) NOT NULL auto_increment,
				  userip varchar(200) NOT NULL,
				  comment_ID int(11) NOT NULL,
				  PRIMARY KEY  (`id`)
				)";
	
		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		  dbDelta($sql);
	
		  add_option("fcp_db_version", $fcp_db_version);
		}
		
		$query = $wpdb->query("ALTER TABLE $wpdb->comments ADD COLUMN fcp_likes_count INT NOT NULL DEFAULT 0;");
	
		/// OPTIONS
		
		add_option("fcp_show_user_post_ico", '1');
	
		add_option("fcp_show_twitter_ico", '1');
		
		add_option("fcp_panel_width", '540px');
		
		add_option("fcp_links_color", '');
		
		add_option("fcp_body_bgcolor", '');
		
		add_option("fcp_body_text_color", '');
		
		add_option("fcp_body_text_size", '12px');
		
		add_option("fcp_commentator_name_color", '');
		
		add_option("fcp_commentator_name_size", '13px');
		
		add_option("fcp_comments_per_page", '10');
		
		add_option("fcp_theme", '1');
		
		add_option("fcp_header_box_bg_color", '');
		add_option("fcp_header_box_link_color", '');
		
		/// language
		add_option("fcp_language_most_recent", 'Most recent');
		add_option("fcp_language_most_liked", 'Most liked');
		add_option("fcp_language_all_comments", 'Show all comments');
		
		add_option("fcp_language_comments", 'Comments');
		add_option("fcp_language_like", 'Like');
		
		add_option("fcp_language_unlike", 'Unlike');
		add_option("fcp_language_people", 'people liked this');
		
		add_option("fcp_language_by_this_user", 'Comments By This User');
		add_option("fcp_language_tweet_this", 'Tweet This');
		add_option("fcp_language_more_records", 'More Records');
		
		add_option("fcp_load_effect", 'drop_effect');
		add_option("fcp_header_logo_link", '1');
	}
}

function fcp_script() 
{
	global $fcp_theme,$theme_like_ico;
	
	switch($fcp_theme){
		case 1:
			$css = "comment.css";
			$theme_like_ico = "like.png";
			 break;
		case 2:
			$css = "theme1.css";
			$theme_like_ico = "like-2.png";
			 break;
		case 3:
			$css = "theme2.css";
			$theme_like_ico = "like-3.png";
			 break;
		case 4:
			$css = "theme3.css";
			$theme_like_ico = "like-3.png";
			 break;
		case 5:
			$css = "theme4.css";
			$theme_like_ico = "like-2.png";
			 break;
		case 6:
			$css = "theme5.css";
			$theme_like_ico = "like-3.png";
			 break;
		case 7:
			$css = "theme6.css";
			$theme_like_ico = "like-3.png";
			 break;
		case 8:
			$css = "theme7.css";
			$theme_like_ico = "like-3.png";
			 break;
			 
		if(!$css)$css = "comment.css";
	}
	
	echo '<link rel="stylesheet" href="'.get_settings('siteurl').'/wp-content/plugins/facebook_comments_point/'.$css.'" type="text/css" media="screen" />';
	echo '<link rel="stylesheet" href="'.get_settings('siteurl').'/wp-content/plugins/facebook_comments_point/common.css" type="text/css" media="screen" />';
	echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/facebook_comments_point/jquery-1.2.6.min.js"></script>';
	
	echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/facebook_comments_point/jquery.livequery.js"></script>';
	
	echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/facebook_comments_point/comment.js"></script>';
	echo '<script type="text/javascript">var blogurl="'.get_settings("siteurl").'";</script>'; 
}

function fcp_insert_reply_id($id) 
{
	global $wpdb;
	
	$reply_id = mysql_escape_string($_REQUEST['reply_ID']);
	$query = $wpdb->query("UPDATE $wpdb->comments SET comment_parent='$reply_id' WHERE comment_ID='$id'");
}

function change_comments_template($file) 
{
	return ABSPATH . "/wp-content/plugins/facebook_comments_point/fcp_comments_temp.php";
}

/////////////

add_action('admin_menu', 'fcp_admin_menu');

function fcp_admin_menu() 
{
	add_options_page('Facebook Comments Point (FCP)', 'Facebook Comments Point (FCP)', 'manage_options', 'facebook-comments-point-fcp', 'fcp_admin_options');
}

function fcp_admin_options() 
{
	if (!current_user_can('manage_options'))  
	{
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	$fcp_show_user_post_ico = $_POST['fcp_show_user_post_ico'];
	$fcp_show_twitter_ico = $_POST['fcp_show_twitter_ico'];
	$fcp_panel_width = $_POST['fcp_panel_width'];
	
	$fcp_links_color = $_POST['fcp_links_color'];
	$fcp_body_bgcolor = $_POST['fcp_body_bgcolor'];
	$fcp_body_text_color = $_POST['fcp_body_text_color'];
	
	$fcp_body_text_size = $_POST['fcp_body_text_size'];
	$fcp_commentator_name_color = $_POST['fcp_commentator_name_color'];
	$fcp_commentator_name_size = $_POST['fcp_commentator_name_size'];
	$fcp_comments_per_page = $_POST['fcp_comments_per_page'];
	
	$fcp_language_most_recent = $_POST['fcp_language_most_recent'];
	$fcp_language_most_liked = $_POST['fcp_language_most_liked'];
	$fcp_language_all_comments = $_POST['fcp_language_all_comments'];
	
	$fcp_language_comments = $_POST['fcp_language_comments'];
	$fcp_language_like = $_POST['fcp_language_like'];
	$fcp_language_unlike = $_POST['fcp_language_unlike'];
	
	$fcp_language_people = $_POST['fcp_language_people'];
	$fcp_language_by_this_user = $_POST['fcp_language_by_this_user'];
	$fcp_language_tweet_this = $_POST['fcp_language_tweet_this'];
	
	$fcp_language_more_records = $_POST['fcp_language_more_records'];
	$fcp_load_effect = $_POST['fcp_load_effect'];
	
	$fcp_theme_form = $_POST['fcp_theme'];
	$fcp_header_logo_link = $_POST['fcp_header_logo_link'];
	// header box
	//$fcp_header_box_bg_color = $_POST['fcp_header_box_bg_color'];
	//$fcp_header_box_link_color = $_POST['fcp_header_box_link_color'];

    if (isset($_POST['info_update']))
    {
		update_option('fcp_show_user_post_ico', (string)$_POST['fcp_show_user_post_ico']);

		update_option('fcp_show_twitter_ico', (string)$_POST['fcp_show_twitter_ico']);

		update_option('fcp_panel_width', (string)$_POST['fcp_panel_width']);

		update_option('fcp_links_color', (string)$_POST['fcp_links_color']);
		update_option('fcp_comments_per_page', (string)$_POST['fcp_comments_per_page']);
		
		update_option('fcp_body_bgcolor', (string)$_POST['fcp_body_bgcolor']);
		update_option('fcp_body_text_color', (string)$_POST['fcp_body_text_color']);
		update_option('fcp_body_text_size', (string)$_POST['fcp_body_text_size']);
		update_option('fcp_commentator_name_color', (string)$_POST['fcp_commentator_name_color']);
		update_option('fcp_commentator_name_size', (string)$_POST['fcp_commentator_name_size']);
		
		//update_option('fcp_header_box_link_color', (string)$_POST['fcp_header_box_link_color']);
		//update_option('fcp_header_box_bg_color', (string)$_POST['fcp_header_box_bg_color']);
		
		// language options
		update_option("fcp_language_most_recent", (string)$_POST['fcp_language_most_recent']);
		update_option("fcp_language_most_liked", (string)$_POST['fcp_language_most_liked']);
		update_option("fcp_language_all_comments", (string)$_POST['fcp_language_all_comments']);
		
		update_option("fcp_language_comments", (string)$_POST['fcp_language_comments']);
		update_option("fcp_language_like", (string)$_POST['fcp_language_like']);
		update_option("fcp_language_unlike", (string)$_POST['fcp_language_unlike']);
		
		update_option("fcp_language_people", (string)$_POST['fcp_language_people']);
		update_option("fcp_language_by_this_user", (string)$_POST['fcp_language_by_this_user']);
		update_option("fcp_language_tweet_this", (string)$_POST['fcp_language_tweet_this']);
		
		update_option("fcp_language_more_records", (string)$_POST['fcp_language_more_records']);
		update_option("fcp_load_effect", (string)$_POST['fcp_load_effect']);
		update_option("fcp_header_logo_link", (int)$_POST['fcp_header_logo_link']);
		//
		
		$fcp_theme = get_option('fcp_theme');
		update_option('fcp_theme', (int)$_POST['fcp_theme']);
		
		if($fcp_theme!=$_POST['fcp_theme'])
		{
			update_option("fcp_show_user_post_ico", '1');
	
			update_option("fcp_show_twitter_ico", '1');
			
			update_option("fcp_panel_width", '580px');
			
			update_option("fcp_links_color", '');
			
			update_option("fcp_body_bgcolor", '');
			
			update_option("fcp_body_text_color", '');
			
			update_option("fcp_body_text_size", '12px');
			
			update_option("fcp_commentator_name_color", '');
			
			update_option("fcp_commentator_name_size", '13px');
			
			$fcp_theme_form = get_option('fcp_theme');
			
			//update_option('fcp_header_box_link_color', '');
			//update_option('fcp_header_box_bg_color', '');
		}

	} else

	{
		$fcp_comments_per_page = get_option('fcp_comments_per_page');
		
		$fcp_show_user_post_ico = get_option('fcp_show_user_post_ico');

		$fcp_show_twitter_ico = get_option('fcp_show_twitter_ico');
	
		$fcp_panel_width = get_option('fcp_panel_width');
	
		$fcp_links_color = get_option('fcp_links_color');
	
		$fcp_body_bgcolor = get_option('fcp_body_bgcolor');
	
		$fcp_body_text_color = get_option('fcp_body_text_color');
	
		$fcp_body_text_size = get_option('fcp_body_text_size');
		
		$fcp_commentator_name_color = get_option('fcp_commentator_name_color');
		
		$fcp_commentator_name_size = get_option('fcp_commentator_name_size');
		
		$fcp_theme_form = get_option('fcp_theme');
		
		//$fcp_header_box_bg_color = get_option('fcp_header_box_bg_color');
		//$fcp_header_box_link_color = get_option('fcp_header_box_link_color');
		
		$fcp_language_most_recent = get_option('fcp_language_most_recent');
		$fcp_language_most_liked = get_option('fcp_language_most_liked');
		$fcp_language_all_comments = get_option('fcp_language_all_comments');
		
		$fcp_language_comments = get_option('fcp_language_comments');
		$fcp_language_like = get_option('fcp_language_like');
		$fcp_language_unlike = get_option('fcp_language_unlike');
		
		$fcp_language_people = get_option('fcp_language_people');
		$fcp_language_by_this_user = get_option('fcp_language_by_this_user');
		$fcp_language_tweet_this = get_option('fcp_language_tweet_this');
		
		$fcp_language_more_records = get_option('fcp_language_more_records');
		
		$fcp_load_effect = get_option('fcp_load_effect');
		$fcp_header_logo_link = get_option('fcp_header_logo_link');
	}
	?>
	
	<div class=wrap>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

    <input type="hidden" name="info_update" id="info_update" value="true" />



   <u><h2>Facebook Comments Point (FCP) | Facebook style Ajax comments plugin for Wordpress</h2></u>



	<p>
<iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://www.facebook.com/plugins/like.php?href=http://goo.gl/ZSuFB/&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light" style="border: medium none; margin-top: 2px; overflow: hidden; width: 80px; height: 21px;"></iframe>

	</p>



	<div id="poststuff" style="height:700px;" class="metabox-holder has-right-sidebar">



		<div style="float:left;width:60%;">



			<div class="postbox">

				<h3>FCP - Options</h3>

					<div>

					<table class="form-table">
					
					<tr valign="top" class="alternate">

							<th scope="row"><label>Show/Hide Icons</label></th>

						<td colspan="2">
							<div style="float:left">
							 <input name="fcp_show_twitter_ico" type="checkbox"<?php if(get_option('fcp_show_twitter_ico')=='1') echo 'checked="checked"'; ?> value="1" /> &nbsp;&nbsp;Show twitter share icon.</div>
							
							<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/tw.png' style="margin-top:1px; margin-left:6px;float:left;" alt='' />
							<br clear="all" />
							<div style="float:left">
							 <input name="fcp_show_user_post_ico" type="checkbox"<?php if(get_option('fcp_show_user_post_ico')=='1') echo 'checked="checked"'; ?> value="1" /> &nbsp;&nbsp;Show User All Comments share icon.</div>
							
							<img src='<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/img/fcp-user.png' style="margin-top:1px; width:20px; margin-left:6px;float:left;" alt='' />						</td>
					</tr>
					
					

					<tr valign="top"  class="alternate">

						<th scope="row">Customize Style Options</th>

						<td colspan="2">
							

						<input name="fcp_panel_width" type="text" size="10" value="<?php echo get_option('fcp_panel_width'); ?>" /> Panel width with px, semi-colon not allowed <code>540px</code>

						<br>

						<input name="fcp_links_color" type="text" size="10" value="<?php echo get_option('fcp_links_color'); ?>" /> Actions links color (Comment,Like,Unlike) <code>#3B5998</code>

						<br>

						<input name="fcp_body_bgcolor" type="text" size="10" value="<?php echo get_option('fcp_body_bgcolor'); ?>" /> Body background color <code>#EDEFF4</code>

						<br>

						<input name="fcp_body_text_color" type="text" size="10" value="<?php echo get_option('fcp_body_text_color'); ?>" /> Body text color <code>#333333
						</code>
						
						<br>

						<input name="fcp_body_text_size" type="text" size="10" value="<?php echo get_option('fcp_body_text_size'); ?>" /> Body text size with px<code>12px
						</code>

						
						<br>

						<input name="fcp_commentator_name_color" type="text" size="10" value="<?php echo get_option('fcp_commentator_name_color'); ?>" /> Commentator name color <code>#3B5998
						</code>
						
						<br>

						<input name="fcp_commentator_name_size" type="text" size="10" value="<?php echo get_option('fcp_commentator_name_size'); ?>" /> Commentator name text size <code>13px
						</code>
						
						<br>

						<input name="fcp_comments_per_page" type="text" size="10" value="<?php echo get_option('fcp_comments_per_page'); ?>" /> Commentator per Page <code>10
						</code>		
						</td>
					</tr>
					
					
					<tr valign="top" >

					  <th width="186" scope="row"><label>Themes</label></th>

						<td width="200">
							<label>
							 <input name="fcp_theme" type="radio" value="1" <?php checked('1', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 1 <code>Default</code></label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme8.jpg" width="160" alt="" />	
							 </label>	    
					    </td>
						<td width="181">
							<label>
							 <input name="fcp_theme" type="radio" value="2" <?php checked('2', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 2</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme1.jpg" width="160" alt="" />	
							 </label>	
						</td>
						
					</tr>
					<tr valign="top" class="alternate">

					  <th width="186" scope="row"></th>

						<td width="200">
							<label>
							 <input name="fcp_theme" type="radio" value="3" <?php checked('3', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 3</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme2.jpg" width="160" alt="" />	
							 </label>	    
					    </td>
						<td width="181">
							<label>
							 <input name="fcp_theme" type="radio" value="4" <?php checked('4', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 4</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme3.jpg" width="160" alt="" />	
							 </label>	
						</td>
						
					</tr>
					
					<tr valign="top">

					  <th width="186" scope="row"></th>

						<td width="200">
							<label>
							 <input name="fcp_theme" type="radio" value="5" <?php checked('5', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 5</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme4.jpg" width="160" alt="" />	
							 </label>	    
					    </td>
						<td width="181">
							<label>
							 <input name="fcp_theme" type="radio" value="6" <?php checked('6', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 6</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme5.jpg" width="160" alt="" />	
							 </label>	
						</td>
						
					</tr>
					<tr valign="top" class="alternate">

					  <th width="186" scope="row"></th>

						<td width="200">
							<label>
							 <input name="fcp_theme" type="radio" value="7" <?php checked('7', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 7</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme6.jpg" width="160" alt="" />	
							 </label>	    
					    </td>
						<td width="181">
							<label>
							 <input name="fcp_theme" type="radio" value="8" <?php checked('8', $fcp_theme_form); ?> />
							 &nbsp;&nbsp;<label style="padding-top:12px;">Theme 8</label>	
							 <img src="<?php echo bloginfo('siteurl')?>/wp-content/plugins/facebook_comments_point/themes-img/theme7.jpg" width="160" alt="" />	
							 </label>	
						</td>
						
					</tr>
					
					<tr valign="top">
					
					 <th width="186" scope="row"><label>Language Options</label></th>
					 
					 <td colspan="2">
					 
						<input name="fcp_language_most_recent" type="text" size="25" value="<?php echo get_option('fcp_language_most_recent'); ?>" /> Most Recent 
						<br>
						<input name="fcp_language_most_liked" type="text" size="25" value="<?php echo get_option('fcp_language_most_liked'); ?>" /> Most Liked 
						<br>
						<input name="fcp_language_all_comments" type="text" size="25" value="<?php echo get_option('fcp_language_all_comments'); ?>" /> Show all comments  
						
						<br>
						<input name="fcp_language_comments" type="text" size="25" value="<?php echo get_option('fcp_language_comments'); ?>" /> Comment
						<br>
						<input name="fcp_language_like" type="text" size="25" value="<?php echo get_option('fcp_language_like'); ?>" /> Like
						<br>
						<input name="fcp_language_unlike" type="text" size="25" value="<?php echo get_option('fcp_language_unlike'); ?>" /> Unlike
						
						<br>
						<input name="fcp_language_people" type="text" size="25" value="<?php echo get_option('fcp_language_people'); ?>" /> people liked this
						<br>
						<input name="fcp_language_by_this_user" type="text" size="25" value="<?php echo get_option('fcp_language_by_this_user'); ?>" /> Comments By This User
						<br>
						<input name="fcp_language_tweet_this" type="text" size="25" value="<?php echo get_option('fcp_language_tweet_this'); ?>" /> Tweet This
						
						<br>
						<input name="fcp_language_more_records" type="text" size="25" value="<?php echo get_option('fcp_language_more_records'); ?>" /> More Records (paging)
						
						</td>
					</tr>
					
					<tr valign="top" class="alternate">
					
						 <th width="186" scope="row"><label>General</label></th>
						 
						 <td colspan="2">
						 	<input name="fcp_header_logo_link" type="checkbox"<?php if(get_option('fcp_header_logo_link')=='1') echo 'checked="checked"'; ?> value="1" /> &nbsp;&nbsp;Linked header FCP logo to plugin home page.
						 </td>
					</tr>
					</table>

				</div>
			</div>

			<div class="submit">

					<input type="submit" name="option_value" class="button-primary" value="<?php _e( 'Update Options' ); ?>" />

			</div>

		</form>

	</div>

			<div id="side-info-column" class="inner-sidebar">

				<div class="postbox">

				  <h3 class="hndle"><span>WP FCP - Likes</span></h3>

				  <div class="inside">

					<ul>

					<li><a href="http://99points.info/plugins/facebook_comments_point_wp_plugin/" title="facebook_comments_point_wp_plugin" target="_blank">Plugin Homepage</a></li>

					<li>

<iframe scrolling="no" frameborder="0" allowtransparency="true" src="http://www.facebook.com/plugins/like.php?href=http://goo.gl/ZSuFB/&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light" style="border: medium none; margin-top: 2px; overflow: hidden; width: 80px; height: 21px;"></iframe>

					</li>
					
					<li>
						<br />
						Please donate, if you like it.<br />
						<div align="left">
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="432QTC8HRCJBQ">
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
					</div>
						

					</li>
					
					</ul>

				  </div>

				</div>

		     </div>

			<br>
			<div id="side-info-column" class="inner-sidebar">

				<div class="postbox">

				  <h3 class="hndle"><span>Information</span></h3>

				  <div class="inside">

					<ul>
					<li> <a href="http://www.99Points.info/hire-me" title="Need a WordPress Expert?" target="_blank">Need a WordPress Expert?</a></li>
					<li> <a href="http://www.99Points.info" title="99Points" target="_blank">www.99Points.info</a></li>

					</ul>

				  </div>

				</div>

		     </div>

	
	<?php
}
///////////

register_activation_hook(__FILE__,'fcp_install');

add_action('wp_head','fcp_script'); 

add_action('comment_post','fcp_insert_reply_id');

add_filter('comments_template', change_comments_template);

?>