<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Shortcode {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'wrmp_menu', array( $this, 'render' ) );
		add_action( 'wp_ajax_wrmp_get_category_products', array( $this, 'ajax_get_category_products' ) );
		add_action( 'wp_ajax_nopriv_wrmp_get_category_products', array( $this, 'ajax_get_category_products' ) );
	}

	/**
	 * Render shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'show_title' => 'yes',
			),
			$atts,
			'wrmp_menu'
		);

		$query            = new WRMP_Menu_Query();
		$categories       = $query->get_selected_categories();
		$settings         = WRMP_Helpers::get_settings();
		$active_category  = ! empty( $categories ) ? $categories[0] : null;
		$products_term    = $active_category && ! empty( $active_category['children'] ) ? $active_category['children'][0] : $active_category;
		$products         = $products_term ? $query->get_category_products( $products_term['term_id'] ) : array();

		ob_start();
		include WRMP_PATH . 'templates/menu-page.php';
		return ob_get_clean();
	}

	/**
	 * AJAX category switch.
	 *
	 * @return void
	 */
	public function ajax_get_category_products() {
		check_ajax_referer( 'wrmp_menu_nonce', 'nonce' );

		$term_id = isset( $_POST['term_id'] ) ? absint( wp_unslash( $_POST['term_id'] ) ) : 0;
		$query   = new WRMP_Menu_Query();
		$allowed = $query->get_selected_term_ids();

		if ( ! $term_id || ! in_array( $term_id, $allowed, true ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Invalid menu category.', 'woo-resturant-cafe-menu' ),
				),
				400
			);
		}

		$category = get_term( $term_id, 'product_cat' );
		$products = $query->get_category_products( $term_id );
		$settings = WRMP_Helpers::get_settings();

		ob_start();
		include WRMP_PATH . 'templates/product-grid.php';
		$html = ob_get_clean();

		wp_send_json_success(
			array(
				'title' => $category instanceof WP_Term ? $category->name : '',
				'description' => $category instanceof WP_Term ? $category->description : '',
				'html'  => $html,
			)
		);
	}
}
