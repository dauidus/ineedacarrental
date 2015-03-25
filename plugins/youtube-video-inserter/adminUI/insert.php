<?php
load_plugin_textdomain('YouTubeVideoInserter');

/**
 * Replace links in text with html links
 *
 * @param  string $text
 * @return string
 */
function auto_link_text($text)
{
  $pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9.,_\/~#&=:;%+?-]+[a-z0-9\/#=?]{1,1})/is";
  $text = preg_replace($pattern, " <a href=\"$1\" target=\"_blank\">$1</a>", $text);
  // fix URLs without protocols
  $text = preg_replace("/href='www/", "href='http://www", $text);
  return $text;
}

if(isset($_POST['insertVid']) && isset($_POST['vidURL']) && str_replace(" ", "", $_POST['vidURL']) != "")
{
	$yturl = $_POST['vidURL'];
	//Zerlegen der URL in Bestandteile
	$array = parse_url($yturl);	
	//Benennung des Query (GET Teil der URL) 
	$query = $array[query];
	//GET auf der $query Variable
	parse_str($query);
	if(isset($v))
	{
		$youtubeXML = simplexml_load_string(file_get_contents('http://gdata.youtube.com/feeds/api/videos/' .$v));
		$yttitle = $youtubeXML->title;
		$ytdesc = auto_link_text(nl2br($youtubeXML->content));
		$ytauthor = $youtubeXML->author->name;
		$ytauthororiginalname = str_replace("http://gdata.youtube.com/feeds/api/users/", "", $youtubeXML->author->uri);
		$ytplayer = "<object width='100%' height='356' id='ytInserterVideo'><param name='movie' value='http://www.youtube.com/v/{$v}?fs=1&amp;hl=de_DE&amp;hd=1'></param><param name='allowFullScreen' value='true'></param><param name='allowscriptaccess' value='always'></param><embed src='http://www.youtube.com/v/{$v}?fs=1&amp;hl=de_DE&amp;hd=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='100%' height='100%'></embed></object>";
		
		$ytSubscriptionBtn = '<script src="https://apis.google.com/js/platform.js"></script> <div class="g-ytsubscribe" data-channel="' .$ytauthororiginalname .'" data-layout="default" data-count="default"></div>';
		
		$dbContent = "<div id='ytInserterVideo' style='width:100%; height:500px;'>" .$ytplayer ."</div><br><br><p>Von: " .$ytauthor .$ytSubscriptionBtn ."<br>" .$ytdesc ."</p>";
		
		$user_ID = get_current_user_id();
			$my_post = array(
		  'post_title'    => wp_strip_all_tags( $yttitle ),
		  'post_content'  => nl2br($dbContent),
		  'post_status'   => $_POST['vidStatus'],
		  'post_author'   => $user_ID,
		  'comment_status' => 'open',
		  'post_category' => array($_POST['Vidcat']),
		  'post_type' => 'ytvideo'
		);
		
		//  Einfügen in die Datenabk
		$postID = wp_insert_post( $my_post );
		//YouTube ID in Datenbank speichern
		add_post_meta($postID, 'yt_insert_YTID', $v);
		echo '<p class="okay">' .__( 'Video is now inserted', 'YouTubeVideoInserter' ) .'. <a href="admin.php?page=editorMenu&CID=' .$postID .'&type=edit">' .__( 'Edit video description', 'YouTubeVideoInserter' ) .'</a></p>';
	}
	else
	{
		echo  '<p class="error">' .__( 'No valid YouTube URL', 'YouTubeVideoInserter' ) .'</p>';	
	}
}
?>
<h2><?php _e( 'Insert video', 'YouTubeVideoInserter' ); ?></h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=insertMenu" method="post"/>
<table border="0" cellpadding="3">
	<tr><td><?php _e( 'The URL of the Video', 'YouTubeVideoInserter' ); ?>:</td><td><input type="text" name="vidURL" class="inputText"/></td></tr>
    <tr>
    	<td><?php _e( 'Category', 'YouTubeVideoInserter' ); ?>:</td>
        <td>
            <select class="inputSelect" name="Vidcat">
            <?php
            //Alle Kategorien auflisten
            $catIDs = get_all_category_ids();
            foreach($catIDs as $catID)
            {
                $catName = get_cat_name($catID);
                echo '<option value="' .$catID .'">'.$catName .'</option>';
            }
            ?>
            </select>
		</td>
	</tr>
    <tr>
    	<td><?php _e( 'Status', 'YouTubeVideoInserter' ); ?>:</td> 
        <td>
            <select class="inputSelect" name="vidStatus">
                <option value="draft"><?php _e( 'Draft', 'YouTubeVideoInserter' ); ?></option>
                <option value="publish"><?php _e( 'Public', 'YouTubeVideoInserter' ); ?></option>
            </select>
       	</td>
	</tr>
<tr><td><input type="submit" name="insertVid" value="   <?php _e( 'Insert video', 'YouTubeVideoInserter' ); ?>   " class="button button-primary button-large"/></td></tr>
</table>
</form>