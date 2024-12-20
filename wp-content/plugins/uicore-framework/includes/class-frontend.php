<?php
namespace UiCore;

use UiCore\WooCommerce\Frontend as WooCommerceFrontend;

defined('ABSPATH') || exit();


/**
 * Frontend ui and functions
 *
 * @author Andrei Voica <andrei@uicore.co
 * @since 1.0.0
 */
class Frontend
{

    private $assets_version;

    /**
     * Construct Frontend
     *
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function __construct()
    {

        //make sure he have all the settings stored in one transient
        //this will be there except first time or if you clear all your transients
        $this->google_fonts = get_option('uicore_fonts');
        $this->assets_version = Helper::get_option('settings_version', false);
        $this->css_check();

        //404 Redirect
        add_action('template_redirect', [$this, 'custom_404_redirect']);

        //maintenance Redirect
        if (Helper::get_option('gen_maintenance') === 'true') {
            add_action('template_redirect', [$this, 'maintenance_redirect']);
        }

        $this->disable_cache_if_multilingual();

        //Include frontend classes file
        $this->frontend_includes();

        //Initiate all the frontend Classes
        $this->frontend_render();

        //Enque general scripts and style
        add_action('wp_enqueue_scripts', [$this, 'frontend_css'], 50);

        //Add Theme Color
        if (Helper::get_option('gen_themecolor') == 'true') {
            add_action('wp_head', [$this, 'add_theme_color']);
        }

        //Add Favicon
        add_action('wp_head', [$this, 'add_favicon']);

        //Add Custopm content in Head
        add_action('wp_head', [$this, 'add_head_content'], 2);

        //Add Custopm content in Head
        add_action('wp_footer', [$this, 'add_footer_content'], 99);

        //If Google font url is setted add it to registred style
        // add_action('wp_head', [$this, 'add_preconnect'], 1);

        //Enque scripts in footer
        add_action('wp_footer', [$this, 'add_script_in_footer'], 4);

        //Add custom classes to body
        add_filter('body_class', [$this, 'add_body_class']);

        //add uicore-simple-megamenu class
        add_filter('nav_menu_css_class' , [$this, 'menu_extra_nav_class'] , 10 , 2);

        //Menu Extra Meta
        add_filter( 'walker_nav_menu_start_el', [$this, 'menu_extra'], 10, 4 );

         //maintenance Redirect
         if (Helper::get_option('gen_cursor') === 'true') {
            add_action('wp_footer', [$this, 'custom_cursor']);
        }

    }

    /**
     * Run frontend components
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function frontend_render()
    {
        //Enque Specific Inline Style
        new InlineStyle();

        new Header();
        new PageTitle();
        new Sidebar();
        new Footer();

        new Search();
        new Extras();

        new Animations();
        new Performance();
        
        if (Helper::get_option('disable_blog') === 'false' ){
            new Blog\Frontend();
        }
        if (Helper::get_option('disable_portfolio') === 'false' ){
            new Portfolio\Frontend();
        }
    }

    /**
     * Enqueue frontend css and js
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function frontend_css()
    {
        // new \Elementor\Frontend->enqueue_styles();
        // delete_option('elementor_active_kit');
        wp_enqueue_style( 'elementor-frontend' );
        wp_enqueue_style('uicore_global');
        wp_enqueue_script('uicore_global');

        if('internal' === get_option( 'elementor_css_print_method' )){
            $kit_id = get_option('elementor_active_kit');
            if (class_exists('\Elementor\Plugin')) {
                //Add kit just to be sure it loads on all pages if is inline
                $post_css_file = new \Elementor\Core\Files\CSS\Post($kit_id);
                $fonts = $post_css_file->enqueue();
            }
        }

        if ( is_rtl() ) {
            wp_enqueue_style('uicore_rtl');
        }
    }

    /**
     * Include Frontend Resources
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function frontend_includes()
    {
        $includes = [
            '/templates/header.php', // Header Template
            '/templates/page-title.php', // Page Title Template
            '/templates/sidebar.php', // Sidebar Template
            '/templates/footer.php', // Footer Template
            '/templates/search.php', // Search Comp Template
            '/templates/extras.php', // Frontend Extras
            '/templates/posts.php', // Custom post and Blog post Template
            '/templates/pages.php', // Custom post and Blog post Template
            '/extra/class-inline-style.php', // Inline Style
            '/extra/class-animations.php', // UiCore Animations
            '/extra/class-performance.php', // Performance Manager
        ];

        //loop trough all required files
        foreach ($includes as $file) {
            $filepath = UICORE_INCLUDES . $file;
            if (!$filepath) {
                trigger_error(sprintf('Error locating /inc%s for inclusion', $file), E_USER_ERROR);
            } else {
                require $filepath;
            }
        }
    }

    /**
     * Add Theme Color Meta markup to head
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function add_theme_color()
    {
        $color = Helper::get_option('gen_themecolorcode');

        echo '<meta name="theme-color" content="' . Helper::get_css_color($color) . '" />';
    }

    /**
     * Add preconnect for google fonts
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function add_preconnect()
    {
        echo '<link rel="preconnect" href="//fonts.googleapis.com" crossorigin>'; //CSS
        echo '<link rel="preconnect" href="//fonts.gstatic.com" crossorigin>'; //Font
    }

    /**
     * Add Favicon Meta
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function add_favicon()
    {
        $favicon = Helper::get_option('fav');
        if (!$favicon) {
            $favicon = UICORE_ASSETS . '/img/favicon.png';
        }
        echo '
        <link rel="shortcut icon" href="' .
            $favicon .
            '" >
		<link rel="icon" href="' .
            $favicon .
            '" >
		<link rel="apple-touch-icon" sizes="152x152" href="' .
            $favicon .
            '">
		<link rel="apple-touch-icon" sizes="120x120" href="' .
            $favicon .
            '">
		<link rel="apple-touch-icon" sizes="76x76" href="' .
            $favicon .
            '">
        <link rel="apple-touch-icon" href="' .
            $favicon .
            '">
        ';
    }

    /**
     * Add Custom Js in Footer
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function add_script_in_footer()
    {
        global $post;
        //check if post id is setted if not return 0
        $post_id = $post->ID ?? 0;

        $meta = get_post_meta($post_id, 'page_options', true);
        $script = '';
        if (Helper::isJson($meta)) {
            $meta = json_decode($meta, true);
            if (is_array($meta) && isset($meta['customjs'])) {
                $script = $meta['customjs'];
            }
        }
        if (Helper::get_option("header_top") === 'true' && Helper::get_option('header_top_dismissable') === 'true'){
            $script .="
            if(document.querySelector('#ui-banner-dismiss')){
                if(!localStorage.getItem('uicore_tb') || localStorage.getItem('uicore_tb') != '".Helper::get_option('header_top_token')."'){
                    document.querySelector('#ui-banner-dismiss').addEventListener('click', function(event){
                        jQuery('.uicore-top-bar').slideToggle();
                        jQuery('.uicore-navbar.uicore-sticky').animate({top:0});
                        localStorage.setItem('uicore_tb', '".Helper::get_option('header_top_token') ."');
                    });
                }
            }
            ";
        }

        echo "<script> \n";
        echo $script;
        echo "var uicore_frontend = {'back':'". _x('Back', 'Frontend - Mobile submenu', 'uicore-framework') ."', 'rtl' : '".is_rtl()."','mobile_br' : '".Helper::get_option('mobile_breakpoint')."'};";
        echo "\n console.log( 'Using ". UICORE_THEME_NAME . " v.". UICORE_THEME_VERSION . "');";
        echo "\n console.log( 'Powered By UiCore Framework v.". UICORE_VERSION . "');";
        // echo "window.MSInputMethodContext && document.documentMode && document.write('<script src=\"https://cdn.jsdelivr.net/gh/nuxodin/ie11CustomProperties@4.1.0/ie11CustomProperties.min.js\"><\/script>');";
        echo "\n </script> ";
    }

    /**
     * 404 Page Redirect
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function custom_404_redirect()
    {
        global $wp_query, $post;

        // check if is a 404 error, and it's on your jobs custom post type
        if (is_404() && Helper::get_option('gen_maintenance') != 'true') {
            $wp_query->is_singular = true;
            $wp_query->is_single = false;
            $wp_query->is_category = false;

            $page = Helper::get_option('gen_404');
            if (isset($page['id']) && $page['id'] == '0') {
                $wp_query->is_404 = true;
            } else {
                $post = get_post(Helper::get_option('gen_404')['id']);
                $wp_query->is_404 = false;
                $wp_query->queried_object = $post;
                $wp_query->queried_object_id = $post->ID;
                $wp_query->query_vars['page_id'] = $post->ID;
                $wp_query->is_page = true;
            }

            $wp_query->post_count = 1;
            $wp_query->current_post = -1;
            $wp_query->posts = [$post];
        }
    }

    /**
     * Maintenance Page Redirect
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function maintenance_redirect()
    {
        // check if is a 404 error, and it's on your jobs custom post type
        if (!is_user_logged_in()) {
            // TODO: Add a frontend notice for that
            global $wp_query, $post;
            $page = Helper::get_option('gen_maintenance_page');

            if (isset($page['id'])) {
                $wp_query->is_page = true;
                $wp_query->is_single = false;
                $wp_query->is_home = false;
                $wp_query->is_singular = false;
                $wp_query->is_category = false;
                $wp_query->is_404 = false;
                $wp_query->post_count = 0;
                $wp_query->current_post = -1;

                if ($page['id'] != '0') {
                    $post = get_post($page['id']);

                    $wp_query->post_count = 1;
                    $wp_query->current_post = -1;
                    $wp_query->posts = [$post];

                    $wp_query->queried_object = $post;
                    $wp_query->queried_object_id = $page['id'];
                } else {
                    $wp_query->posts = [];
                    include get_template_directory() . '/maintenance.php';
                    exit();
                }
            }
        }

        return null;
    }

    /**
     * Add Conditional body classes
     *
     * @param array $classes
     * @return array $classes
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    public function add_body_class(array $classes)
    {
        global $post;

        if (isset($post->ID)) {
            $site_layout = Helper::po('layout', 'gen_layout', 'full width', $post->ID);
        } else {
            $site_layout = Helper::get_option('gen_layout');
        }

        $sticky_top_bar = Helper::get_option('header_top_sticky') === 'true' ? 'uicore-sticky-tb' : null ;
        $hamburger_menu = strpos(Helper::get_option('header_layout'), 'ham') !== false ? 'uicore-is-ham' : null ;
        $menu_focus = Helper::get_option('menu_focus') === 'true' ? 'uicore-menu-focus' : null ;

        $newclasses = [
            $site_layout == 'boxed' ? 'uicore-boxed' : null,
            $sticky_top_bar,
            $hamburger_menu,
            $menu_focus
        ];

        return array_merge($classes, $newclasses);
    }
    function menu_extra_nav_class($classes, $item){

        if($item->mega == '1'){
            $mega_type = get_post_meta( $item->ID, '_menu_item_mega-type', true );
            if($mega_type){
                $classes[] = 'uicore-'.$mega_type;
            }
            $classes[] = 'uicore-simple-megamenu';
        }
		if(get_post_meta($item->ID, '_menu_item_icon-placement', true ) === 'right') {
			$classes[] = 'ui-icon-right';
		}
        if(!empty( $item->description )){
            $classes[] = 'ui-has-description';
        }
        if(Helper::get_option('menu_active') === 'false'){
            $classes = array_diff($classes, ['current-menu-item'] );
        }

        return $classes;
    }

    function disable_cache_if_multilingual()
    {
        if(function_exists('icl_object_id')  || function_exists('pll_the_languages') ){
            add_filter('uicore-menu-cache', '__return_false');
            add_filter('uicore-footer-cache', '__return_false');
        }
    }
	function css_check()
	{
		if($this->assets_version === false || $this->assets_version == '0' || defined('UICORE_LOCAL_CSS')){
			Settings::clear_cache();
		}
	}

    function menu_extra( $item_output, $item, $depth, $args ) {
        if ( !empty( $item->description ) ) {
            if($depth === 0){
                $item_output = str_replace( $args->link_after , '<div class="custom-menu-desc">' . $item->description . '</div>' . $args->link_after, $item_output );
            }else{
                $item_output = str_replace( $args->link_after , $args->link_after . '<span class="custom-menu-desc">' . $item->description . '</span>', $item_output );
            }
        }
        $img = get_post_meta($item->ID, '_menu_item_img', true);
        if($img){
            $item_output = str_replace('<span class="ui-menu-item-wrapper">', wp_get_attachment_image($img, 'thumbnail', '', ["class" => "ui-menu-img" ]) .'<span class="ui-menu-item-wrapper">', $item_output);
        }
        $icon = get_post_meta($item->ID, '_menu_item_icon', true );
        if($icon){
            $icon_placement = get_post_meta($item->ID, '_menu_item_icon-placement', true );
            $icon_color = get_post_meta($item->ID, '_menu_item_icon-color', true );
            if($icon_placement === 'right'){
                $item_output = str_replace( $args->link_after , $args->link_after . Data::get_menu_icons($icon,$icon_color) , $item_output );
            }else{
                $item_output = str_replace('<span class="ui-menu-item-wrapper">', Data::get_menu_icons($icon,$icon_color) .'<span class="ui-menu-item-wrapper">', $item_output);
            }
        }
        $badge = get_post_meta($item->ID, '_menu_item_badge', true );
        if($badge){
            $badge_color = get_post_meta($item->ID, '_menu_item_badge-color', true );
            $item_output = str_replace( $args->link_after , $args->link_after . '<span class="ui-badge" style="--ui-badge-color:'.Helper::get_css_color($badge_color, 'Primary').'">' . $badge . '</span>', $item_output );
        }

        return $item_output;
    }

    function add_head_content()
    {
        echo Helper::get_option('header_content');
    }
    function add_footer_content()
    {
        echo Helper::get_option('footer_content');
    }
    function custom_cursor()
    {
        echo '<div class="ui-cursor ui-cursor-main"></div>';
    }
}