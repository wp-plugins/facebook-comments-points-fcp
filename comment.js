$j=jQuery.noConflict();

jQuery(document).ready(function(){
	
	$j('#reply_ID').val(0);
	
	$j('.fcpall').livequery('click', function(){
		
		$j('.fcp-header-loader').show();
		$j('#fcp_comments_textarea').val('');
		changeFormPosition(0);
		
		var puid =  $j(this).parent().attr('id').replace('collapsed-','');	
		$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_fetching.php?id="+puid+"&type=all", {
	
			}, function(response){
				
				$j(".fcp-comments-box .fcp-comments-inner-container").html(response);
				//loadFCPresult(0);
				//$j(".fcp-comments-box .fcp-comments-inner-container").html($j(response).fadeIn('slow'));	
				$j('.fcp-header-loader').hide();
			});
	});
	
	
	$j('.fcpliked').livequery('click', function(){
		
		$j('.fcp-header-loader').show();
		$j('#fcp_comments_textarea').val('');
		changeFormPosition(0);
		
		var puid =  $j(this).parent().attr('id').replace('liked-','');	
		$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_fetching.php?id="+puid+"&type=liked", {
	
			}, function(response){
				
				$j(".fcp-comments-box .fcp-comments-inner-container").html($j(response).fadeIn('slow'));		
				$j('.fcp-header-loader').hide();
			});
	});
	
	$j('.fcpnew').livequery('click', function(){
		
		$j('.fcp-header-loader').show();
		$j('#fcp_comments_textarea').val('');
		changeFormPosition(0);
				
		var puid =  $j(this).parent().attr('id').replace('recent-','');	
		$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_fetching.php?id="+puid+"&type=recent", {
	
			}, function(response){
				
				$j(".fcp-comments-box .fcp-comments-inner-container").html($j(response).fadeIn('slow'));		
				$j('.fcp-header-loader').hide();
			});
	});
	
	$j('#fcpCommentForm').submit(function(){
										   		
		var reply_ID				= encodeURIComponent($j('#reply_ID').val());
		var fcp_comments_textarea	= encodeURIComponent($j('#fcp_comments_textarea').val());
		
		var author	= encodeURIComponent($j('#author').val());
		var email	= encodeURIComponent($j('#email').val());
		
		if(author)
		{$j("#author").css("border", "1px solid #666");}
		
		if(fcp_comments_textarea)
		{$j("#fcp_comments_textarea").css("border", "1px solid #666");}
		
		if(fcp_comments_textarea == "" || ( (author == "" || email == "")))
		{
			if(author ==""){  $j("#author").focus();$j("#author").css("border", "1px solid red"); }
			else if(email==""){$j("#email").focus();$j("#email").css("border", "1px solid red");}
			else{$j("#fcp_comments_textarea").focus();$j("#fcp_comments_textarea").css("border", "1px solid red");}
			return false;
		}
		else if(email)
		{
			 var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;	
			 if(reg.test(email) == false) 
			 {
				 $j("#email").focus(); $j("#email").css("border", "1px solid red");
			 }
		}
		
		if(email)
		{$j("#email").css("border", "1px solid #666");}
		
		fcp_comments_textarea = fcp_comments_textarea.replace(/\r\n\r\n/g, "</p><p>");
		fcp_comments_textarea = fcp_comments_textarea.replace(/\r\n/g, "<br />");
		fcp_comments_textarea = fcp_comments_textarea.replace(/\n\n/g, "</p><p>");
		fcp_comments_textarea = fcp_comments_textarea.replace(/\n/g, "<br />");
		
		$j.ajax({
	
			type: 'post',
	
			url:  blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_submit_ajax_comments.php",
	
			data: $j('#fcpCommentForm').serialize(),
	
			beforeSend: function(){
				
				$j('.fcp-buttons').hide();
				$j('.fcp-form-loader').fadeIn();
	
			},
			success: function(res){
				
				var param = $j('#reply_ID').val();
				if(param == 0)
				{
					$j('#fcp-commment-form-div').before(res);
				}
				else
				{
					var current_li = $j('#comment-'+param);
					$j('#comment-'+param+' #fcp-commment-form-div').before(res);
					
					$j('.fcp-form-loader').hide();
					$j('.fcp-buttons').fadeIn();
				}
				
				
	 			$j('#fcp_comments_textarea').val('');
				changeFormPosition(0);
			}
		});
	
	   return false;
	});

});

function fCpLike(comment_ID)
{
	$j('.fcp-like-click-'+comment_ID).hide();
	$j('.fcp-comment-loader-'+comment_ID).show();
	
	$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_like.php?id="+comment_ID+"&type=like", {
	
	}, function(response){
		
		$j('.fcp-comment-loader-'+comment_ID).hide();
		$j('.fcp-unlike-click-'+comment_ID).fadeIn();
		
		$j(".fcp-likes-stat-"+comment_ID).html(escape(response));
		
	});
}

function fCpUnLike(comment_ID)
{
	$j('.fcp-unlike-click-'+comment_ID).hide();
	$j('.fcp-comment-loader-'+comment_ID).show();
	
	$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_like.php?id="+comment_ID+"&type=unlike", {
	
	}, function(response){
		
		$j('.fcp-comment-loader-'+comment_ID).hide();
		$j('.fcp-like-click-'+comment_ID).fadeIn();
		
		$j(".fcp-likes-stat-"+comment_ID).html(escape(response));
		
	});
}

function fcpUserCom (comment_ID,Pid)
{
	$j('#fcp-user-comm-logo-'+comment_ID).hide();
	$j('.fcp-this-user-loader'+comment_ID).show();
	
	changeFormPosition(0);
	
	$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_fetching.php?id="+comment_ID+"&type=userpost&pid="+Pid, {

		}, function(response){
			
			$j(".fcp-comments-box .fcp-comments-inner-container").html(response);
			$j('.fcp-this-user-loader'+comment_ID).hide();
		});
}

function changeFormPosition(param)
{
	$j('#fcp_comments_textarea').val('');
	if(param == 0)
	{
		$j('#reply_ID').val(0);
		$j('#fcp-cancel').hide();
		$j('.fcp-comments-inner-container').after($j('#fcp-commment-form-div').fadeIn());
	}
	else
	{
		var current_li = $j('#comment-'+param+' div:first');
		$j('#reply_ID').val(param);
		$j('#fcp-commment-form-div').hide();
		
		current_li.append($j('#fcp-commment-form-div').show());
		$j('#fcp-cancel').fadeIn();
	}
}

$j('a#fcp_next_rec').livequery('click', function(){
	
	var next = $j('#fcp_paging_id').val();
	var pid = $j('#fcp_paging_pid').val();
	$j('#fcp_next_rec').hide();
	$j('.fcp-paging-loader').show();
	changeFormPosition(0);
	
	$j.post( blogurl+"/wp-content/plugins/facebook-comments-points-fcp/fcp_ajax_fetching.php?id="+pid+"&type=paging&next="+next, {

		}, function(response){
			
			$j(".fcp-comments-box .fcp-comments-inner-container .fcp-paging-wrap-"+next).html($j(response).fadeIn('slow'));		
			$j('.fcp-paging-loader').hide();
		});
});



