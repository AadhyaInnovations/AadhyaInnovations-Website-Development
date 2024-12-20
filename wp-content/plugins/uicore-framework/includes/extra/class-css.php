<?php
namespace UiCore;

defined('ABSPATH') || exit();

/**
 *  CSS Util
 */
class CSS
{
    private $settings;
    private $br;
    private $global_animations;
    public $files;
    public $css;

    /**
     * Apply the filter to get the class (disabled by default)
     *
     * @param string $item
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function __construct($settings)
    {
        $this->settings = $settings;
        if (class_exists('Elementor')) {
            $br_points = \Elementor\Core\Responsive\Responsive::get_breakpoints();
        } else {
            $br_points = [
                'sm' => '480',
                'md' => '767',
                'lg' => '1024',
            ];
        }
        $this->br = $br_points;
        $this->global_animations = ($settings['animations'] === 'true' && $settings['performance_animations'] === 'true');

        $this->get_the_css_parts();
        $this->get_css_from_settings();
        $this->combine_css();
    }

    /**
     * Add css files parts
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function get_the_css_parts()
    {

        //Blog
        $this->files['blog'][] = UICORE_PATH . '/assets/css/blog.css';
        $this->files['blog'][] = UICORE_PATH . '/assets/css/blog/grid.css';
        if($this->settings['blog_item_style'] != 'simple'){
            $type = str_replace(' ', '-', $this->settings['blog_item_style'] );
            $this->files['blog'][] = UICORE_PATH . '/assets/css/blog/item-style-'.$type.'.css';
        }
        //simple pagetitle breacrumb style fallback
        if($this->settings['pagetitle'] === 'false' && $this->settings['blogs_title'] === 'simple page title' && $this->settings['blogs_breadcrumb'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/page-title.css';
        }

        //Portfolio
        $this->files['portfolio'][] = UICORE_PATH . '/assets/css/portfolio.css';
        $this->files['portfolio'][] = UICORE_PATH . '/assets/css/portfolio/grid.css';


        $this->files['global'][] = UICORE_PATH . '/assets/css/frontend.css';

        //Top Banner
        if($this->settings['header_top'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/top-banner.css';
        }
        //Header
        if($this->settings['header'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/header.css';
        }
        //Page title
        if($this->settings['pagetitle'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/page-title.css';
        }
        //Footer
        if($this->settings['footer'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/footer.css';
        }
        //Copyrights
        if($this->settings['copyrights'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/copyrights.css';
        }
        //Animations
        if($this->settings['performance_animations'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/animations.css';
        }
        //sidebars
        if($this->settings['performance_widgets'] === 'true'){
            $this->files['global'][] = UICORE_PATH . '/assets/css/global/sidebars.css';
        }

        //Woocommerce
        if(class_exists('WooCommerce')){
            $this->files['global'][] = UICORE_PATH . '/assets/css/woocommerce.css';
        }

        $upload_dir = wp_upload_dir();
        $kit_id = get_option('elementor_active_kit');
        $this->files['global'][] = $upload_dir['basedir'].'/elementor/css/post-'.$kit_id.'.css';

        //TODO: Move what we need to keep from this to core!!!
		//Theme Skin
        // if(defined('UICORE_THEME_NAME') && \UICORE_THEME_NAME !== 'Brisk'){
        //     $skins = [
        //         'top_banner',
        //         'mobile_navbar',
        //         'sidebars',
        //         'page_title',
        //         'footer',
        //         'blog',
        //         'portfolio',
        //         'ham',
        //         'back_to_top',
        //         'link',
        //     ];
        //     foreach($skins as $skin){
        //         $this->add_skin($skin);
        //     }
        // }


		//Icons ( Skin or default );

		// if(defined('UICORE_THEME_NAME') && file_exists($icons_path = get_template_directory() . '/assets/skin/' . str_replace(' ', '', strtolower(UICORE_THEME_NAME)) .'/icons.css')){
		// 	$this->files['global'][] = $icons_path;
		// }else{
			$this->files['global'][] = UICORE_PATH . '/assets/fonts/uicore-icons.css';
		// }

    }

    /**
     * Get css content for all category [Theme Options]
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function get_css_from_settings()
    {
        //global includes WooCommerce
        $this->css['global'] = $this->global_css();
        $this->css['blog'] = $this->blog_css();
        $this->css['portfolio'] = $this->portfolio_css();
    }

    /**
     * Combine and minify files and Theme Options css
     *
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since [currentVersion]
     */
    function combine_css()
    {
        foreach($this->files as $type=>$files){

            do_action('uicore_before_generate_'.$type.'_css', $files);

            $available_files = [];
            foreach($files as $file){
                if(@is_file($file)){
                   $available_files[] = $file;
                }
            }

            $minifier = new \MatthiasMullie\Minify\CustomCSS();
			// $minifier = new \MatthiasMullie\Minify\CSS();

            if(count($available_files)){
                $minifier->addFile($available_files);
            }

            if(array_key_exists($type,$this->css)){
                $minifier->add($this->css[$type]);
            }

            $upload_dir = wp_upload_dir();
            $file = $upload_dir['basedir']."/uicore-".$type.'.css';
            
            $content = $minifier->minify();
            $content = str_replace('uicore-icons.' , \UICORE_ASSETS.'/fonts/uicore-icons.',$content);
            $content = str_replace('99999999999px' , $this->settings['mobile_breakpoint'].'px',$content);
            $this->save($content,$file);

            do_action('uicore_after_generate_'.$type.'_css', $file,$content);
        }

    }

    function save($data,$path)
    {
        $fp = fopen($path, 'w');
        fwrite($fp, $data);
        fclose($fp);
    }

    /**
     * Get Global Theme Options css
     *
     * @return string css markup
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function global_css()
    {
        $global_animations = $this->global_animations;
        $json_settings = $this->settings;
        $br_points = $this->br;
        $translateEase = 'cubic-bezier(0.22, 1, 0.36, 1)';
        $opacityEase = 'cubic-bezier(0, 0, 0.2, 1)';
        $css = null;

        //Top Banner
        if($json_settings['header_top'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/top-banner-css.php';
        }
        //Header
        if($json_settings['header'] === 'true'){
          include UICORE_INCLUDES .'/extra/style/header-css.php';

            //Drawer
            if($json_settings['header_side_drawer'] === 'true'){
                include UICORE_INCLUDES .'/extra/style/drawer-css.php';
            }
        }
        //Page title
        if($json_settings['pagetitle'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/page-title-css.php';
        }
        //Footer
        if($json_settings['footer'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/footer-css.php';
        }
        //Copyrights
        if($json_settings['copyrights'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/copyrights-css.php';
        }

        //Sidebar
        if($json_settings['performance_widgets'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/sidebar-css.php';
        }

        //typo
        include UICORE_INCLUDES .'/extra/style/typography-css.php';

        //Animations
        if($json_settings['animations'] === 'true' && $json_settings['performance_animations'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/animations-css.php';
        }
        //force disable all animations
        if($json_settings['performance_animations'] === 'false'){
            $css .= '
            .animated {
                animation: unset !important;
            }
            .elementor-invisible {
                visibility: visible;
            }
            ';
        }

        //Global
        include UICORE_INCLUDES .'/extra/style/global-css.php';

        if($json_settings['gen_cursor'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/cursor-css.php';
        }

        if($json_settings['button_interaction'] != 'none'){
            $css_selectors = str_replace('{{WRAPPER}}','', Elementor\Core::get_buttons_class());
            include UICORE_INCLUDES .'/extra/style/buttons-css.php';
        }

        if(class_exists('WooCommerce')){
            include UICORE_INCLUDES .'/extra/style/woo-css.php';
        }

        if(function_exists('tutor_lms')){
            include UICORE_INCLUDES .'/extra/style/tutor-lms-css.php';
        }
        if(defined( 'AWSM_JOBS_PLUGIN_VERSION' )){
            include UICORE_INCLUDES .'/extra/style/wp-job-css.php';
        }
        //Custom CSS
        $css .= $json_settings['customcss'];

        return $css;

    }

    /**
     * Get Blog Theme Options css
     *
     * @return string css markup
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function blog_css()
    {
        $global_animations = $this->global_animations;
        $json_settings = $this->settings;
        $br_points = $this->br;
        $css = null;

        include UICORE_INCLUDES .'/extra/style/blog-css.php';

        if($json_settings['blogs_author_box'] === 'true'){
            include UICORE_INCLUDES .'/extra/style/blog-author_box-css.php';
        }
        if($json_settings['blogs_related'] === 'true' && $json_settings['blogs_related_style'] === 'list' ){
            include UICORE_INCLUDES .'/extra/style/blog-related_posts-css.php';
        }
        return $css;

    }

    /**
     * Get Portfolio Theme Options css
     *
     * @return string css markup
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function portfolio_css()
    {
        $global_animations = $this->global_animations;
        $json_settings = $this->settings;
        $br_points = $this->br;
        $css = null;

        include UICORE_INCLUDES .'/extra/style/portfolio-css.php';
        return $css;

    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $condition
     * @param mixed $return
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function iff($condition, $return)
    {
        if(strlen($condition) != 0 ){
            return $return;
        }else{
            return null;
        }
    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $fam
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function fam($fam)
    {

        switch ($fam) {
            case "Primary":
                $font = 'var(--e-global-typography-uicore_primary-font-family)';
                break;
            case "Secondary":
                $font = 'var(--e-global-typography-uicore_secondary-font-family)';
                break;
            case "Text":
                $font = 'var(--e-global-typography-uicore_text-font-family)';
                break;
            case "Accent":
                $font = 'var(--e-global-typography-uicore_accent-font-family)';
                break;
            default :
                $font = $fam;
        }
        return $font;

    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $for
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function st($for)
    {
        if (strpos($for['st'], 'italic') !== false) {
            return 'italic';
        } else {
            return 'normal';
        }
    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $for
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function wt($for)
    {
        if ((strpos($for['st'], 'regular') !== false) ||(strpos($for['st'], 'normal') !== false)) {
            return 'normal';
        } else {
            if (strlen(str_replace('italic', '', $for['st'])) < 2) {
                return 'normal';
            } else {
                return str_replace('italic', '', $for['st']);
            }
        }
    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $color
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function color($color)
    {
        //Color + Blur Migrate support
        if(!is_string($color) && isset($color['color'])){
            $color = $color['color'];
        }
        if ($color == 'Primary') {
            $color = 'var(--e-global-color-uicore_primary)';
        } else if ($color == 'Secondary') {
            $color = 'var(--e-global-color-uicore_secondary)';
        } else if ($color == 'Accent') {
            $color = 'var(--e-global-color-uicore_accent)';
        } else if ($color == 'Headline') {
            $color = 'var(--e-global-color-uicore_headline)';
        } else if ($color == 'Body') {
            $color = 'var(--e-global-color-uicore_body)';
        } else if ($color == 'Dark Neutral') {
            $color = 'var(--e-global-color-uicore_dark)';
        } else if ($color == 'Light Neutral') {
            $color = 'var(--e-global-color-uicore_light)';
        } else if ($color == 'White') {
            $color = 'var(--e-global-color-uicore_white)';
        }
        return $color;
    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $prop
     * @param mixed $class
     * @param mixed $media_query
     * @param mixed $responsive
     * @param mixed $br
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function background($prop, $class, $media_query = false, $responsive = false, $br = null)
    {
        $css = '';
        if($media_query){
                $css .= "\n".'@media only screen and ('.$media_query.') { '."\n";
        }
        if ($prop['type'] == 'solid') {
            $css .= $class .' {
                background-color: ' .
                $prop['solid'] .
                ';
            ';

            if(isset($prop['blur']) && $prop['blur'] === 'true'){
                $css .='
                    backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);';
            }

            $css .= '}';
        }elseif ($prop['type'] == 'gradient') {
            $css .= $class .' {
                background-image: linear-gradient(' .
                $prop['gradient']['angle'] .
                'deg,' .
                $prop['gradient']['color1'] .
                ', ' .
                $prop['gradient']['color2'] .
                ');
            } ';
        }elseif ($prop['type'] == 'image') {
            $css .= $class .' {
                background: url(' .
                $prop['image']['url'] .
                ') ' .
                $prop['image']['position']['d'] .
                '/' .
                $prop['image']['size']['d'] .
                ' ' .
                $prop['image']['repeat'] .
                ' ' .
                $prop['image']['attachment'] .
                ' ' .
                $this->color($prop['solid']) .
                ';
            } ';
            if($responsive){
                $css .= '
                @media (max-width: ' . $br['lg'] . 'px) {'
                    . $class .' {
                        background: url(' .
                        $prop['image']['url'] .
                        ') ' .
                        $prop['image']['position']['t'] .
                        '/' .
                        $prop['image']['size']['t'] .
                        ' ' .
                        $prop['image']['repeat'] .
                        ' ' .
                        $prop['image']['attachment'] .
                        ' ' .
                        $this->color($prop['solid']) .
                        ';
                    }

                }
                @media (max-width: ' . $br['md'] . 'px) {
                    '
                    . $class .' {
                        background: url(' .
                        $prop['image']['url'] .
                        ') ' .
                        $prop['image']['position']['m'] .
                        '/' .
                        $prop['image']['size']['m'] .
                        ' ' .
                        $prop['image']['repeat'] .
                        ' ' .
                        $prop['image']['attachment'] .
                        ' ' .
                        $this->color($prop['solid']) .
                        ';
                    }
                }
                ';
            }

        }else{
            $css .= $class . ' {
                background: ' . $this->color($prop['type']) . ';
            }';
        }

        if($media_query){
            $css .= "\n".' }'."\n";
        }
        return $css;
    }

    /**
     * Helper function used inside css.php files
     *
     * @param mixed $type
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function grid_animation($type)
    {

        if(!$this->global_animations){
            return;
        }
        $css = null;
        $json_settings = $this->settings;
        $translateEase = 'cubic-bezier(0.22, 1, 0.36, 1)';
        $opacityEase = 'cubic-bezier(0, 0, 0.2, 1)';

        if($json_settings['animations_'.$type.''] != 'none'){

            //made dellay dynamic so it will work good with grid element different number of columns
            $css .= '
            .uicore-'.$type.'-grid.animate-4 .uicore-animate:nth-child(4n-2){
                animation-delay: '. (int)$json_settings['animations_'.$type.'_delay_child'] .';
            }
            .uicore-'.$type.'-grid.animate-4 .uicore-animate:nth-child(4n-1){
                animation-delay: '. ((int)$json_settings['animations_'.$type.'_delay_child'] * 2) .'ms;
            }
            .uicore-'.$type.'-grid.animate-4 .uicore-animate:nth-child(4n){
                animation-delay: '. ((int)$json_settings['animations_'.$type.'_delay_child'] * 3) .'ms;
            }
            .uicore-'.$type.'-grid.animate-3 .uicore-animate:nth-child(3n-1){
                animation-delay: '. ((int)$json_settings['animations_'.$type.'_delay_child']) .'ms;
            }
            .uicore-'.$type.'-grid.animate-3 .uicore-animate:nth-child(3n){
                animation-delay: '. ((int)$json_settings['animations_'.$type.'_delay_child'] * 2) .'ms;
            }
            .uicore-'.$type.'-grid.animate-2 .uicore-animate:nth-child(2n){
                animation-delay: '. ((int)$json_settings['animations_'.$type.'_delay_child']) .'ms;
            }
			';

			if($type === 'blog') {
				$css .= '
				.ui-simple-creative .uicore-animate,
				';
			}
			$css .= '
            .uicore-'.$type.'-animation .uicore-animate{';

            if($json_settings['animations_'.$type.''] === 'fade'){
                $css .= '
                    opacity: 0;
                    animation-fill-mode: forwards;
                    animation-duration: 1s;
                    animation-name: uicoreFadeIn;
                    animation-play-state: paused;
                    animation-timing-function: '.$opacityEase.';
                ';
            }
            if($json_settings['animations_'.$type.''] === 'fade down'){
                $css = '
                    opacity: 0;
                    animation-fill-mode: forwards;
                    animation-duration: 1.8s;
                    animation-name: uicoreFadeInDown, uicoreFadeIn;
                    animation-play-state: paused;
                    animation-timing-function: '.$translateEase.','. $opacityEase.';
                ';
            }
            if($json_settings['animations_'.$type.''] === 'fade up'){
                $css .= '
                    opacity: 0;
                    animation-fill-mode: forwards;
                    animation-duration: 1.8s;
                    animation-name: uicoreFadeInUp, uicoreFadeIn;
                    animation-play-state: paused;
                    animation-timing-function: '.$translateEase.','. $opacityEase.';
                ';
            }
            if( $json_settings['animations_'.$type.'_duration'] === 'fast'){
                $css .= '
                    animation-duration: 1.3s;
                ';
            }
            if( $json_settings['animations_footer_duration'] === 'slow'){
                $css .= '
                    animation-duration: 2.7s;
                ';
            }
            $css .= '}';
        }

        return $css;
    }

    /**
     * Helper function to get skin stylesheet
     *
     * @param mixed $skin
     * @return void
     * @author Andrei Voica <andrei@uicore.co>
     * @since 3.0.0
     */
    function add_skin($skin)
    {
        $settings = $this->settings['skin_'.$skin];
        $required = false;

        if(isset($settings['conditions'])){
            foreach($settings['conditions'] as $condition=>$value){
                if($this->settings[$condition] === $value){
                    $required = true;
                }
            }
        }else{
            $required = true;
        }
        if($settings['value'] != 'clean' && $required){
            $folder = ($settings['value'] === 'default') ? str_replace(' ', '_', strtolower(UICORE_THEME_NAME)) : $settings['value'];
            $skin = isset($settings['dynamic']) ? $skin . '_' . str_replace(' ', '_', $this->settings[$settings['dynamic']]) : $skin;
            $type = isset($settings['type']) ? $settings['type'] : 'global';
            $this->files[$type][] = get_template_directory() . '/assets/skin/' . $folder .'/'. $skin .'.css';
        }

    }



}
