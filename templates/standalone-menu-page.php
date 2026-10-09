<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$restaurant_name = $settings['restaurant_name'] ?? '';
$logo_url        = WRMP_Helpers::get_logo_url();
$atts            = array(
	'show_title' => 'yes',
);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( wp_get_document_title() ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="wrmp-standalone-page<?php echo is_rtl() ? ' rtl' : ''; ?>">
<?php wp_body_open(); ?>
<main class="wrmp-standalone-main">
	<div class="wrmp-menu-page" style="<?php echo WRMP_Helpers::get_inline_style( $settings ); ?>">
		<?php include WRMP_PATH . 'templates/menu-app.php'; ?>
	</div>
</main>
<?php wp_footer(); ?>
</body>
</html>
