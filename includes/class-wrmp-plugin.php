<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Plugin {
	/**
	 * Instance.
	 *
	 * @var WRMP_Plugin|null
	 */
	protected static $instance = null;

	/**
	 * Modules.
	 *
	 * @var array
	 */
	protected $modules = array();

	/**
	 * Singleton.
	 *
	 * @return WRMP_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		add_action( 'plugins_loaded', array( $this, 'load' ) );
	}

	/**
	 * Activation.
	 *
	 * @return void
	 */
	public static function activate() {
		self::load_dependencies();

		if ( class_exists( 'WRMP_Page' ) ) {
			WRMP_Page::maybe_create_menu_page();
		}
	}

	/**
	 * Deactivation.
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Load plugin.
	 *
	 * @return void
	 */
	public function load() {
		$this->load_textdomain();

		if ( ! $this->is_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_notice' ) );
			return;
		}

		self::load_dependencies();
		$this->boot_modules();
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	protected function load_textdomain() {
		load_plugin_textdomain( 'woo-resturant-cafe-menu', false, dirname( plugin_basename( WRMP_FILE ) ) . '/languages' );
	}

	/**
	 * Shared includes.
	 *
	 * @return void
	 */
	protected static function load_dependencies() {
		require_once WRMP_PATH . 'includes/class-wrmp-helpers.php';
		require_once WRMP_PATH . 'includes/class-wrmp-assets.php';
		require_once WRMP_PATH . 'includes/class-wrmp-menu-query.php';
		require_once WRMP_PATH . 'includes/admin/class-wrmp-admin-settings.php';
		require_once WRMP_PATH . 'includes/frontend/class-wrmp-shortcode.php';
		require_once WRMP_PATH . 'includes/frontend/class-wrmp-page.php';
	}

	/**
	 * Initialize modules.
	 *
	 * @return void
	 */
	protected function boot_modules() {
		$this->modules['assets']    = new WRMP_Assets();
		$this->modules['admin']     = is_admin() ? new WRMP_Admin_Settings() : null;
		$this->modules['shortcode'] = new WRMP_Shortcode();
		$this->modules['page']      = new WRMP_Page();
	}

	/**
	 * WooCommerce state.
	 *
	 * @return bool
	 */
	protected function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Admin notice.
	 *
	 * @return void
	 */
	public function woocommerce_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-warning">
			<p><?php echo esc_html__( 'Woo Resturant Cafe Menu requires WooCommerce to be active.', 'woo-resturant-cafe-menu' ); ?></p>
		</div>
		<?php
	}
}
