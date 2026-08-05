<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article class="wrmp-product-card">
	<?php if ( ! empty( $product_item['image'] ) ) : ?>
		<div class="wrmp-product-image-link">
			<img class="wrmp-product-image" src="<?php echo esc_url( $product_item['image'] ); ?>" alt="<?php echo esc_attr( $product_item['name'] ); ?>">
		</div>
	<?php endif; ?>

	<div class="wrmp-product-body">
		<div class="wrmp-product-meta">
			<h4 class="wrmp-product-title"><?php echo esc_html( $product_item['name'] ); ?></h4>
			<div class="wrmp-product-price"><?php echo wp_kses_post( $product_item['price_html'] ); ?></div>
		</div>
		<div class="wrmp-product-description"><?php echo wp_kses_post( wpautop( $product_item['short_description'] ) ); ?></div>
	</div>
</article>
