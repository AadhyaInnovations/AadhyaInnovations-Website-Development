<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package landio
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<section class="utility-page maintenance-page">
				<img src="<?php echo get_template_directory_uri() ?>/assets/img/bg-404.png" class="error-404-img">
				<h1 class="maintenance-title"><?php esc_html_e('The site is currently down for maintenance.', 'landio'); ?></h1>
				<p><?php esc_html_e('We apologise for any incoveniences caused.', 'landio'); ?></p>
			</section><!-- .utility-page -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
