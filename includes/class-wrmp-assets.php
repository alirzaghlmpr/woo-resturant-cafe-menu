<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Assets {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'isolate_standalone_page' ), 9999 );
		add_action( 'wp_footer', array( $this, 'isolate_standalone_page' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
	}

	/**
	 * Frontend assets.
	 *
	 * @return void
	 */
	public function enqueue_frontend() {
		if ( ! WRMP_Helpers::is_menu_context() ) {
			return;
		}

		wp_register_style( 'wrmp-frontend', WRMP_URL . 'assets/css/frontend.css', array(), WRMP_VERSION );
		wp_register_script( 'wrmp-frontend', WRMP_URL . 'assets/js/frontend.js', array( 'jquery' ), WRMP_VERSION, true );

		wp_enqueue_style( 'wrmp-frontend' );
		wp_enqueue_script( 'wrmp-frontend' );
		wp_localize_script(
			'wrmp-frontend',
			'wrmpMenu',
			array(
				'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'wrmp_menu_nonce' ),
				'loadingText'      => __( 'Loading menu items...', 'woo-resturant-cafe-menu' ),
				'errorText'        => __( 'Could not load menu items.', 'woo-resturant-cafe-menu' ),
				'skeletonCount'    => 6,
				'instagramLabel'   => __( 'Instagram', 'woo-resturant-cafe-menu' ),
				'phoneLabel'       => __( 'Phone', 'woo-resturant-cafe-menu' ),
			)
		);
	}

	/**
	 * Strip every non-plugin style/script on the standalone full-page menu.
	 *
	 * Runs after all themes/plugins have had a chance to enqueue on
	 * `wp_enqueue_scripts` (default priority 10), so the queue reflects
	 * everything that would otherwise be printed. Only this plugin's own
	 * assets (and their real dependencies, e.g. jQuery) survive.
	 *
	 * @return void
	 */
	public function isolate_standalone_page() {
		if ( ! WRMP_Helpers::is_menu_page() ) {
			return;
		}

		global $wp_scripts, $wp_styles;

		$keep_scripts = $this->collect_dependencies( $wp_scripts, array( 'wrmp-frontend' ) );

		foreach ( (array) $wp_scripts->queue as $handle ) {
			if ( ! in_array( $handle, $keep_scripts, true ) ) {
				wp_dequeue_script( $handle );
			}
		}

		$keep_styles = $this->collect_dependencies( $wp_styles, array( 'wrmp-frontend' ) );

		foreach ( (array) $wp_styles->queue as $handle ) {
			if ( ! in_array( $handle, $keep_styles, true ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Walk a dependency tree so required handles (e.g. jQuery) survive isolation.
	 *
	 * @param WP_Scripts|WP_Styles $registry Asset registry.
	 * @param array                $handles  Handles to keep, seed of the walk.
	 * @return array
	 */
	protected function collect_dependencies( $registry, $handles ) {
		$keep  = array();
		$stack = $handles;

		while ( ! empty( $stack ) ) {
			$handle = array_pop( $stack );

			if ( in_array( $handle, $keep, true ) ) {
				continue;
			}

			$keep[] = $handle;
			$item   = $registry->registered[ $handle ] ?? null;

			if ( $item && ! empty( $item->deps ) ) {
				foreach ( $item->deps as $dep ) {
					$stack[] = $dep;
				}
			}
		}

		return $keep;
	}

	/**
	 * Admin assets.
	 *
	 * @param string $hook Hook.
	 * @return void
	 */
	public function enqueue_admin( $hook ) {
		if ( 'toplevel_page_wrmp-settings' !== $hook ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'wrmp-admin', WRMP_URL . 'assets/css/admin.css', array(), WRMP_VERSION );
		wp_enqueue_script( 'wrmp-admin', WRMP_URL . 'assets/js/admin.js', array( 'jquery' ), WRMP_VERSION, true );
		wp_localize_script(
			'wrmp-admin',
			'wrmpAdmin',
			array(
				'mediaTitle'  => __( 'Select Logo', 'woo-resturant-cafe-menu' ),
				'mediaButton' => __( 'Use Logo', 'woo-resturant-cafe-menu' ),
				'noLogo'      => __( 'No logo selected yet.', 'woo-resturant-cafe-menu' ),
			)
		);
	}
}
