<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Template Name: Rates Page
 *
 * This template is a full-width version of the page.php template file. It removes the sidebar area.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
?>
       
    <div id="content" class="page col-full">
    
    	<?php woo_main_before(); ?>
    	
		<section id="main" class="fullwidth">
			<center>
				<div style="margin:0 -22px;">
				
					<a href="<?php bloginfo('url'); ?>/rate/summer-rates-june/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rates/summerrates_june.gif" title="Summer Rates - June"></a> 
					<a href="<?php bloginfo('url'); ?>/rate/summer-rates-july/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rates/summerrates_july2.gif" title="Summer Rates - July"></a> 
					<a href="<?php bloginfo('url'); ?>/rate/summer-rates-august/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rates/summerrates_august2.gif" title="Summer Rates - August"></a> 
					<a href="<?php bloginfo('url'); ?>/rate/winter-rates-september-may/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rates/winterrates.gif" title="Winter Rates"></a> 
				
				</div>
			</center>
			<br>
           
	        <?php
	        	if ( have_posts() ) { $count = 0;
	        		while ( have_posts() ) { the_post(); $count++;
	        ?>                                                             
	                <article <?php post_class(); ?>>
						<div class="article-inner">
		                			                	
	                	<?php $now = new DateTime();
						$month = (int)$now->format("m");
						
							if ($month == '6') {
								$page_query = new WP_Query(array('post_type' => 'rate', 'post__in' => array(32)));
								$page_query->the_post();
									echo '<header><h1>';
									echo the_title();
									echo '</h1></header>';
									echo '<section class="entry">';
									the_content();
								wp_reset_postdata();
							}
							elseif ($month == '7') {
								$page_query = new WP_Query(array('post_type' => 'rate', 'post__in' => array(34)));
								$page_query->the_post();
									echo '<header><h1>';
									echo the_title();
									echo '</h1></header>';
									echo '<section class="entry">';
									the_content();
								wp_reset_postdata();
							}
							elseif ($month == '8') {
								$page_query = new WP_Query(array('post_type' => 'rate', 'post__in' => array(36)));
								$page_query->the_post();
									echo '<header><h1>';
									echo the_title();
									echo '</h1></header>';
									echo '<section class="entry">';
									the_content();
								wp_reset_postdata();
							}
							else {
								$page_query = new WP_Query(array('post_type' => 'rate', 'post__in' => array(38)));
								$page_query->the_post();
									echo '<header><h1>';
									echo the_title();
									echo '</h1></header>';
									echo '<section class="entry">';
									the_content();
								wp_reset_postdata();
							}
						?>
		                	

		                	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
			               	</section><!-- /.entry -->
	
						</div><!-- /.article-inner -->
	                </article><!-- /.post -->
                                                    
				<?php
						} // End WHILE Loop
					} else {
				?>
				<article <?php post_class(); ?>>
                	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
                </article><!-- /.post -->
            <?php } ?>  
        
		</section><!-- /#main -->
		
		<?php woo_main_after(); ?>
		
    </div><!-- /#content -->
		
<?php get_footer(); ?>