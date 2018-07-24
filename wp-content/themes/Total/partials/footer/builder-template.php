<?php
/**
 * Template used for the VC live editor when modifying your footer builder content
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.5
 * @deprecated 4.5 - the theme will redirect to single-templatera.php
 */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?><?php wpex_schema_markup( 'body' ); ?>>

<?php get_footer(); ?>