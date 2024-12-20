<?php
namespace UiCore;
defined('ABSPATH') || exit();

/**
 * Here we generate the header
 */
class Pagination
{
    /**
     * sajdnek
     *
     * @param array $args
     * @param string $class
     * @author Andrei Voica <andrei@uicore.co
     * @since 1.0.0
     */
    function __construct($args = [], $class = 'Pagination')
    {
        //global $query;
        $args = wp_parse_args($args, [
            'mid_size' => 2,
            'prev_next' => true,
            'prev_text' => null,
            'next_text' => null,
            'screen_reader_text' => _x('Posts navigation', 'Frontend - Pagination', 'uicore-framework'),
            'type' => 'array',
            'current' => max(1, get_query_var('paged')),
        ]);
        if (class_exists('WooCommerce')) {
            $total = isset($total) ? $total : wc_get_loop_prop('total_pages');
            if ($total <= 1 && is_woocommerce()) {
                return;
            } elseif (is_woocommerce()) {
                $args = apply_filters('woocommerce_pagination_args', [
                    // WPCS: XSS ok.
                    // 'base'         => $base,
                    // 'format'       => $format,
                    //'add_args'     => false,
                    'current' => max(1, wc_get_loop_prop('current_page')),
                    'total' => $total,
                    'prev_text' => '',
                    'next_text' => '',
                    'type' => 'array',
                    'screen_reader_text' => _x('Posts navigation', 'Frontend - Pagination', 'uicore-framework'),
                    //'end_size'     => 3,
                    //'mid_size'     => 3,
                ]);
            }
        }

        $links = paginate_links($args);
        if (is_array($links) || is_object($links)) { ?>
		<nav aria-label="<?php echo $args['screen_reader_text']; ?>" class="uicore-pagination">
			<ul>
				<?php foreach ($links as $key => $link) { ?>
					<li class="uicore-page-item <?php echo strpos($link, 'current') ? 'uicore-active' : ''; ?>">
						<?php echo str_replace('page-numbers', 'uicore-page-link', $link); ?>
					</li>
                    <?php } ?>
			</ul>
		</nav>
		<?php }
    }
}
