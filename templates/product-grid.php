<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( empty( $products ) ) : ?>
	<p class="wrmp-menu-empty"><?php esc_html_e( 'No products found in this category.', 'woo-resturant-cafe-menu' ); ?></p>
<?php else : ?>
	<div class="wrmp-product-grid">
		<?php foreach ( $products as $product_item ) : ?>
			<?php include WRMP_PATH . 'templates/product-card.php'; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
