<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Admin_Settings {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Menu.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Restaurant Menu', 'woo-resturant-cafe-menu' ),
			__( 'Restaurant Menu', 'woo-resturant-cafe-menu' ),
			'manage_options',
			'wrmp-settings',
			array( $this, 'render_page' ),
			'dashicons-store',
			56
		);
	}

	/**
	 * Settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'wrmp_settings_group',
			WRMP_Helpers::OPTION_KEY,
			array(
				'sanitize_callback' => array( 'WRMP_Helpers', 'sanitize_settings' ),
			)
		);

		add_settings_section( 'wrmp_general', __( 'General Settings', 'woo-resturant-cafe-menu' ), '__return_false', 'wrmp-settings' );
		add_settings_field( 'restaurant_name', __( 'Restaurant Name', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'restaurant_name' ) );
		add_settings_field( 'logo_id', __( 'Logo', 'woo-resturant-cafe-menu' ), array( $this, 'render_logo_field' ), 'wrmp-settings', 'wrmp_general' );
		add_settings_field( 'primary_color', __( 'Primary Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'primary_color', 'type' => 'color' ) );
		add_settings_field( 'secondary_color', __( 'Secondary Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'secondary_color', 'type' => 'color' ) );
		add_settings_field( 'background_color', __( 'Background Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'background_color', 'type' => 'color' ) );
		add_settings_field( 'text_color', __( 'Text Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'text_color', 'type' => 'color' ) );
		add_settings_field( 'instagram_id', __( 'Instagram ID', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'instagram_id' ) );
		add_settings_field( 'phone_number', __( 'Phone Number', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_general', array( 'key' => 'phone_number' ) );

		add_settings_section( 'wrmp_style_cards', __( 'Card Styles', 'woo-resturant-cafe-menu' ), '__return_false', 'wrmp-settings' );
		add_settings_field( 'category_card_inactive_background', __( 'Category Inactive Background', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_inactive_background', 'type' => 'color' ) );
		add_settings_field( 'category_card_inactive_text_color', __( 'Category Inactive Text Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_inactive_text_color', 'type' => 'color' ) );
		add_settings_field( 'category_card_background', __( 'Category Card Background', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_background', 'type' => 'color' ) );
		add_settings_field( 'category_card_text_color', __( 'Category Card Text Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_text_color', 'type' => 'color' ) );
		add_settings_field( 'category_card_hover_background', __( 'Category Hover Background', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_hover_background', 'type' => 'color' ) );
		add_settings_field( 'category_card_hover_text_color', __( 'Category Hover Text Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'category_card_hover_text_color', 'type' => 'color' ) );
		add_settings_field( 'product_card_background', __( 'Product Card Background', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'product_card_background', 'type' => 'color' ) );
		add_settings_field( 'product_card_text_color', __( 'Product Card Text Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'product_card_text_color', 'type' => 'color' ) );
		add_settings_field( 'product_name_color', __( 'Product Name Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'product_name_color', 'type' => 'color' ) );
		add_settings_field( 'product_price_color', __( 'Product Price Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'product_price_color', 'type' => 'color' ) );
		add_settings_field( 'product_description_color', __( 'Product Description Color', 'woo-resturant-cafe-menu' ), array( $this, 'render_text_field' ), 'wrmp-settings', 'wrmp_style_cards', array( 'key' => 'product_description_color', 'type' => 'color' ) );

		add_settings_section( 'wrmp_menu_source', __( 'Menu Source', 'woo-resturant-cafe-menu' ), '__return_false', 'wrmp-settings' );
		add_settings_field( 'category_ids', __( 'Product Categories', 'woo-resturant-cafe-menu' ), array( $this, 'render_categories_field' ), 'wrmp-settings', 'wrmp_menu_source' );

		add_settings_section( 'wrmp_page', __( 'Menu Page', 'woo-resturant-cafe-menu' ), '__return_false', 'wrmp-settings' );
		add_settings_field( 'menu_page_enabled', __( 'Enable Dedicated Menu Page', 'woo-resturant-cafe-menu' ), array( $this, 'render_checkbox_field' ), 'wrmp-settings', 'wrmp_page', array( 'key' => 'menu_page_enabled' ) );
		add_settings_field( 'menu_page_id', __( 'Dedicated Menu Page', 'woo-resturant-cafe-menu' ), array( $this, 'render_page_field' ), 'wrmp-settings', 'wrmp_page' );
	}

	/**
	 * Page output.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( isset( $_POST['wrmp_create_page'] ) && check_admin_referer( 'wrmp_create_page_action', 'wrmp_create_page_nonce' ) ) {
			$page_id  = WRMP_Page::maybe_create_menu_page( true );
			$settings = WRMP_Helpers::get_settings();
			$settings['menu_page_id'] = $page_id;
			update_option( WRMP_Helpers::OPTION_KEY, $settings );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'Menu page created or updated.', 'woo-resturant-cafe-menu' ) . '</p></div>';
		}
		?>
		<div class="wrap wrmp-admin">
			<h1><?php echo esc_html__( 'Woo Resturant Cafe Menu', 'woo-resturant-cafe-menu' ); ?></h1>
			<p class="wrmp-admin-intro"><?php esc_html_e( 'Configure your restaurant branding, choose WooCommerce categories, and build a fast AJAX menu page.', 'woo-resturant-cafe-menu' ); ?></p>
			<div class="wrmp-admin-shortcode-card">
				<strong><?php esc_html_e( 'Shortcode', 'woo-resturant-cafe-menu' ); ?>:</strong>
				<code>[wrmp_menu]</code>
				<span><?php esc_html_e( 'Use this shortcode on any page, or enable the dedicated menu page below.', 'woo-resturant-cafe-menu' ); ?></span>
			</div>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'wrmp_settings_group' );
				do_settings_sections( 'wrmp-settings' );
				submit_button();
				?>
			</form>
			<form method="post">
				<?php wp_nonce_field( 'wrmp_create_page_action', 'wrmp_create_page_nonce' ); ?>
				<?php submit_button( __( 'Create / Refresh Menu Page', 'woo-resturant-cafe-menu' ), 'secondary', 'wrmp_create_page', false ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Text field.
	 *
	 * @param array $args Args.
	 * @return void
	 */
	public function render_text_field( $args ) {
		$key      = $args['key'];
		$type     = $args['type'] ?? 'text';
		$settings = WRMP_Helpers::get_settings();
		$value    = $settings[ $key ] ?? '';
		?>
		<input type="<?php echo esc_attr( $type ); ?>" class="<?php echo 'color' === $type ? 'wrmp-color-field' : 'regular-text'; ?>" name="<?php echo esc_attr( WRMP_Helpers::OPTION_KEY . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php if ( 'restaurant_name' === $key ) : ?>
			<p class="description"><?php esc_html_e( 'Shown at the top of the menu page and shortcode output.', 'woo-resturant-cafe-menu' ); ?></p>
		<?php elseif ( 'instagram_id' === $key ) : ?>
			<p class="description"><?php esc_html_e( 'Optional. Example: yourrestaurant', 'woo-resturant-cafe-menu' ); ?></p>
		<?php elseif ( 'phone_number' === $key ) : ?>
			<p class="description"><?php esc_html_e( 'Optional. Displayed in the custom menu sidebar.', 'woo-resturant-cafe-menu' ); ?></p>
		<?php elseif ( in_array( $key, array( 'category_card_background', 'category_card_text_color', 'category_card_inactive_background', 'category_card_inactive_text_color', 'product_card_background', 'product_card_text_color', 'product_name_color', 'product_price_color', 'product_description_color' ), true ) ) : ?>
			<p class="description"><?php esc_html_e( 'Used on the custom menu cards in the frontend layout.', 'woo-resturant-cafe-menu' ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Checkbox field.
	 *
	 * @param array $args Args.
	 * @return void
	 */
	public function render_checkbox_field( $args ) {
		$key      = $args['key'];
		$checked  = ! empty( WRMP_Helpers::get_setting( $key, 0 ) );
		$labels   = array(
			'menu_page_enabled' => __( 'Maintain a dedicated WordPress page containing the restaurant menu shortcode.', 'woo-resturant-cafe-menu' ),
		);
		?>
		<label>
			<input type="checkbox" name="<?php echo esc_attr( WRMP_Helpers::OPTION_KEY . '[' . $key . ']' ); ?>" value="1" <?php checked( $checked ); ?>>
			<?php esc_html_e( 'Enabled', 'woo-resturant-cafe-menu' ); ?>
		</label>
		<?php if ( isset( $labels[ $key ] ) ) : ?>
			<p class="description"><?php echo esc_html( $labels[ $key ] ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Logo field.
	 *
	 * @return void
	 */
	public function render_logo_field() {
		$logo_id  = absint( WRMP_Helpers::get_setting( 'logo_id', 0 ) );
		$logo_url = WRMP_Helpers::get_logo_url();
		?>
		<div class="wrmp-logo-field">
			<input type="hidden" id="wrmp-logo-id" name="<?php echo esc_attr( WRMP_Helpers::OPTION_KEY . '[logo_id]' ); ?>" value="<?php echo esc_attr( $logo_id ); ?>">
			<button type="button" class="button wrmp-upload-logo"><?php esc_html_e( 'Choose Logo', 'woo-resturant-cafe-menu' ); ?></button>
			<button type="button" class="button wrmp-remove-logo"><?php esc_html_e( 'Remove Logo', 'woo-resturant-cafe-menu' ); ?></button>
			<div class="wrmp-logo-preview">
				<?php if ( $logo_url ) : ?>
					<img src="<?php echo esc_url( $logo_url ); ?>" alt="">
				<?php else : ?>
					<span class="wrmp-empty-preview"><?php esc_html_e( 'No logo selected yet.', 'woo-resturant-cafe-menu' ); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<p class="description"><?php esc_html_e( 'Recommended for the menu header and dedicated restaurant page.', 'woo-resturant-cafe-menu' ); ?></p>
		<?php
	}

	/**
	 * Category field.
	 *
	 * @return void
	 */
	public function render_categories_field() {
		$selected_ids = array_map( 'absint', (array) WRMP_Helpers::get_setting( 'category_ids', array() ) );
		$categories   = $this->get_category_tree();

		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			echo '<p>' . esc_html__( 'No product categories found.', 'woo-resturant-cafe-menu' ) . '</p>';
			return;
		}
		?>
		<div class="wrmp-category-selector">
			<div class="wrmp-category-actions">
				<button type="button" class="button button-secondary wrmp-select-all-categories"><?php esc_html_e( 'Select All', 'woo-resturant-cafe-menu' ); ?></button>
				<button type="button" class="button button-secondary wrmp-clear-categories"><?php esc_html_e( 'Clear', 'woo-resturant-cafe-menu' ); ?></button>
			</div>
			<div class="wrmp-category-tree">
				<?php $this->render_category_checklist( $categories, $selected_ids ); ?>
			</div>
		</div>
		<p class="description"><?php esc_html_e( 'Selected categories and their child categories appear in the restaurant menu.', 'woo-resturant-cafe-menu' ); ?></p>
		<?php
	}

	/**
	 * Page field.
	 *
	 * @return void
	 */
	public function render_page_field() {
		$page_id = WRMP_Helpers::get_menu_page_id();
		wp_dropdown_pages(
			array(
				'name'             => WRMP_Helpers::OPTION_KEY . '[menu_page_id]',
				'selected'         => $page_id,
				'show_option_none' => __( 'Select a page', 'woo-resturant-cafe-menu' ),
			)
		);

		if ( $page_id ) {
			$edit_link = get_edit_post_link( $page_id, '' );
			$view_link = get_permalink( $page_id );

			echo '<p class="description">';
			if ( $edit_link ) {
				echo '<a href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit selected page', 'woo-resturant-cafe-menu' ) . '</a>';
			}
			if ( $edit_link && $view_link ) {
				echo ' | ';
			}
			if ( $view_link ) {
				echo '<a href="' . esc_url( $view_link ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View selected page', 'woo-resturant-cafe-menu' ) . '</a>';
			}
			echo '</p>';
		}
	}

	/**
	 * Hierarchical category tree.
	 *
	 * @return array
	 */
	protected function get_category_tree() {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return $terms;
		}

		$tree = array();
		$map  = array();

		foreach ( $terms as $term ) {
			$map[ $term->term_id ] = array(
				'term'     => $term,
				'children' => array(),
			);
		}

		foreach ( $map as $term_id => &$node ) {
			$parent = (int) $node['term']->parent;

			if ( $parent && isset( $map[ $parent ] ) ) {
				$map[ $parent ]['children'][ $term_id ] = &$node;
			} else {
				$tree[ $term_id ] = &$node;
			}
		}

		unset( $node );

		return $tree;
	}

	/**
	 * Render hierarchical checklist.
	 *
	 * @param array $nodes Nodes.
	 * @param array $selected_ids Selected IDs.
	 * @return void
	 */
	protected function render_category_checklist( $nodes, $selected_ids ) {
		if ( empty( $nodes ) ) {
			return;
		}

		echo '<ul class="wrmp-category-checklist">';

		foreach ( $nodes as $node ) {
			$term      = $node['term'];
			$is_checked = in_array( (int) $term->term_id, $selected_ids, true );

			echo '<li>';
			echo '<label>';
			echo '<input type="checkbox" class="wrmp-category-checkbox" name="' . esc_attr( WRMP_Helpers::OPTION_KEY . '[category_ids][]' ) . '" value="' . esc_attr( $term->term_id ) . '" ' . checked( $is_checked, true, false ) . '>';
			echo '<span class="wrmp-category-name">' . esc_html( $term->name ) . '</span>';
			echo '</label>';
			$thumbnail_url = WRMP_Helpers::get_category_thumbnail_url( $term->term_id );
			if ( $thumbnail_url ) {
				echo '<img class="wrmp-category-thumb" src="' . esc_url( $thumbnail_url ) . '" alt="">';
			}

			if ( ! empty( $node['children'] ) ) {
				$this->render_category_checklist( $node['children'], $selected_ids );
			}

			echo '</li>';
		}

		echo '</ul>';
	}
}
