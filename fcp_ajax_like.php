<?php 

include("../../../wp-config.php");
global $nested,$comments_holder, $total_comments, $comments_per_page, $id,$wpdb;

$id 	= (int)$_REQUEST['id'];
$type 	= mysql_real_escape_string($_REQUEST['type']);
$ip = $_SERVER['REMOTE_ADDR'];
if($type=='like')
{
	$wpdb->query("insert into ".$wpdb->prefix."fcp_likes_ip (userip,comment_ID) values ('$ip','$id')");
	$comments = $wpdb->query("update $wpdb->comments set fcp_likes_count=fcp_likes_count+1 WHERE comment_ID = '$id' AND comment_approved = '1'");
	
	$fcp_likes_count = $wpdb->get_var("select fcp_likes_count from $wpdb->comments WHERE comment_ID = '$id'");
	echo $fcp_likes_count;
}
elseif($type=='unlike')
{
	$wpdb->query("delete from ".$wpdb->prefix."fcp_likes_ip where userip = '$ip' and comment_ID = '$id'");
	$comments = $wpdb->query("update $wpdb->comments set fcp_likes_count=fcp_likes_count-1 WHERE comment_ID = '$id' AND comment_approved = '1'");
	
	$fcp_likes_count = $wpdb->get_var("select fcp_likes_count from $wpdb->comments WHERE comment_ID = '$id'");
	echo $fcp_likes_count;
}
elseif($type=='recent')
{
$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = '$id' AND comment_approved = '1' ORDER BY comment_date desc limit 50");
}



?>