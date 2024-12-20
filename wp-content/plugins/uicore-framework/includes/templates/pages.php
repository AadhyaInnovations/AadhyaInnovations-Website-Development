<?php
namespace UiCore;
defined('ABSPATH') || exit();

/**
 * Here we generate the page template
 */
class Pages
{
    function __construct()
    {
        if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
            $this->page();
        }
    }

    function page()
    {
        global $post;
        $is_elementor = false;
        if(\class_exists('\Elementor\Plugin')){
            $is_elementor = \Elementor\Plugin::$instance->documents->get( $post->ID )->is_built_with_elementor();
        }
        while (have_posts()):
            the_post();
            if ( $is_elementor || Helper::get_option('gen_maintenance') === 'true' ) {
                $this->elementor_content();
            } else {
                $this->content();
            }
        endwhile; // End of the loop.
    }

    function elementor_content()
    {
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <main class="entry-content">
                <?php the_content(); ?>
            </main>
        </article>
        <?php
    }

    function content()
    {
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
            <main id="main" class="site-main elementor-section elementor-section-boxed uicore">
                <div class="uicore elementor-container uicore-content-wrapper uicore-no-editor">
                    <div class="uicore-content">
                    <?php the_content(); ?>
                    </div>
                </div>
            </main>
            </div>
        </article>
        <?php
    }
}
