<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package landio
 */
defined('ABSPATH') || exit;


if (!class_exists('\UiCore\Core')) {
	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	function landio_widgets_init()
	{
		register_sidebar(
			array(
				'name' => esc_attr__( 'Left Sidebar', 'landio' ),
				'id'            => 'left-sidebar',
				'before_widget' => '<div class="uicore-sidebar-element">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="ui-widget-title">',
				'after_title' => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name' => esc_attr__( 'Right Sidebar', 'landio' ),
				'id'            => 'right-sidebar',
				'before_widget' => '<div class="uicore-sidebar-element">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="ui-widget-title">',
				'after_title' => '</h4>',
			)
		);
	}
	add_action('widgets_init', 'landio_widgets_init');


	/**
	 * Custom template tags for this theme.
	 */
	require get_template_directory() . '/inc/template-tags.php';


	/**
	 * Proper way to enqueue scripts and styles
	 */
	function landio_default_theme()
	{
		wp_enqueue_style('landio', get_stylesheet_uri(), array(), '0.6.0');
		wp_enqueue_style('landio-icons', get_template_directory_uri() . '/assets/fonts/uitheme-icons.css');
		wp_enqueue_style('landio-font', "https://fonts.googleapis.com/css2?family=Inter%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&display=auto&ver=5.8");
		wp_enqueue_script('landio-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'),'0.6.0', true);
	}
	add_action('wp_enqueue_scripts', 'landio_default_theme');

	function landio_editor_style_for_page()
	{
		echo '<style id="uicore-editor" >
				.post-type-post .editor-styles-wrapper [data-block],
				.post-type-post .editor-styles-wrapper .wp-block {
					max-width: 625px !important;
				}
				.edit-post-layout__content .edit-post-visual-editor{
					padding-top:0;
				}
			</style>';
	}
	add_action('admin_head', 'landio_editor_style_for_page');

	function landio_body_classes($classes)
	{
		if (is_active_sidebar('left-sidebar')) {
			$classes[] = 'left-sidebar';
		}
		if (is_active_sidebar('right-sidebar')) {
			$classes[] = 'right-sidebar';
		}
		return $classes;
	}
	add_filter('body_class', 'landio_body_classes');

	/**
	 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
	 */
	function landio_pingback_header()
	{
		if (is_singular() && pings_open()) {
			printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
		}
	}
	add_action('wp_head', 'landio_pingback_header');

	function landio_excerpt_length($length)
	{
		return 17;
	}
	add_filter('excerpt_length', 'landio_excerpt_length', 999);

	function landio_pagination_style($default){
		$args = array(
			'before'           => '<nav aria-label="'.esc_attr__('Posts navigation', 'landio') .'" class="ui-pagination ui-pages"> <ul>',
			'after'            => '</ul></nav>',
			'link_before'      => '<li class="ui-page-item">',
			'link_after'       => '</li>',
			'aria_current'     => 'page',
			'next_or_number'   => 'number',
			'separator'        => ' ',
			'nextpagelink'     => esc_attr__( 'Next page', 'landio' ),
			'previouspagelink' => esc_attr__( 'Previous page', 'landio'  ),
			'pagelink'         => '%',
			'echo'             => 1,
		);

		$parsed_args = wp_parse_args( $args, $default );

		return $parsed_args;
	}
	add_filter('wp_link_pages_args', 'landio_pagination_style');

	function landio_pagination()
	{

		//global $query;
		$args = wp_parse_args(
			array(
				'mid_size'           => 2,
				'prev_next'          => true,
				'prev_text'          => esc_attr__('', 'landio'),
				'next_text'          => esc_attr__('', 'landio'),
				'screen_reader_text' => esc_attr__('Posts navigation', 'landio'),
				'type'               => 'array',
				'current'            => max(1, get_query_var('paged')),
				'base' => get_pagenum_link(1) . '%_%',
			)
		);

		$links = paginate_links($args);
		if (is_array($links) || is_object($links)) {
?>
			<nav aria-label="<?php echo esc_attr($args['screen_reader_text']); ?>" class="ui-pagination">
				<ul>
					<?php
					foreach ($links as $key => $link) {
					?>
						<li class="ui-page-item <?php echo strpos($link, 'current') ? 'ui-active' : ''; ?>">
							<?php echo str_replace('page-numbers', 'ui-page-link', $link); ?>
						</li>
					<?php
					}
					?>
				</ul>
			</nav>
			<?php
		}
	}


	function landio_page_title()
	{
		echo '<h1>';
		if (is_search()) {
            echo sprintf(
                esc_html__('Search Results for: %s', 'landio'),
                '<span>' . get_search_query() . '</span>'
			);
		} else if (is_home()) {
			echo esc_html__('Latest Posts', 'landio');
		} else if ((is_category() || is_day() || is_month() || is_author() || is_year() || is_tag())) {
			the_archive_title();
		} else if (is_singular()) {
			the_title();
		}
		if (class_exists('WooCommerce')) {
			if (is_product_taxonomy()) {
				single_cat_title();
			}
			if (is_shop()) {
				esc_html_e('Shop', 'landio');
			}
			// if (is_product()) {
			// 	the_title();
			// }
		}
		echo '</h1>';
	}

	if (class_exists('WooCommerce')) {
		function landio_woocommerce_setup()
		{
			add_theme_support('woocommerce');
			add_theme_support('wc-product-gallery-zoom');
			add_theme_support('wc-product-gallery-lightbox');
			add_theme_support('wc-product-gallery-slider');
		}
		add_action('after_setup_theme', 'landio_woocommerce_setup');

		function landio_woo_widgets_init()
		{
			register_sidebar(
				array(
					'name' => esc_attr__( 'Product Sidebar', 'landio' ),
					'id'            => 'product-sidebar',
					'before_widget' => '<div class="uicore-sidebar-element %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h4 class="ui-widget-title">',
					'after_title' => '</h4>',
				)
			);
			register_sidebar(
				array(
					'name' => esc_attr__( 'Shop Sidebar', 'landio' ),
					'id'            => 'shop-sidebar',
					'before_widget' => '<div class="uicore-sidebar-element %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h4 class="ui-widget-title">',
					'after_title' => '</h4>',
				)
			);
		}
		add_action('widgets_init', 'landio_woo_widgets_init');

		//remove woo default sidebar
		// remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		//remove woo default breadcrumb
		remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
		//remove woo default page title
		add_filter('woocommerce_show_page_title', '__return_false', 20);

		//Zoom wrapper
		add_action('woocommerce_before_shop_loop_item', function () {
			echo '<div class="uicore-zoom-wrapper">';
		}, 10);
		add_action('woocommerce_before_shop_loop_item_title', function () {
			echo '</div>';
		}, 10);

		//Add to cart&price hover effect
		remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
		add_action('woocommerce_after_shop_loop_item', function () {
			echo '<div class="uicore-reveal-wrapper"><div class="uicore-reveal">';
		}, 6);
		add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 8);
		add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 9);
		add_action('woocommerce_after_shop_loop_item', function () {
			echo '</div></div>';
		}, 11);

		//Wrap the woo pages
		remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
		remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
		if (!function_exists('uicore_wrapper_before')) {
			function uicore_wrapper_before()
			{
			?>
				<div id="primary" class="content-area">
					<main class="uicore-container alt-container">
						<?php
						if (is_product() && is_active_sidebar('product-sidebar')) {
						?>
							<aside class="left-widget-area uicore-col-lg-3">
								<?php dynamic_sidebar('product-sidebar'); ?>
							</aside>
						<?php } elseif (!is_product() && is_active_sidebar('shop-sidebar')) {
						?>
							<aside class="left-widget-area uicore-col-lg-3">
								<?php dynamic_sidebar('shop-sidebar'); ?>
							</aside>
						<?php } ?>
						<div class="uicore-row">
						<?php
					}
				}
				add_action('woocommerce_before_main_content', 'uicore_wrapper_before');
				if (!function_exists('uicore_wrapper_after')) {
					function uicore_wrapper_after()
					{
						?>

						</div>
					</main>
	<?php
					}
				}
				add_action('woocommerce_after_main_content', 'uicore_wrapper_after');
			}
		}
