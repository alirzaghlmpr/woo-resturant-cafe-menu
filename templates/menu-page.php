<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$restaurant_name = $settings['restaurant_name'] ?? '';
$logo_url        = WRMP_Helpers::get_logo_url();
?>
<div class="wrmp-menu-page" style="--wrmp-primary: <?php echo esc_attr( $settings['primary_color'] ); ?>; --wrmp-secondary: <?php echo esc_attr( $settings['secondary_color'] ); ?>; --wrmp-background: <?php echo esc_attr( $settings['background_color'] ); ?>; --wrmp-text: <?php echo esc_attr( $settings['text_color'] ); ?>; --wrmp-category-card-bg: <?php echo esc_attr( $settings['category_card_background'] ); ?>; --wrmp-category-card-text: <?php echo esc_attr( $settings['category_card_text_color'] ); ?>; --wrmp-category-card-inactive-bg: <?php echo esc_attr( $settings['category_card_inactive_background'] ); ?>; --wrmp-category-card-inactive-text: <?php echo esc_attr( $settings['category_card_inactive_text_color'] ); ?>; --wrmp-category-card-hover-bg: <?php echo esc_attr( $settings['category_card_hover_background'] ); ?>; --wrmp-category-card-hover-text: <?php echo esc_attr( $settings['category_card_hover_text_color'] ); ?>; --wrmp-product-card-bg: <?php echo esc_attr( $settings['product_card_background'] ); ?>; --wrmp-product-card-text: <?php echo esc_attr( $settings['product_card_text_color'] ); ?>; --wrmp-product-name-color: <?php echo esc_attr( $settings['product_name_color'] ); ?>; --wrmp-product-price-color: <?php echo esc_attr( $settings['product_price_color'] ); ?>; --wrmp-product-description-color: <?php echo esc_attr( $settings['product_description_color'] ); ?>;">
	<?php include WRMP_PATH . 'templates/menu-app.php'; ?>
</div>
