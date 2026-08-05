<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Page {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'maybe_sync_page_content' ) );
		add_action( 'template_redirect', array( $this, 'render_custom_menu_page' ) );
	}

	/**
	 * Create page.
	 *
	 * @param bool $force Force content sync.
	 * @return int
	 */
	public static function maybe_create_menu_page( $force = false ) {
		$settings = WRMP_Helpers::get_settings();
		$page_id  = absint( $settings['menu_page_id'] ?? 0 );
		$content  = '[wrmp_menu]';

		if ( $page_id && get_post( $page_id ) instanceof WP_Post ) {
			if ( $force ) {
				wp_update_post(
					array(
						'ID'           => $page_id,
						'post_content' => $content,
					)
				);
			}

			return $page_id;
		}

		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Restaurant Menu', 'woo-resturant-cafe-menu' ),
				'post_name'    => 'restaurant-menu',
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => $content,
			)
		);

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			$settings['menu_page_id'] = $page_id;
			update_option( WRMP_Helpers::OPTION_KEY, WRMP_Helpers::sanitize_settings( $settings ) );
			flush_rewrite_rules();
			return (int) $page_id;
		}

		return 0;
	}

	/**
	 * Sync page content.
	 *
	 * @return void
	 */
	public function maybe_sync_page_content() {
		$settings = WRMP_Helpers::get_settings();

		if ( empty( $settings['menu_page_enabled'] ) || empty( $settings['menu_page_id'] ) ) {
			return;
		}

		$post = get_post( $settings['menu_page_id'] );

		if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
			return;
		}

		if ( false === strpos( (string) $post->post_content, '[wrmp_menu]' ) ) {
			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_content' => '[wrmp_menu]',
				)
			);
		}
	}

	/**
	 * Render standalone menu page.
	 *
	 * @return void
	 */
	public function render_custom_menu_page() {
		if ( ! WRMP_Helpers::is_menu_page() ) {
			return;
		}

		status_header( 200 );
		nocache_headers();

		// Full-page, theme-independent view: no admin toolbar, no theme header/footer.
		add_filter( 'show_admin_bar', '__return_false' );
		remove_action( 'wp_head', '_wp_render_title_tag', 1 );

		$query           = new WRMP_Menu_Query();
		$categories      = $query->get_selected_categories();
		$settings        = WRMP_Helpers::get_settings();
		$active_category = ! empty( $categories ) ? $categories[0] : null;
		$products_term   = $active_category && ! empty( $active_category['children'] ) ? $active_category['children'][0] : $active_category;
		$products        = $products_term ? $query->get_category_products( $products_term['term_id'] ) : array();

		include WRMP_PATH . 'templates/standalone-menu-page.php';
		exit;
	}
}
