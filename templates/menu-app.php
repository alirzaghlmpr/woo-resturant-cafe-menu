<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrmp-menu-shell">
	<div class="wrmp-menu-layout">
		<aside class="wrmp-menu-sidebar">
			<div class="wrmp-menu-brand">
				<?php if ( ! empty( $atts['show_title'] ) && 'yes' === $atts['show_title'] ) : ?>
					<?php if ( $logo_url ) : ?>
						<img class="wrmp-menu-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $restaurant_name ); ?>">
					<?php endif; ?>
					<div class="wrmp-brand-text">
						<h2 class="wrmp-menu-title"><?php echo esc_html( $restaurant_name ); ?></h2>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $settings['instagram_id'] ) || ! empty( $settings['phone_number'] ) ) : ?>
				<div class="wrmp-menu-contact">
					<?php if ( ! empty( $settings['instagram_id'] ) ) : ?>
						<div class="wrmp-contact-item">
							<span class="wrmp-contact-label"><?php esc_html_e( 'Instagram', 'woo-resturant-cafe-menu' ); ?></span>
							<span class="wrmp-contact-value">@<?php echo esc_html( ltrim( $settings['instagram_id'], '@' ) ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $settings['phone_number'] ) ) : ?>
						<div class="wrmp-contact-item">
							<span class="wrmp-contact-label"><?php esc_html_e( 'Phone', 'woo-resturant-cafe-menu' ); ?></span>
							<span class="wrmp-contact-value"><?php echo esc_html( $settings['phone_number'] ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $categories ) ) : ?>
				<div class="wrmp-menu-nav" role="tablist" aria-label="<?php esc_attr_e( 'Menu Categories', 'woo-resturant-cafe-menu' ); ?>">
					<?php foreach ( $categories as $category ) : ?>
						<button
							type="button"
							class="wrmp-menu-tab<?php echo $active_category && (int) $active_category['term_id'] === (int) $category['term_id'] ? ' is-active' : ''; ?>"
							data-term-id="<?php echo esc_attr( $category['term_id'] ); ?>"
							data-children="<?php echo esc_attr( wp_json_encode( $category['children'] ?? array() ) ); ?>"
							role="tab"
							aria-selected="<?php echo $active_category && (int) $active_category['term_id'] === (int) $category['term_id'] ? 'true' : 'false'; ?>"
						>
							<?php if ( ! empty( $category['thumbnail_url'] ) ) : ?>
								<img class="wrmp-menu-tab-thumb" src="<?php echo esc_url( $category['thumbnail_url'] ); ?>" alt="">
							<?php endif; ?>
							<span class="wrmp-menu-tab-text"><?php echo esc_html( $category['name'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</aside>

		<div class="wrmp-menu-content">
			<?php if ( empty( $categories ) ) : ?>
				<p class="wrmp-menu-empty"><?php esc_html_e( 'No menu categories selected yet.', 'woo-resturant-cafe-menu' ); ?></p>
			<?php else : ?>
				<header class="wrmp-menu-content-header">
					<h3 class="wrmp-menu-section-title" data-wrmp-category-title><?php echo esc_html( $active_category ? $active_category['name'] : '' ); ?></h3>

					<div class="wrmp-menu-subnav<?php echo ( $active_category && ! empty( $active_category['children'] ) ) ? ' has-children' : ''; ?>" role="group" aria-label="<?php esc_attr_e( 'Sub-categories', 'woo-resturant-cafe-menu' ); ?>" data-wrmp-subnav>
						<?php if ( $active_category && ! empty( $active_category['children'] ) ) : ?>
							<?php foreach ( $active_category['children'] as $index => $child ) : ?>
								<button type="button" class="wrmp-subchip<?php echo 0 === $index ? ' is-active' : ''; ?>" data-term-id="<?php echo esc_attr( $child['term_id'] ); ?>">
									<span class="wrmp-subchip-text"><?php echo esc_html( $child['name'] ); ?></span>
								</button>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $active_category['description'] ) ) : ?>
						<p class="wrmp-menu-section-description" data-wrmp-category-description><?php echo esc_html( $active_category['description'] ); ?></p>
					<?php else : ?>
						<p class="wrmp-menu-section-description" data-wrmp-category-description></p>
					<?php endif; ?>
				</header>
				<div class="wrmp-menu-grid-wrap" data-wrmp-products>
					<?php include WRMP_PATH . 'templates/product-grid.php'; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
