<?php
load_plugin_textdomain('YouTubeVideoInserter');

function getYTID($postid)
	{
		$keyvalues = get_post_custom_values('yt_insert_YTID', $postid);
		foreach ( $keyvalues as $key => $value ) {
			return $value; 
		  }
	}
	
	function auto_link_text($text)
	{
	  $pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9.,_\/~#&=:;%+?-]+[a-z0-9\/#=?]{1,1})/is";
	  $text = preg_replace($pattern, " <a href=\"$1\" target=\"_blank\">$1</a>", $text);
	  // fix URLs without protocols
	  $text = preg_replace("/href='www/", "href='http://www", $text);
	  return $text;
	}

if(!isset($_GET['CID']))
	{
		?>
        <p>
        <h2><?php _e( 'You do not select a video', 'YouTubeVideoInserter' ); ?></h2>
        <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a>
        </p>
        <?php
	}
	elseif($_GET['type'] == "edit")
	{
		$post = get_post($_GET['CID']);
		$title = $post->post_title;
		if(isset($_POST['updateDetails']))
		{
			$content = $_POST['content'];
			$titleUD = $_POST['post_title'];
			$post = array();
			$post['ID'] = $_GET['CID'];
			$post['post_title'] = $titleUD;
			$post['post_content'] = $content;
			$post['post_category'] = array($_POST['VidCat']);
			wp_update_post( $post );
			?>
            <h2><i><?php echo $title; ?></i> <?php _e( 'edited', 'YouTubeVideoInserter' ); ?></h2>
            <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a><br><br>
            <?php
		}
		else
		{
			?>
            <h2><?php _e( 'Edit YouTube Video', 'YouTubeVideoInserter' ); ?>: <?php echo $title; ?></h2>
            <?php
		}
		$post2 = get_post($_GET['CID']);
		$title = $post2->post_title;
		$content = $post2->post_content;
	?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=editorMenu&CID=<?php echo $_GET['CID']; ?>&type=edit" method="post">
    <div id="changeArea">
    	<input type="text" name="post_title" size="30" value="<?php echo $title; ?>" id="title" autocomplete="off" style="padding: 3px 8px; font-size: 1.7em; line-height: 100%; height: 1.7em; width: 100%; outline: 0; margin: 0;">
        <?php wp_editor($content,'content');?>
    </div>
    <p>
    <?php _e( 'Category', 'YouTubeVideoInserter' ); ?>: <select name="VidCat">
    	<?php
		//Alle Kategorien auflisten
		$catIDs = get_all_category_ids();
		foreach($catIDs as $catID)
		{
			$catName = get_cat_name($catID);
			if($post2->post_category[0] == $catID)
			{
				echo '<option value="' .$catID .'" selected>'.$catName .'</option>';
			}
			else
			{
				echo '<option value="' .$catID .'">'.$catName .'</option>';
			}
		}
		?>
    </select>
    </p>
    <p><input type="submit" name="updateDetails" value="   Edit video description   " class="button button-primary button-large"/></p>
    </form>
    <?php
	}
	elseif($_GET['type'] == "publish")
	{
		wp_publish_post( $_GET['CID'] );
		$post = get_post($_GET['CID']); 
		$title = $post->post_title;
		?>
        	<h2><i><?php echo $title; ?></i> <?php _e( 'is now public', 'YouTubeVideoInserter' ); ?></h2>
            <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a>
        <?php
	}
	elseif($_GET['type'] == "delete")
	{
		$post = get_post($_GET['CID']); 
		$title = $post->post_title;
		wp_delete_post( $_GET['CID'] );
		?>
        	<h2><i><?php echo $title; ?></i> <?php _e( 'delete', 'YouTubeVideoInserter' ); ?></h2>
            <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a>
        <?php
	}
	elseif($_GET['type'] == "updateData") {
		$v = getYTID($_GET['CID']);
		$id = $_GET['CID'];
		
		$youtubeXML = simplexml_load_string(file_get_contents('http://gdata.youtube.com/feeds/api/videos/' .$v));
		$yttitle = $youtubeXML->title;
		$ytdesc = auto_link_text(nl2br($youtubeXML->content));
		$ytauthor = $youtubeXML->author->name;
		$ytauthororiginalname = str_replace("http://gdata.youtube.com/feeds/api/users/", "", $youtubeXML->author->uri);
		$ytplayer = "<object width='100%' height='356' id='ytInserterVideo'><param name='movie' value='http://www.youtube.com/v/{$v}?fs=1&amp;hl=de_DE&amp;hd=1'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/{$v}?fs=1&amp;hl=de_DE&amp;hd=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='100%' height='100%'></embed></object>";
		
		$ytSubscriptionBtn = '<script src="https://apis.google.com/js/platform.js"></script> <div class="g-ytsubscribe" data-channel="' .$ytauthororiginalname .'" data-layout="default" data-count="default"></div>';
		
		$dbContent = "<div id='ytInserterVideo' style='width:100%; height:500px;'>" .$ytplayer ."</div><br><br><p>Von: " .$ytauthor .$ytSubscriptionBtn ."<br>" .$ytdesc ."</p>";
		
		$post = array();
		$post['ID'] = $id;
		$post['post_content'] = $dbContent;
		$post['post_title'] = $yttitle;
		
		wp_update_post( $post );
			
		?><h2><?php _e( 'Video updated', 'YouTubeVideoInserter' );?></h2>
        <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a><?php	
	}
	else
	{
		?>
        <p>
        <h2><?php _e( 'You do not select a video', 'YouTubeVideoInserter' ); ?></h2>
        <a href="admin.php?page=youtube-video-inserter/index.php"><?php _e( 'Back to overview', 'YouTubeVideoInserter' ); ?></a>
        </p>
        <?php
	}
?>
