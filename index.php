<?php
/**
 * Plugin Name:Woocommerce Category Subcategory Product Search
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description:Search the Woocommerce Product based on Category and Subcategory 
 * Version: The Plugin's Version Number, e.g.: 1.0
 * Author: Sandeep Paul
 * Author URI: codeofsandy.wordpress.com
 * License: A "Slug" license name e.g. GPL2
 */


class Sandy_Woocommerce_Product_Filter extends WP_Widget {


	function __construct() {
		parent::__construct ( 'sandy_woocommerce_product_filter', 		// Base ID
				__ ( 'Sandy Woocommerce Product Search Widget', 'text_domain' ), 		// Name
				array (
						'description' => __ ( 'Search the Woocommerce Product based on Category and Subcategory', 'text_domain' )
				) );		// Args

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args
	 *        	Widget arguments.
	 * @param array $instance
	 *        	Saved values from database.
	 */
	public function widget($args, $instance) {
		$title = apply_filters ( 'widget_title', $instance ['title'] );

		echo $args ['before_widget'];
		if (! empty ( $title ))
			echo $args ['before_title'] . $title . $args ['after_title'];

		$dropdown_args = wp_parse_args ( $dropdown_args, $dropdown_defaults );

		// Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258		?>
<form method="get"
	action="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ))?>">
			<?php
		sandy_woocommerce_category_dropdown ( 'product_cat' );
		
		?>
		 <select id="sub_cat" name="product_cat"></select>
	<button class="button explore" type="submit">Explore</button>
</form>
<?php
	}
}
function register_sandy_woocommerce_product_filter_widget() {
	register_widget ( 'sandy_woocommerce_product_filter' );
}
add_action ( 'widgets_init', 'register_sandy_woocommerce_product_filter_widget' );

function sandy_woocommerce_category_dropdown($taxonomy) {
	$terms = get_terms ( $taxonomy );
	
	if ($terms) {
		$link = admin_url ( 'admin-ajax.php?action=my_user_vote&post_id=' . $post->ID . '&nonce=' . $nonce );
		printf ( '<select id="parent_cat"  class="postform" onchange="my_js_function();"><option value="">Region</option>', esc_attr ( $taxonomy ) );
		
		foreach ( $terms as $term ) {
			
			if ($term->parent == 0) {
				printf ( '<option value="%s">%s</option>', esc_attr ( $term->term_id ), esc_html ( $term->name ) );
			}
		}
		print ('</select>') ;
	}
}

function ajax_function() {
$dir =plugins_url();
	wp_enqueue_script ( 'function',$dir. '/woocommerce-subcategory-product-search/js/custom_ajax.js', 'jquery', true );
	wp_localize_script ( 'function', 'ajax_script', array (
	'ajaxurl' => admin_url ( 'admin-ajax.php' )
	) );
}
add_action ( 'template_redirect', 'ajax_function' );
add_action ( "wp_ajax_nopriv_get_my_option", "get_my_option" );
add_action ( "wp_ajax_get_my_option", "get_my_option" );
function get_my_option() {
	// print_r($_REQUEST);
	if ($_REQUEST ['parent_id'] != '') {
		$parent = get_term_by ( 'slug', $_REQUEST ['parent_id'], 'product_cat' );
		$args = array (
				'hierarchical' => 1,
				'show_option_none' => '',
				'hide_empty' => 0,
				'parent' => $_REQUEST ['parent_id'],
				'taxonomy' => 'product_cat'
		);
		$subcats = get_categories ( $args );
		// print_r($subcats);

		foreach ( $subcats as $subcat ) {
			printf ( '<option value="%s">%s</option>', esc_attr ( $subcat->slug ), esc_html ( $subcat->name ) );
		}

		// $var = get_option('my_var');
		// echo json_encode($var);
		die ();
	}
}