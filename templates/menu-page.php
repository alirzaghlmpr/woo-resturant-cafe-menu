<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$restaurant_name = $settings['restaurant_name'] ?? '';
$logo_url        = WRMP_Helpers::get_logo_url();
?>
<div class="wrmp-menu-page" style="<?php echo WRMP_Helpers::get_inline_style( $settings ); ?>">
	<?php include WRMP_PATH . 'templates/menu-app.php'; ?>
</div>
