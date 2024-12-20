<?php
add_action('template_redirect', 'restrict_domain');
function restrict_domain() {
    if ($_SERVER['HTTP_HOST'] !== 'aadhyainnovations.com') {
       
        exit;
    }
}
/**
 * landio functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package landio
 */
defined('ABSPATH') || exit;

//Global Constants
define('UICORE_THEME_VERSION', '2.0.0');
define('UICORE_THEME_NAME', 'Landio');
define('UICORE_FRAMEWORK_VERSION', '4.1.0');


$landio_includes = array(
	'/setup.php',
	'/default.php',
	'/template-tags.php',
	'/plugin-activation.php'
);

foreach ($landio_includes as $file) {
	require_once get_template_directory() . '/inc' . $file;
}


//Required
if ( ! isset( $content_width ) ) {
	$content_width = 1000;
}
if ( is_singular() ) {
	wp_enqueue_script( "comment-reply" );
}


add_filter('uicore_settings_default_admin', 'uicore_default_admin_options');
function uicore_default_admin_options($default) {
	$settings = array(
		'pFont' => [
						'f' => 'DM Sans',
						'st' => '700',
					],
					'sFont' => [
						'f' => 'DM Sans',
						'st' => '700',
					],
					'tFont' => [
						'f' => 'DM Sans',
						'st' => '500',
					],
					'aFont' => [
						'f' => 'DM Sans',
						'st' => '500',
					],
	);
	return wp_parse_args($settings, $default);
}

add_filter('uicore_settings_default_front', 'uicore_default_front_options');
function uicore_default_front_options($default) {
	$settings = [
		'pColor'					=> '#FF5D0C',
		'sColor'					=> '#1D1D1F',
		'aColor'					=> '#D1345B',
		'hColor'					=> '#0B0A27',
		'bColor'					=> '#6E7A84',
		'dColor'					=> '#1D1D1F',
		'lColor'					=> '#F8FCFC',
		'logo'						=> 'https://landio.uicore.co/saas/wp-content/uploads/sites/2/2021/08/landio-logo.webp',
		'fav'						=> 'https://landio.uicore.co/saas/wp-content/uploads/sites/2/2021/08/landio-favicon.webp',
		'pagetitle_bg' 				=> [
			'type' 			=> 'gradient',
			'solid' 		=> 'Light Neutral',
			'gradient' 		=> [
				'angle' 		=> '85',
				'color1' 		=> '#CBE7E5',
				'color2' 		=> '#FAF4F3',
			],
			'image' 		=> [
				'url' 			=> '',
				'attachment' 	=> 'scroll',
				'position' 		=> [
					'd' => 'bottom center',
					't' => 'center center',
					'm' => 'center center',
				],
				'repeat' 		=> 'no-repeat',
				'size' 			=> [
					'd' => 'cover',
					't' => 'cover',
					'm' => 'contain',
				],
			],
		],
		'pagetitle_color'			=> 'Dark Neutral'
	];
	return wp_parse_args($settings, $default);
}

function ui_disable_plugin_updates( $value ) {

    $pluginsToDisable = [
        'bdthemes-element-pack/bdthemes-element-pack.php',
        'metform-pro/metform-pro.php'
    ];

    if ( isset($value) && is_object($value) ) {
        foreach ($pluginsToDisable as $plugin) {
            if ( isset( $value->response[$plugin] ) ) {
                unset( $value->response[$plugin] );
            }
        }
    }
    return $value;
}
add_filter( 'site_transient_update_plugins', 'ui_disable_plugin_updates' );