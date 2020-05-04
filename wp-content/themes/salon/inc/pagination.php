<?php
/**
 * Pagination layout.
 *
 * @package cornerstone
 */

if ( ! function_exists ( 'vslmd_pagination' ) ) {

    function vslmd_pagination( $args = array(), $class = 'pagination' ) {

        if ($GLOBALS['wp_query']->max_num_pages <= 1) return;

        $args = wp_parse_args( $args, array(
            'mid_size'           => 2,
            'prev_next'          => true,
            'prev_text'          => __('&laquo;', 'vslmd'),
            'next_text'          => __('&raquo;', 'vslmd'),
            'screen_reader_text' => __('Posts navigation', 'vslmd'),
            'type'               => 'array',
            'current'            => max( 1, get_query_var('paged') ),
        ) );

        $links = paginate_links($args);

        ?>

        <nav aria-label="<?php echo $args['screen_reader_text']; ?>">

            <ul class="pagination justify-content-center">

                <?php

                    foreach ( $links as $key => $link ) { ?>

                        <li class="page-item <?php echo strpos( $link, 'current' ) ? 'active' : '' ?>">

                            <?php echo str_replace( 'page-numbers', 'page-link', $link ); ?>

                        </li>

                <?php } ?>

            </ul>

        </nav>

        <?php
    }
}

?>