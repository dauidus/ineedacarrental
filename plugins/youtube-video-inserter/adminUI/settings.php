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
?>
<h2><?php _e( 'Settings', 'YouTubeVideoInserter' ); ?></h2>
<h3><?php _e( 'Creat a category', 'YouTubeVideoInserter' ); ?></h3>
<?php
if(isset($_POST['insertCat']) && isset($_POST['catname']) && str_replace(" ", "", $_POST['catname']) != "" && isset($_POST['catdesc']) && str_replace(" ", "", $_POST['catdesc']) != "")
{
	if(get_cat_ID($_POST['catname']) == 0)
	{
		$my_cat = array('cat_name' => $_POST['catname'], 'category_description' => $_POST['catdesc'], 'category_nicename' => strtolower($_POST['catname']), 'category_parent' => $_POST['catparent']);
		wp_insert_category($my_cat);
		echo __( 'Creation sucssessful', 'YouTubeVideoInserter' );
	}
	else
	{
		echo  __( 'The category already exist', 'YouTubeVideoInserter' );	
	}
} else if(isset($_POST['updateDesc'])) {
	global $post;
    $args = array('post_type' => 'ytvideo', 'numberposts' => -1, 'post_status' => 'publish,draft', 'sort_order' => 'DESC', 'sort_column' => 'ID',);
    $myposts = get_posts( $args );
	$lastDate = null;
    foreach( $myposts as $post ) :	setup_postdata($post); 
        $v = getYTID(get_the_ID());
		$id = get_the_ID();
		
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
		
	endforeach;
	_e( 'All videos updated.', 'YouTubeVideoInserter' );
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=settingMenu" method="post"/>
<table border="0" cellpadding="3">
<tr><td><?php _e( 'Name of category', 'YouTubeVideoInserter' ); ?>:</td><td><input type="text" name="catname" class="inputText"/></td></tr>
<tr><td><?php _e( 'Description', 'YouTubeVideoInserter' ); ?>:</td><td><input type="text" name="catdesc" class="inputText"/></td></tr>
<tr><td><?php _e( 'Subcategory from', 'YouTubeVideoInserter' ); ?>:</td>
<td>
    <select class="inputSelect" name="catparent">
    <option value=""><?php _e( 'New main category', 'YouTubeVideoInserter' ); ?></option>
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
</td></tr>
<tr><td><input type="submit" name="insertCat" value="   <?php _e( 'Creat Category', 'YouTubeVideoInserter' ); ?>   " class="button button-primary button-large"/></td><td></td></tr>
</table>
</form>
<h3><?php _e( 'Update all descriptions', 'YouTubeVideoInserter' ); ?></h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=settingMenu" method="post"/>
	<p><?php _e( 'If you click the button, all custom change will delete.', 'YouTubeVideoInserter' ); ?></p>
	<p><input type="submit" name="updateDesc" value="   <?php _e( 'Update all', 'YouTubeVideoInserter' ); ?>   " class="button button-primary button-large"/></p>
</form>
<hr/>
<h2>Shortcodes</h2>
<table width="600" border="1" rules="all" cellpadding="5">
<tr><th>Shortcode</th><th><?php _e( 'Description', 'YouTubeVideoInserter' ); ?></th></tr>
<tr><td width="150">[YTLastVideos count="x"]</td><td><?php _e( 'Output the last videos. x is the number of them. <br>
Default: 10', 'YouTubeVideoInserter' ); ?></td></tr>
<tr><td width="150">[yt_Inserter_Single_Video id="x"]</td><td><?php _e('Output a video without title and description. 
<br>Default: 0 (Last Post (Not only videos!))
<br>For a special video, look in the video overview.', 'YouTubeVideoInserter' ); ?>
</td></tr>
</table>
<hr/>