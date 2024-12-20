<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package landio
 */
defined('ABSPATH') || exit;


if (!function_exists('landio_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function landio_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on landio, use a find and replace
		 * to change 'landio' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('landio', get_template_directory() . '/languages');


		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		add_theme_support('responsive-embeds');


		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => esc_html__('Primary', 'landio'),
		));

		add_theme_support('html5', array('comment-list', 'comment-form', 'gallery','script', 'style'));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		add_image_size( 'uicore-medium', 650, 650, false );

		//Required
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( "title-tag" );

		// Add support for editor styles.
		add_theme_support('editor-styles');

		if (!class_exists('\UiCore\Core')) {
			add_editor_style();
			add_editor_style('https://fonts.googleapis.com/css2?family=Inter%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&display=auto&ver=5.8');
		} else {
			add_theme_support('align-wide');
		}
	}
endif;
add_action('after_setup_theme', 'landio_setup');

