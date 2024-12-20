<?php

namespace UiCore;

defined('ABSPATH') || exit();

/**
 * General Functions
 */
class InlineStyle
{
    /**
     * Generate css from Theme Options
     *
     * @param $json_settings
     */
    public function __construct()
    {
        add_action('wp_head', [$this, 'inline_css'], 100);
    }

    /**
     * Enqueue frontend inline css ( theme options & custom css)
     *
     * @static
     */
    public function inline_css()
    {
        // global $post;
        $post = get_queried_object_id();
        \ob_start();
        $this->getStyle($post);
        $css = \ob_get_clean();
        if($css){
            echo "<style> \n";
            echo $css;
            echo "\n </style> ";
        }

    }

    /**
     * getStyle
     *
     * @param  mixed $post
     *
     * @return void
     */
    private function getStyle($post_id)
    {
        /**
         * Add support for adding css on the fly
         */
        $extra_css = \apply_filters('uicore_frontent_css','');
        echo $extra_css;


        if((Helper::get_option('disable_blog') === 'false' ) && Blog\Frontend::is_blog() && !is_singular('post')){
            $post_id = get_option('page_for_posts');
        }elseif((Helper::get_option('disable_portfolio') === 'false' ) && Portfolio\Frontend::is_portfolio() && !is_singular('portfolio') ){
            $post_id = Helper::get_option('portfolio_page')['id'] ?? 0;
        }

        //Page Options Related Style
        if (isset($post_id)) {
            
            $meta = Settings::po_get_page_settings($post_id);
            $this->getHeaderStyle($meta);
            $this->getPageTitleStyle($meta);
            $this->getPageLayoutStyle($meta);
            $this->getPageCustomCss($meta);
        }
    }

    /**
     * getPageTitleStyle
     *
     * @param  mixed $post
     *
     * @return void
     */
    private function getHeaderStyle($meta)
    {
        $color = isset($meta['header_transparent_color_m']) ? $meta['header_transparent_color_m'] : false;
        $hover_color = isset($meta['header_transparent_color_h']) ? $meta['header_transparent_color_h'] : false;

        if ($color && $color['type'] != 'theme default') {
            //set style if color is setted
            if ($color['type'] == 'solid') {
                echo '.uicore.uicore-transparent:not(.uicore-scrolled){
                    --uicore-header--menu-typo-c:' .  $color['solid'] .   '
                }';
            } else {
                echo '.uicore.uicore-transparent:not(.uicore-scrolled){
                    --uicore-header--menu-typo-c:' .   Settings::color_filter( $color['type'] ).   '
                }';
            }
        }
        if ($hover_color && $hover_color['type'] != 'theme default') {
            //set style if color is setted
            if ($hover_color['type'] == 'solid') {
                echo '.uicore.uicore-transparent:not(.uicore-scrolled){
                    --uicore-header--menu-typo-ch:' .  $hover_color['solid'] .   '
                }';
            } else {
                echo '.uicore.uicore-transparent:not(.uicore-scrolled){
                    --uicore-header--menu-typo-ch:' .   Settings::color_filter( $hover_color['type'] ).   '
                }';
            }
        }
    }

    /**
     * getPageTitleStyle
     *
     * @param  mixed $post
     *
     * @return void
     */
    private function getPageTitleStyle($meta)
    {
        $background = $meta['pagetitle_bg'];
        $overlay = $meta['pagetitle_overlay'];
        $title = $meta['pagetitle_color'];

        if ($background && $background['type'] != 'theme default') {
            //set style for solid background
            if ($background['type'] == 'solid') {
                echo 'header.uicore-page-title{
                          background:' .
                    $background['solid'] .
                    '!important
                        }';

                //set style for gradient background
            } elseif ($background['type'] == 'gradient') {
                $angle = $background['gradient']['angle'];
                $color1 = $background['gradient']['color1'];
                $color2 = $background['gradient']['color2'];
                echo 'header.uicore-page-title{
                            background: linear-gradient(' .
                    $angle .
                    'deg, ' .
                    $color1 .
                    ', ' .
                    $color2 .
                    ')!important
                        }';
            } elseif ($background['type'] == 'image' && !$background['image']['url'] == '') {
                $url = $background['image']['url'];
                $position = $background['image']['position']['d'];
                $size = $background['image']['size']['d'];
                $repeat = $background['image']['repeat'];
                $attachment = $background['image']['attachment'];
                $solid = $background['solid'];
                echo 'header.uicore-page-title{
                            background:url(' .
                    $url .
                    ') ' .
                    $position .
                    '/' .
                    $size .
                    ' ' .
                    $repeat .
                    ' ' .
                    $attachment .
                    ' ' .
                    $solid .
                    '!important
                        }';
            } else {
                echo 'header.uicore-page-title{
                                background:' .
                    Settings::color_filter($background['type']) .
                    ';
                    }';
            }
        }

        if ($overlay && $overlay['type'] != 'theme default') {
            //set style for solid background
            if ($overlay['type'] == 'none') {
                echo 'header.uicore-page-title .uicore-overlay{
                            display: none;
                        }';

                //set style for solid background
            } elseif ($overlay['type'] == 'solid') {
                echo 'header.uicore-page-title .uicore-overlay{
                            background:' .
                    $overlay['solid'] .
                    ';
					display: block;
                        }';

                //set style for gradient background
            } elseif ($overlay['type'] == 'gradient') {
                $angle = $overlay['gradient']['angle'];
                $color1 = $overlay['gradient']['color1'];
                $color2 = $overlay['gradient']['color2'];
                echo 'header.uicore-page-title .uicore-overlay{
                            background: linear-gradient(' .
                    $angle .
                    'deg, ' .
                    $color1 .
                    ', ' .
                    $color2 .
                    ');
					display: block;
                        }';
            } else {
                echo 'header.uicore-page-title .uicore-overlay{
                                background:' .
                    Settings::color_filter($overlay['type']) .
                    ';
					display: block;
                    }';
            }
        }
        if ($title && $title['type'] != 'theme default') {
            //set style if color is setted
            if ($title['type'] == 'solid') {
                echo '.uicore-page-title h1.uicore-title, .uicore-page-title a, .uicore-page-title p, .uicore-page-title a:hover{
                    color:' .  $title['solid'] .   '
                }';
            } else {
                echo '.uicore-page-title h1.uicore-title, .uicore-page-title a, .uicore-page-title p, .uicore-page-title a:hover{
                    color:' .  Settings::color_filter( $title['type'] ).   '
                }';
            }
        }
    }

    /**
     * getPageLayoutStyle
     *
     * @param  mixed $meta
     *
     * @return void
     */
    private function getPageLayoutStyle($meta)
    {
        $layout = $meta['layout'];
        $background = $meta['bodybg'];
        $boxbg = $meta['boxbg'];
        if ($layout == 'boxed') {
            echo '
            body.uicore-boxed .uicore-reveal .uicore-post-info,
            .uicore-fade-light .uicore-zoom-wrapper,
            .ui-simple-creative,
            .content-area,
            .uicore-body-content > footer{
                    background-color:' .
                $boxbg['solid'] .
                '
            }';
            //Boxed background
            echo '
        body.uicore-boxed #uicore-page{
            background-color:' .$boxbg['solid'] . ';
            max-width:var(--uicore-boxed-width);
            margin:0 auto;
        }';
        }

        //Body Background
        if ($background && $background['type'] != 'theme default') {
            //set style for solid background
            if ($background['type'] == 'solid') {
                echo '.uicore-reveal .uicore-post-info, .ui-simple-creative, .uicore-fade-light .uicore-zoom-wrapper, .content-area, .uicore-body-content>footer{
                          background:' .
                    $background['solid'] .
                    '
                        }';

                //set style for gradient background
            } elseif ($background['type'] == 'gradient') {
                $angle = $background['gradient']['angle'];
                $color1 = $background['gradient']['color1'];
                $color2 = $background['gradient']['color2'];
                echo '.uicore-reveal .uicore-post-info, .ui-simple-creative, .uicore-fade-light .uicore-zoom-wrapper, .content-area, .uicore-body-content>footer{
                            background: linear-gradient(' .
                    $angle .
                    'deg, ' .
                    $color1 .
                    ', ' .
                    $color2 .
                    ')
                        }';
            } elseif ($background['type'] == 'image' && !$background['image']['url'] == '') {
                $url = $background['image']['url'];
                $position = $background['image']['position']['d'];
                $size = $background['image']['size']['d'];
                $repeat = $background['image']['repeat'];
                $attachment = $background['image']['attachment'];
                $solid = $background['solid'];
                echo '.uicore-reveal .uicore-post-info, .ui-simple-creative, .uicore-fade-light .uicore-zoom-wrapper, .content-area, .uicore-body-content>footer{
                            background:url(' .
                    $url .
                    ') ' .
                    $position .
                    '/' .
                    $size .
                    ' ' .
                    $repeat .
                    ' ' .
                    $attachment .
                    ' ' .
                    $solid .
                    '
                        }';
            } else {
                echo '.uicore-reveal .uicore-post-info, .ui-simple-creative, .uicore-fade-light .uicore-zoom-wrapper, .content-area, .uicore-body-content>footer{
                        background:' .
                    Settings::color_filter($background['type']) .
                    ';
                    }';
            }
        }
    }

    /**
     * getPageCustomCss
     *
     * @param  mixed $meta
     *
     * @return void
     */
    private function getPageCustomCss($meta)
    {
        $css = $meta['customcss'];
        echo $css;
    }
}
