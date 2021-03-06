<?php
// File Security Check
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die ( 'You do not have sufficient permissions to access this page' );
}
?>
<?php

global $woo_options;

// If WooCommerce is active, do all the things
if ( is_woocommerce_activated() )

// Load WooCommerce stylsheet
if ( ! is_admin() ) { add_action( 'get_header', 'woo_load_woocommerce_css', 20 ); }

if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		wp_register_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
		wp_enqueue_style( 'woocommerce' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Products */
/*-----------------------------------------------------------------------------------*/

// Number of products per page
add_filter('loop_shop_per_page', 'wooframework_products_per_page');
if (!function_exists('wooframework_products_per_page')) {
	function wooframework_products_per_page() {
		global $woo_options;
		if ( isset( $woo_options['woocommerce_products_per_page'] ) ) {
			return $woo_options['woocommerce_products_per_page'];
		}
	}
}

// Display product tabs?
add_action('wp_head','wooframework_tab_check');
if ( ! function_exists( 'wooframework_tab_check' ) ) {
	function wooframework_tab_check() {
		global $woo_options;
		if ( isset( $woo_options[ 'woocommerce_product_tabs' ] ) && $woo_options[ 'woocommerce_product_tabs' ] == "false" ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		}
	}
}

// Display related products
add_action('wp_head','wooframework_related_products');
if ( ! function_exists( 'wooframework_related_products' ) ) {
	function wooframework_related_products() {
		global $woo_options;
		if ( isset( $woo_options[ 'woocommerce_related_products' ] ) && $woo_options[ 'woocommerce_related_products' ] == "false" ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* Layout */
/*-----------------------------------------------------------------------------------*/

// Shop archives full width?
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action( 'woo_main_after', 'woocommerce_get_sidebar', 10 );
// Only display sidebar on product archives if instructed to do so via woocommerce_archives_fullwidth
if (!function_exists('woocommerce_get_sidebar')) {
	function woocommerce_get_sidebar() {
		global $woo_options;
		if (!is_woocommerce() && !is_page_template('template-fullwidth.php') ) {
			get_sidebar();
		} elseif ( isset( $woo_options[ 'woocommerce_archives_fullwidth' ] ) && $woo_options[ 'woocommerce_archives_fullwidth' ] == "true" && (is_woocommerce()) || (is_product()) ) {
			get_sidebar();
		} elseif ( isset( $woo_options[ 'woocommerce_archives_fullwidth' ] ) && $woo_options[ 'woocommerce_archives_fullwidth' ] == "false" && (is_archive(array('product', 'portfolio'))) ) {
			// no sidebar
		}
	}
}

// Add a class to the body if full width shop archives are specified
add_filter( 'body_class','wooframework_layout_body_class', 10 );		// Add layout to body_class output
if ( ! function_exists( 'wooframework_layout_body_class' ) ) {
	function wooframework_layout_body_class( $wc_classes ) {

		global $woo_options;

		$layout = '';

		// Add woocommerce-fullwidth class if full width option is enabled
		if ( isset( $woo_options[ 'woocommerce_archives_fullwidth' ] ) && $woo_options[ 'woocommerce_archives_fullwidth' ] == "false" && (is_shop() || is_product_category())) {
			$layout = 'layout-full';
		}

		// Add classes to body_class() output
		$wc_classes[] = $layout;
		return $wc_classes;

	} // End woocommerce_layout_body_class()
}

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'woo_install_theme', 1);

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

function woo_install_theme() {

update_option( 'woocommerce_thumbnail_image_width', '400' );
update_option( 'woocommerce_thumbnail_image_height', '400' );
update_option( 'woocommerce_single_image_width', '720' );
update_option( 'woocommerce_single_image_height', '720' );
update_option( 'woocommerce_catalog_image_width', '350' );
update_option( 'woocommerce_catalog_image_height', '350' );

}

/*-----------------------------------------------------------------------------------*/
/* Disable WooCommerce Styles */
/*-----------------------------------------------------------------------------------*/
if ( version_compare( WOOCOMMERCE_VERSION, '2.1' ) >= 0 ) {
    // WooCommerce 2.1 or above is active
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
    // WooCommerce less than 2.1 is active
    define( 'WOOCOMMERCE_USE_CSS', false );
}

/*-----------------------------------------------------------------------------------*/
/* Fix the layout, breadcrumbs and pagination */
/*-----------------------------------------------------------------------------------*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'scrollider_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'scrollider_after_content', 20 );

// Fix the layout etc
if (!function_exists('scrollider_before_content')) {
	function scrollider_before_content() {
	?>
		<!-- #content Starts -->
		<?php woo_content_before(); ?>
	    <div id="content" class="col-full">
	    	<?php woo_main_before(); ?>
	    	<section id="main" class="col-left">
	        <!-- #main Starts -->

	    <?php
	}
}

if (!function_exists('scrollider_after_content')) {
	function scrollider_after_content() {
	?>
			</section>
			<?php woo_main_after(); ?>
	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}


// Remove breadcrumb (we're using the WooFramework default breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

// Remove pagination (we're using the WooFramework default pagination)
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );

remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerceframework_pagination', 10 );
function woocommerceframework_pagination() {
	if ( is_search() && is_post_type_archive() ) {
		add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
	}
	woo_pagenav();
}

function woocommerceframework_add_search_fragment ( $settings ) {
	$settings['add_fragment'] = '&post_type=product';
	return $settings;
}

/*-----------------------------------------------------------------------------------*/
/* If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'lightbox[product-gallery]');
				});
			</script>
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Product Archives */
/*-----------------------------------------------------------------------------------*/
// 3 products per row
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}

// Remove add to cart buttons
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// Add excerpt
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5);

// Move the sale marker
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 15 );

// Add wrapping div around pagination
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_open', 5 );
add_action( 'woocommerce_pagination', 'woocommerce_pagination_wrap_close', 25 );

if (!function_exists('woocommerce_pagination_wrap_open')) {
	function woocommerce_pagination_wrap_open() {
		echo '<section class="pagination-wrap">';
	}
}

if (!function_exists('woocommerce_pagination_wrap_close')) {
	function woocommerce_pagination_wrap_close() {
		echo '</section>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Single Product */
/*-----------------------------------------------------------------------------------*/
// Add single product wrap
add_action( 'woocommerce_before_single_product_summary', 'scrollider_product_wrap_open', 10 );
add_action( 'woocommerce_after_single_product_summary', 'scrollider_product_wrap_close', 5 );

function scrollider_product_wrap_open() {
	echo '<div class="single-product-wrap">';
}

function scrollider_product_wrap_close() {
	echo '</div>';
}

// Change thumbs on the single page to 4 per column
add_filter( 'woocommerce_product_thumbnails_columns', 'woocommerce_custom_product_thumbnails_columns' );

if (!function_exists('woocommerce_custom_product_thumbnails_columns')) {
	function woocommerce_custom_product_thumbnails_columns() {
		return 4;
	}
}

// Move the sale marker
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 3 );

// Change the number of related products displayed
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20);

if (!function_exists('woocommerce_output_related_products')) {
	function woocommerce_output_related_products() {

		global $woo_options;

		if ( isset( $woo_options[ 'woocommerce_related_products' ] ) && $woo_options[ 'woocommerce_related_products' ] == "true" ) {
			woocommerce_related_products(3,3); // 3 products, 3 columns
		}

	}
}

// Change the number of upsells displayed
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_upsells', 20);

if (!function_exists('woocommerce_output_upsells')) {
	function woocommerce_output_upsells() {
	    woocommerce_upsell_display(3,3); // Display 3 products in rows of 3
	}
}

// Move the breadcrumbs
add_action( 'wp', 'scrollider_single_product_breadcrumbs' );
function scrollider_single_product_breadcrumbs() {
	if ( is_woocommerce() ) {
		remove_action('woo_content_before','woo_display_breadcrumbs',10);
		add_action('woocommerce_before_main_content','woo_display_breadcrumbs',2);
	}
}


/*-----------------------------------------------------------------------------------*/
/* Widgets */
/*-----------------------------------------------------------------------------------*/
// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'star_sidebar');

if (!function_exists('star_sidebar')) {
	function star_sidebar() {
		return 12;
	}
}

/*-----------------------------------------------------------------------------------*/
/* The mini cart in the header */
/*-----------------------------------------------------------------------------------*/
add_action( 'add_to_cart_fragments', 'woocommerceframework_header_add_to_cart_fragment' );
if (!function_exists('woocommerceframework_header_add_to_cart_fragment')) {
	function woocommerceframework_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		scrollider_mini_cart();
		$fragments['ul.mini-cart'] = ob_get_clean();
		return $fragments;
	}
}
if (!function_exists('scrollider_mini_cart')) {
	function scrollider_mini_cart() {
		global $woocommerce;
		?>
		<ul class="mini-cart">
			<li>
				<a href="#" class="cart-parent">
					<span>
					<?php
					echo '<mark>' . $woocommerce->cart->get_cart_contents_count() . '</mark>';
					_e('View Cart', 'woothemes');
					?>
					</span>
				</a>
				<?php

		        echo '<ul class="cart_list">';
		        echo '<li class="cart-title"><h3>'.__('Your Cart Contents', 'woothemes').'</h3></li>';
		           if (sizeof($woocommerce->cart->cart_contents)>0) : foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
			           $_product = $cart_item['data'];
			           if ($_product->exists() && $cart_item['quantity']>0) :
			               echo '<li class="cart_list_product"><a href="'.get_permalink($cart_item['product_id']).'">';

			               echo $_product->get_image();

			               echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product).'</a>';

			               if($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
			                   echo woocommerce_get_formatted_variation( $cart_item['variation'] );
			                 endif;

			               echo '<span class="quantity">' .$cart_item['quantity'].' &times; '.woocommerce_price($_product->get_price()).'</span></li>';
			           endif;
			       endforeach;

		        	else: echo '<li class="empty">'.__('No products in the cart.','woothemes').'</li>'; endif;
		        	if (sizeof($woocommerce->cart->cart_contents)>0) :
		            echo '<li class="total"><strong>';

		            if (get_option('js_prices_include_tax')=='yes') :
		                _e('Total', 'woothemes');
		            else :
		                _e('Subtotal', 'woothemes');
		            endif;



		            echo ':</strong>' . $woocommerce->cart->get_cart_total() . '</li>';

		            echo '<li class="buttons"><a href="'.$woocommerce->cart->get_cart_url().'" class="button">'.__('View Cart &rarr;','woothemes').'</a> <a href="'.$woocommerce->cart->get_checkout_url().'" class="button checkout">'.__('Checkout &rarr;','woothemes').'</a></li>';
		        endif;

		        echo '</ul>';

		    ?>
			</li>
		</ul>
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'woocommerce' );