<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Helpers {
	/**
	 * Option key.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'wrmp_settings';

	/**
	 * Defaults.
	 *
	 * @return array
	 */
	public static function get_default_settings() {
		return array(
			'restaurant_name'   => get_bloginfo( 'name' ),
			'logo_id'           => 0,
			'logo_width'        => 72,
			'logo_height'       => 0,
			'logo_width_mobile' => 42,
			'logo_height_mobile' => 0,
			'product_image_mode'          => 'fixed',
			'product_image_height'        => 380,
			'product_image_height_mobile' => 240,
			'primary_color'     => '#0d1b2a',
			'secondary_color'   => '#1b9aaa',
			'background_color'  => '#0d1b2a',
			'text_color'        => '#e0e1dd',
			'category_card_background' => '#1b9aaa',
			'category_card_text_color' => '#ffffff',
			'category_card_inactive_background' => '#1b263b',
			'category_card_inactive_text_color' => '#778da9',
			'category_card_hover_background' => '#415a77',
			'category_card_hover_text_color' => '#ffffff',
			'product_card_background'  => '#1b263b',
			'product_card_text_color'  => '#e0e1dd',
			'product_name_color'       => '#1b9aaa',
			'product_price_color'      => '#e0e1dd',
			'product_description_color'=> '#778da9',
			'instagram_id'      => '',
			'phone_number'      => '',
			'category_ids'      => array(),
			'menu_page_enabled' => 1,
			'menu_page_id'      => 0,
		);
	}

	/**
	 * Settings.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$saved = get_option( self::OPTION_KEY, array() );
		$saved = is_array( $saved ) ? $saved : array();

		return wp_parse_args( $saved, self::get_default_settings() );
	}

	/**
	 * One setting.
	 *
	 * @param string $key Key.
	 * @param mixed  $default Default.
	 * @return mixed
	 */
	public static function get_setting( $key, $default = null ) {
		$settings = self::get_settings();

		if ( array_key_exists( $key, $settings ) ) {
			return $settings[ $key ];
		}

		return $default;
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input.
	 * @return array
	 */
	public static function sanitize_settings( $input ) {
		$defaults = self::get_default_settings();
		$input    = is_array( $input ) ? $input : array();

		$settings = array(
			'restaurant_name'   => sanitize_text_field( $input['restaurant_name'] ?? $defaults['restaurant_name'] ),
			'logo_id'           => absint( $input['logo_id'] ?? $defaults['logo_id'] ),
			'logo_width'        => self::sanitize_size( $input['logo_width'] ?? $defaults['logo_width'], 16, 400 ),
			'logo_height'       => self::sanitize_size( $input['logo_height'] ?? $defaults['logo_height'], 0, 400 ),
			'logo_width_mobile' => self::sanitize_size( $input['logo_width_mobile'] ?? $defaults['logo_width_mobile'], 16, 400 ),
			'logo_height_mobile' => self::sanitize_size( $input['logo_height_mobile'] ?? $defaults['logo_height_mobile'], 0, 400 ),
			'product_image_mode'          => in_array( $input['product_image_mode'] ?? '', array( 'fixed', 'auto' ), true ) ? $input['product_image_mode'] : $defaults['product_image_mode'],
			'product_image_height'        => self::sanitize_size( $input['product_image_height'] ?? $defaults['product_image_height'], 100, 1200 ),
			'product_image_height_mobile' => self::sanitize_size( $input['product_image_height_mobile'] ?? $defaults['product_image_height_mobile'], 80, 800 ),
			'primary_color'    => sanitize_hex_color( $input['primary_color'] ?? $defaults['primary_color'] ) ?: $defaults['primary_color'],
			'secondary_color'   => sanitize_hex_color( $input['secondary_color'] ?? $defaults['secondary_color'] ) ?: $defaults['secondary_color'],
			'background_color'  => sanitize_hex_color( $input['background_color'] ?? $defaults['background_color'] ) ?: $defaults['background_color'],
			'text_color'        => sanitize_hex_color( $input['text_color'] ?? $defaults['text_color'] ) ?: $defaults['text_color'],
			'category_card_background' => sanitize_hex_color( $input['category_card_background'] ?? $defaults['category_card_background'] ) ?: $defaults['category_card_background'],
			'category_card_text_color' => sanitize_hex_color( $input['category_card_text_color'] ?? $defaults['category_card_text_color'] ) ?: $defaults['category_card_text_color'],
			'category_card_inactive_background' => sanitize_hex_color( $input['category_card_inactive_background'] ?? $defaults['category_card_inactive_background'] ) ?: $defaults['category_card_inactive_background'],
			'category_card_inactive_text_color' => sanitize_hex_color( $input['category_card_inactive_text_color'] ?? $defaults['category_card_inactive_text_color'] ) ?: $defaults['category_card_inactive_text_color'],
			'category_card_hover_background' => sanitize_hex_color( $input['category_card_hover_background'] ?? $defaults['category_card_hover_background'] ) ?: $defaults['category_card_hover_background'],
			'category_card_hover_text_color' => sanitize_hex_color( $input['category_card_hover_text_color'] ?? $defaults['category_card_hover_text_color'] ) ?: $defaults['category_card_hover_text_color'],
			'product_card_background'  => sanitize_hex_color( $input['product_card_background'] ?? $defaults['product_card_background'] ) ?: $defaults['product_card_background'],
			'product_card_text_color'  => sanitize_hex_color( $input['product_card_text_color'] ?? $defaults['product_card_text_color'] ) ?: $defaults['product_card_text_color'],
			'product_name_color'       => sanitize_hex_color( $input['product_name_color'] ?? $defaults['product_name_color'] ) ?: $defaults['product_name_color'],
			'product_price_color'      => sanitize_hex_color( $input['product_price_color'] ?? $defaults['product_price_color'] ) ?: $defaults['product_price_color'],
			'product_description_color'=> sanitize_hex_color( $input['product_description_color'] ?? $defaults['product_description_color'] ) ?: $defaults['product_description_color'],
			'instagram_id'      => sanitize_text_field( $input['instagram_id'] ?? $defaults['instagram_id'] ),
			'phone_number'      => sanitize_text_field( $input['phone_number'] ?? $defaults['phone_number'] ),
			'category_ids'      => array_map( 'absint', (array) ( $input['category_ids'] ?? array() ) ),
			'menu_page_enabled' => empty( $input['menu_page_enabled'] ) ? 0 : 1,
			'menu_page_id'      => absint( $input['menu_page_id'] ?? $defaults['menu_page_id'] ),
		);

		return $settings;
	}

	/**
	 * Clamp a pixel size to a range.
	 *
	 * @param mixed $value Raw value.
	 * @param int   $min   Minimum.
	 * @param int   $max   Maximum.
	 * @return int
	 */
	protected static function sanitize_size( $value, $min, $max ) {
		return min( $max, max( $min, absint( $value ) ) );
	}

	/**
	 * CSS custom properties for the menu wrapper's inline style.
	 *
	 * Shared by the shortcode and standalone page templates.
	 *
	 * A height of 0 means "auto" (natural size); in auto image mode the
	 * skeleton placeholders fall back to a fixed height since they have no
	 * intrinsic size.
	 *
	 * @param array $settings Settings from get_settings().
	 * @return string
	 */
	public static function get_inline_style( $settings ) {
		$auto_image = 'auto' === $settings['product_image_mode'];

		$vars = array(
			'primary'                 => $settings['primary_color'],
			'secondary'               => $settings['secondary_color'],
			'background'              => $settings['background_color'],
			'text'                    => $settings['text_color'],
			'category-card-bg'        => $settings['category_card_background'],
			'category-card-text'      => $settings['category_card_text_color'],
			'category-card-inactive-bg'   => $settings['category_card_inactive_background'],
			'category-card-inactive-text' => $settings['category_card_inactive_text_color'],
			'category-card-hover-bg'  => $settings['category_card_hover_background'],
			'category-card-hover-text' => $settings['category_card_hover_text_color'],
			'product-card-bg'         => $settings['product_card_background'],
			'product-card-text'       => $settings['product_card_text_color'],
			'product-name-color'      => $settings['product_name_color'],
			'product-price-color'     => $settings['product_price_color'],
			'product-description-color' => $settings['product_description_color'],
			'logo-width'              => absint( $settings['logo_width'] ) . 'px',
			'logo-height'             => $settings['logo_height'] ? absint( $settings['logo_height'] ) . 'px' : 'auto',
			'logo-width-mobile'       => absint( $settings['logo_width_mobile'] ) . 'px',
			'logo-height-mobile'      => $settings['logo_height_mobile'] ? absint( $settings['logo_height_mobile'] ) . 'px' : 'auto',
			'image-height'            => $auto_image ? 'auto' : absint( $settings['product_image_height'] ) . 'px',
			'image-height-mobile'     => $auto_image ? 'auto' : absint( $settings['product_image_height_mobile'] ) . 'px',
			'skeleton-image-height'   => $auto_image ? '300px' : absint( $settings['product_image_height'] ) . 'px',
			'skeleton-image-height-mobile' => $auto_image ? '200px' : absint( $settings['product_image_height_mobile'] ) . 'px',
		);

		$style = '';

		foreach ( $vars as $name => $value ) {
			$style .= '--wrmp-' . $name . ': ' . $value . '; ';
		}

		return esc_attr( trim( $style ) );
	}

	/**
	 * Logo URL.
	 *
	 * @return string
	 */
	public static function get_logo_url() {
		$logo_id = absint( self::get_setting( 'logo_id', 0 ) );

		if ( ! $logo_id ) {
			return '';
		}

		$url = wp_get_attachment_image_url( $logo_id, 'medium' );
		return $url ? $url : '';
	}

	/**
	 * Category thumbnail URL.
	 *
	 * @param int $term_id Term ID.
	 * @return string
	 */
	public static function get_category_thumbnail_url( $term_id ) {
		$thumbnail_id = absint( get_term_meta( $term_id, 'thumbnail_id', true ) );

		if ( ! $thumbnail_id ) {
			return '';
		}

		$url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );
		return $url ? $url : '';
	}

	/**
	 * Managed page.
	 *
	 * @return int
	 */
	public static function get_menu_page_id() {
		return absint( self::get_setting( 'menu_page_id', 0 ) );
	}

	/**
	 * Current menu page.
	 *
	 * A page counts as the standalone, theme-independent menu page if either:
	 * - it is the page explicitly picked in settings ("Dedicated Menu Page"), or
	 * - its entire content is just the [wrmp_menu] shortcode (nothing else),
	 *   which makes intent unambiguous even if it was never linked in settings
	 *   (e.g. created manually, or re-created after the original was deleted).
	 *
	 * @return bool
	 */
	public static function is_menu_page() {
		if ( is_admin() || ! is_page() ) {
			return false;
		}

		$page_id = self::get_menu_page_id();

		if ( $page_id && is_page( $page_id ) ) {
			return true;
		}

		$post = get_post();

		return $post instanceof WP_Post && self::is_shortcode_only_content( $post->post_content );
	}

	/**
	 * Whether content is nothing but the [wrmp_menu] shortcode.
	 *
	 * @param string $content Post content.
	 * @return bool
	 */
	protected static function is_shortcode_only_content( $content ) {
		if ( ! has_shortcode( $content, 'wrmp_menu' ) ) {
			return false;
		}

		$stripped = preg_replace( '/<!--\s*\/?wp:[^>]*-->/', '', $content );
		$stripped = wp_strip_all_tags( $stripped );
		$stripped = preg_replace( '/\[wrmp_menu[^\]]*\]/', '', $stripped );

		return '' === trim( $stripped );
	}

	/**
	 * Shortcode presence.
	 *
	 * @return bool
	 */
	public static function has_menu_shortcode() {
		if ( is_admin() || ! is_singular() ) {
			return false;
		}

		$post = get_post();
		return $post instanceof WP_Post && has_shortcode( $post->post_content, 'wrmp_menu' );
	}

	/**
	 * Menu context.
	 *
	 * @return bool
	 */
	public static function is_menu_context() {
		return self::is_menu_page() || self::has_menu_shortcode();
	}
}
