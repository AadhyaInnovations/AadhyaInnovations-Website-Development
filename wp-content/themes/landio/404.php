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
			<section class="utility-page">
				<img src="<?php echo get_template_directory_uri() ?>/assets/img/bg-404.png" class="error-404-img">
				<h1><?php esc_html_e('Error 404', 'landio'); ?></h1>
				<p><?php esc_html_e('We can’t seem to find the page you’re looking for.', 'landio'); ?></p>
				<a class="default-button" href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e('Go back to homepage', 'landio'); ?></a>
			</section><!-- .utility-page -->
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
