<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Define the theme-specific key to be sent to PressTrends.
define( 'WOO_PRESSTRENDS_THEMEKEY', '3kohovka9l4zwjaep2yq6v3ampuxpcnay' );

// WooFramework init
require_once ( get_template_directory() . '/functions/admin-init.php' );	

/*-----------------------------------------------------------------------------------*/
/* Load the theme-specific files, with support for overriding via a child theme.
/*-----------------------------------------------------------------------------------*/

$includes = array(
				'includes/theme-options.php', 			// Options panel settings and custom settings
				'includes/theme-functions.php', 		// Custom theme functions
				'includes/theme-actions.php', 			// Theme actions & user defined hooks
				'includes/theme-comments.php', 			// Custom comments/pingback loop
				'includes/theme-js.php', 				// Load JavaScript via wp_enqueue_script
				'includes/sidebar-init.php', 			// Initialize widgetized areas
				'includes/theme-widgets.php'			// Theme widgets
				);

// Allow child themes/plugins to add widgets to be loaded.
$includes = apply_filters( 'woo_includes', $includes );
				
foreach ( $includes as $i ) {
	locate_template( $i, true );
}

// Load WooCommerce functions, if applicable.
if ( is_woocommerce_activated() ) {
	locate_template( 'includes/theme-woocommerce.php', true );
}

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/


// custom text on admin portfolio-item featured image boxes
add_action('do_meta_boxes', 'portfolio_image_box');

function portfolio_image_box() {

	remove_meta_box( 'postimagediv', 'vehicle-class', 'side' );

}


// multiple thumbnails for portfolio-image post type
if (class_exists('MultiPostThumbnails')) {

	 new MultiPostThumbnails(array(
		'label' => 'First Image',
		'id' => 'first-image',
		'post_type' => 'vehicle-class'
	 ) );

	 new MultiPostThumbnails(array(
		'label' => 'Second Image',
		'id' => 'second-image',
		'post_type' => 'vehicle-class'
	 ) );
	 
	 new MultiPostThumbnails(array(
		'label' => 'Third Image',
		'id' => 'third-image',
		'post_type' => 'vehicle-class'
	 ) );
	 
	 new MultiPostThumbnails(array(
		'label' => 'Fourth Image',
		'id' => 'Fourth-Image',
		'post_type' => 'vehicle-class'
	 ) );
	 
	 new MultiPostThumbnails(array(
		'label' => 'Fifth Image',
		'id' => 'fifth-image',
		'post_type' => 'vehicle-class'
	 ) );

}







/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>
