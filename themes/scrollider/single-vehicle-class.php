<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php
/**
 * Single Post Template
 *
 * This template is the default page template. It is used to display content when someone is viewing a
 * singular view of a post ('post' post_type).
 * @link http://codex.wordpress.org/Post_Types#Post
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();
	global $woo_options;
	
/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
?>
       
    <div id="content" class="col-full">
    
    	<?php woo_main_before(); ?>
    	
		<section id="main" class="fullwidth">
		           
        <?php
        	if ( have_posts() ) { $count = 0;
        		while ( have_posts() ) { the_post(); $count++;
        ?>
			<article <?php post_class(); ?>>

                <div class="article-inner">

                	<header>
						<h1>Vehicle Class: <?php the_title(); ?></h1>
					</header>

	                <section class="entry fix">
	                	
	                	<div class="vehicle-class-right">

							<span id="model">
								<?php 
								echo(types_render_field("vehicle-model", array("arg1"=>"val1","arg2"=>"val2"))); 
								echo '<br><em>or similar*</em><br><br>'; 
								?>
							</span>

							<span id="passengers">
								<em>
									<?php echo(types_render_field("num-passengers", array("arg1"=>"val1","arg2"=>"val2"))); ?>
								</em>
							</span>

							<span id="bags">
								<em>
									<?php echo(types_render_field("number-bags", array("arg1"=>"val1","arg2"=>"val2"))); ?>
								</em>
							</span>

							<p>
								<?php 
								echo(types_render_field("vehicle-description", array("arg1"=>"val1","arg2"=>"val2"))); 
								?>
							</p>

							<p class="smalls"><br>* This vehicle represents its manufacturer's base model</p>
						</div>

	                	<div class="vehicle-class-left">
	                		
	                		<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'first-image')) { ?>
	                			<div class="bigbox car1">								
									<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'first-image' );	?>
								</div>
							<?php } ?>

							<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'second-image')) { ?>
	                			<div class="bigbox car2">								
									<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'second-image' );	?>
								</div>
							<?php } ?>
							
							<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'third-image')) { ?>
	                			<div class="bigbox car3">								
									<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'third-image' );	?>
								</div>
							<?php } ?>
							
							<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'fourth-image')) { ?>
	                			<div class="bigbox car4">								
									<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'fourth-image' );	?>
								</div>
							<?php } ?>
							
							<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'fifth-image')) { ?>
	                			<div class="bigbox car5">								
									<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'fifth-image' );	?>
								</div>
							<?php } ?>

							<div class="fix"></div>

							<div id="carthumbs">

								<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'first-image')) { ?>
		                			<div class="thumbbox car1">								
										<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'first-image' );	?>
									</div>
								<?php } ?>

								<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'second-image')) { ?>
		                			<div class="thumbbox car2">								
										<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'second-image' );	?>
									</div>
								<?php } ?>

								<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'third-image')) { ?>
		                			<div class="thumbbox car3">								
										<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'third-image' );	?>
									</div>
								<?php } ?>

								<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'fourth-image')) { ?>
		                			<div class="thumbbox car4">								
										<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'fourth-image' );	?>
									</div>
								<?php } ?>

								<?php if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail(get_post_type(), 'fifth-image')) { ?>
		                			<div class="thumbbox car5">								
										<?php MultiPostThumbnails::the_post_thumbnail(get_post_type(), 'fifth-image' );	?>
									</div>
								<?php } ?>

							</div>

						</div>

						<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	                	<script>
	                		var highest = 6;
							$( ".thumbbox.car1" ).click(function() {
							  	$(".car1").css('z-index', ++highest); // increase highest by 1
							});
							$( ".thumbbox.car2" ).click(function() {
							  	$(".car2").css('z-index', ++highest); // increase highest by 1
							});
							$( ".thumbbox.car3" ).click(function() {
							  	$(".car3").css('z-index', ++highest); // increase highest by 1
							});
							$( ".thumbbox.car4" ).click(function() {
							  	$(".car4").css('z-index', ++highest); // increase highest by 1
							});
							$( ".thumbbox.car5" ).click(function() {
							  	$(".car5").css('z-index', ++highest); // increase highest by 1
							});
						</script>


					</section>	

					<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<span class="small">', '</span>' ); ?>									
	            
				</div><!-- /.article-inner -->

            </article><!-- .post -->

            <?php

				} // End WHILE Loop
			} else {
		?>
			<article <?php post_class(); ?>>
            	<p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
			</article><!-- .post -->             
       	<?php } ?>  
        
		</section><!-- #main -->
		
		<?php woo_main_after(); ?>

    </div><!-- #content -->

    <?php if ( woo_active_sidebar( 'single-full' ) ) { ?>

	<div id="single-widget-fullwidth" class="col-full">
		<?php woo_sidebar( 'single-full' ); ?>
	</div>

	<?php } ?>	
		
<?php get_footer(); ?>
