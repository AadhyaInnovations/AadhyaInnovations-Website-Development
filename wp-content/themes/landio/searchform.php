<?php
/**
 * The template for displaying search forms in landio
 *
 * @package landio
 */
?>
    <form method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
        <label>
            <span class="screen-reader-text"><?php esc_attr_e( 'Search for:', 'landio' ); ?></span>
            <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search &hellip;', 'landio' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" x-webkit-speech>
        </label>
        <input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'landio' ); ?>" />
    </form>
