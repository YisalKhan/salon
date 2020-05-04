<?php

class SLN_PostType_Booking extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();
//        add_filter('wp_insert_post_data', array($this, 'insert_post_data'), '99', 2);

        if (is_admin()) {
            add_action('manage_'.$this->getPostType().'_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_'.$this->getPostType().'_posts_columns', array($this, 'manage_columns'));
            add_action('admin_footer-post.php', array($this, 'bulkAdminFooterEdit'));
            add_action('admin_footer-post-new.php', array($this, 'bulkAdminFooterNew'));
            add_filter('display_post_states', array($this, 'bulkPostStates'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
            add_action('restrict_manage_posts', array($this, 'restrict_manage_posts'), 10, 2);
            add_filter('parse_query', array($this, 'parse_query'));
            add_filter('pre_get_posts', array($this, 'pre_get_posts'));
            add_filter('post_row_actions', array($this, 'post_row_actions'), 10, 2);
            add_filter( 'months_dropdown_results', array($this,'months_dropdown_results'),10,2 );
            add_filter( 'posts_join', array($this, 'posts_join'),10,2 );
            add_filter( 'posts_where', array($this, 'posts_where') );
            add_filter( 'posts_distinct', array($this,'posts_distinct') );
            add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts')   );
            add_filter( 'posts_search', [$this,'posts_search'], 10, 2 );
        }
        $this->registerPostStatus();
    }

    public function admin_enqueue_scripts(){
        $screen = get_current_screen();
        if( $screen->id === 'edit-sln_booking' ){
            wp_enqueue_style('salon-admin-css', SLN_PLUGIN_URL.'/css/admin.css', array(), SLN_VERSION, 'all');

	    //Rtl support
	    wp_style_add_data( 'salon-admin-css', 'rtl', 'replace' );

            SLN_Action_InitScripts::preloadScripts();
        }
    }


    function posts_search( $search, $wp_query ) {
        // Bail if we are not in the admin area
        if ( ! is_admin() ) {
            return $search;
        }

        // Bail if this is not the search query.
        if ( ! $wp_query->is_main_query() && ! $wp_query->is_search() ) {
            return $search;
        }   

        // Get the value that is being searched.
        $search_string = get_query_var( 's' );

        // Bail if the search string is not an integer.
        if ( ! filter_var( $search_string, FILTER_VALIDATE_INT ) ) {
            return $search;
        }

        // This appears to be a search using a post ID.
        // Return modified posts_search clause.
        return "AND wp_posts.ID = '" . intval( $search_string )  . "'";
    }

    public function pre_get_posts($query){
        global $pagenow;

        if (isset($_GET['post_type']) && $_GET['post_type'] === $this->getPostType() && is_admin() && $pagenow=='edit.php' && $query->get('post_type') === $this->getPostType()) {
            $query->set('m', null);

        }
        return $query;
    }
    public function posts_join ( $join, $wp_query ) {
        global $pagenow, $wpdb;
        // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
        
        if ( is_admin() && 'edit.php' === $pagenow  && ! empty($_GET['post_type'] )  && $this->getPostType() === $_GET['post_type'] && ! empty( $_GET['s'] ) && ( isset($wp_query->query_vars['post_type'] )&& $wp_query->query_vars['post_type'] === $this->getPostType() ) && ( empty($wp_query->get('meta_query')) )) {
            $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
        }
        return $join;
    }

    public function posts_where( $where ) {
        global $pagenow, $wpdb;

        // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
        if ( is_admin() && 'edit.php' === $pagenow && ! empty($_GET['post_type'] ) && $this->getPostType() === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
            $where = preg_replace(
                "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
        }
        return $where;
    }
    public function posts_distinct( $where ){
        global $pagenow, $wpdb;

        if ( is_admin() && $pagenow=='edit.php' && ! empty($_GET['post_type'] ) && $_GET['post_type']==$this->getPostType() && !empty($_GET['s'])) {
        return "DISTINCT";

        }
        return $where;
    }

    public  function months_dropdown_results($months, $post_type){

        if($post_type !== $this->getPostType() ) return $months;
        global $wpdb;
        $extra_checks = "AND post_status != 'auto-draft'";
            if ( ! isset( $_GET['post_status'] ) || 'trash' !== $_GET['post_status'] ) {
                $extra_checks .= " AND post_status != 'trash'";
            } elseif ( isset( $_GET['post_status'] ) ) {
                $extra_checks = $wpdb->prepare( ' AND post_status = %s', sanitize_text_field(wp_unslash($_GET['post_status'])) );
            }
                $months = $wpdb->get_results(
                $wpdb->prepare(
                    "
                SELECT DISTINCT YEAR( meta_value ) AS year, MONTH( meta_value) AS month

                FROM $wpdb->postmeta as pt
                LEFT JOIN $wpdb->posts as p
                ON pt.post_id = p.ID
                WHERE pt.meta_key = '_sln_booking_date' AND
                post_type = %s
                $extra_checks
                ORDER BY meta_value DESC
            ", $post_type
                )
            );

            return $months;
    }


//    public function insert_post_data($data, $postarr)
//    {
//        if ($data['post_type'] == $this->getPostType()) {
//            $data['post_title'] = 'Booking #' . $postarr['ID'];
//        }
//
//        return $data;
//    }

    public function post_row_actions($actions, $post) {
        if ($post->post_type === SLN_Plugin::POST_TYPE_BOOKING) {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }

    public function manage_columns($columns)
    {
        $ret = array(
            'cb' => $columns['cb'],
            'ID' => __('Booking ID'),
            'booking_date' => __('Booking Date', 'salon-booking-system'),
            'booking_status' => __('Status', 'salon-booking-system'),
            'myauthor' => __('User name', 'salon-booking-system'),
            'booking_attendant' => __('Attendant', 'salon-booking-system'),
//            'date' => __('Submitted', 'salon-booking-system'),
            'booking_price' => __('Booking Price', 'salon-booking-system'),
            'booking_services' => __('Booking Services', 'salon-booking-system'),
            'booking_review' => __('Booking Review', 'salon-booking-system'),
        );

        return $ret;
    }

    public function manage_column($column, $post_id)
    {
        $obj = $this->getPlugin()->createBooking($post_id);

	$customer = $obj->getCustomer();

        switch ($column) {
            case 'ID' :
                echo edit_post_link($post_id, '<p>', '</p>', $post_id);
                break;
            case 'myauthor':
		echo '<a href="'.esc_url(add_query_arg(array('page' => SLN_Admin_Customers::PAGE, 'id' => $customer ? $customer->getId() : null), admin_url('admin.php'))).'">'.$obj->getDisplayName().'</a>';
                break;
            case 'booking_status' :
                echo SLN_Enum_BookingStatus::getLabel(get_post_status($post_id));
                break;
            case 'booking_date':
                echo $this->getPlugin()->format()->datetime(
                    new SLN_DateTime(
                        get_post_meta($post_id, '_sln_booking_date', true)
                        .' '.get_post_meta($post_id, '_sln_booking_time', true)
                    )
                );
                break;
            case 'booking_price' :
                echo $this->getPlugin()->format()->money(get_post_meta($post_id, '_sln_booking_amount', true));
                if (get_post_status($post_id) == SLN_Enum_BookingStatus::PAID && $deposit = get_post_meta(
                        $post_id,
                        '_sln_booking_deposit',
                        true
                    )
                ) {
                    echo '(deposit '.$this->getPlugin()->format()->money($deposit).')';
                }
                break;
            case 'booking_services' :
                $name_services = array();
                foreach ($obj->getServices() as $helper) {
                    $name_services[] = $helper->getName();
                }
                echo implode(', ', $name_services);
                break;
            case 'booking_attendant' :
                echo $obj->getAttendantsString();
                break;
            case 'booking_review' :
                $comments = get_comments("post_id=$post_id&type=sln_review");
                $comment = isset($comments[0]) ? $comments[0] : null;

                echo '<input type="hidden" name="sln-rating" value="'.$obj->getRating().'">
                        <div class="rating" style="display: none;"></div>';

                if (!empty($comment)) {
                    echo '<a href="'.esc_url(add_query_arg(array('p' => $post_id), admin_url('edit-comments.php'))).'#salon-review"
                            class="overflow-dots">'.$comment->comment_content.'</a>';
                }

                break;
        }
    }

    public function enter_title_here($title, $post)
    {
        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter booking name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Booking updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Booking updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Booking restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Booking published.', 'salon-booking-system')
            ),
            7 => __('Booking saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Booking submitted.', 'salon-booking-system')
            ),
            9 => sprintf(
                __(
                    'Booking scheduled for: <strong>%1$s</strong>.',
                    'salon-booking-system'
                ),
                SLN_TimeFunc::translateDate(__('M j, Y @ G:i', 'salon-booking-system'), SLN_TimeFunc::getPostTimestamp($post))
            ),
            10 => sprintf(
                __('Booking draft updated.', 'salon-booking-system')
            ),
        );


        return $messages;
    }

    protected function getPostTypeArgs()
    {
        return array(
            'description' => __('This is where bookings are stored.', 'salon-booking-system'),
            'public' => true,
            'show_ui' => true,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_menu' => 'salon',
            'hierarchical' => false,
            'show_in_nav_menus' => true,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array('title', 'comments', 'custom-fields'),
            'has_archive' => false,
            'rewrite' => false,
            'supports' => array(
                'revisions',
            ),
            'labels' => array(
                'name' => __('Bookings', 'salon-booking-system'),
                'singular_name' => __('Booking', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Booking', 'salon-booking-system'),
                'all_items' => __('Bookings', 'salon-booking-system'),
                'add_new' => __('Add Booking', 'salon-booking-system'),
                'add_new_item' => __('Add New Booking', 'salon-booking-system'),
                'edit_item' => __('Edit Booking', 'salon-booking-system'),
                'new_item' => __('New Booking', 'salon-booking-system'),
                'view_item' => __('View Booking', 'salon-booking-system'),
                'search_items' => __('Search Bookings', 'salon-booking-system'),
                'not_found' => __('No bookings found', 'salon-booking-system'),
                'not_found_in_trash' => __('No bookings found in trash', 'salon-booking-system'),
                'archive_title' => __('Booking Archive', 'salon-booking-system'),
            ),
            'capability_type' => array($this->getPostType(), $this->getPostType().'s'),
            'map_meta_cap' => true,
        );
    }

    private function registerPostStatus()
    {
        foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {
            register_post_status(
                $k,
                array(
                    'label' => $v,
                    'public' => true,
                    'exclude_from_search' => false,
                    'show_in_admin_all_list' => true,
                    'show_in_admin_status_list' => true,
                    'label_count' => _n_noop(
                        $v.' <span class="count">(%s)</span>',
                        $v.' <span class="count">(%s)</span>'
                    ),
                )
            );
        }
        add_action('transition_post_status', array($this, 'transitionPostStatus'), 10, 3);
    }

    public function transitionPostStatus($new_status, $old_status, $post)
    {
        if (
            $post->post_type == SLN_Plugin::POST_TYPE_BOOKING
            && $old_status != $new_status
        ) {
            $p = $this->getPlugin();
            $booking = $p->createBooking($post);
            $p->messages()->sendByStatus($booking, $new_status);
            //$ret = $GLOBALS['sln_googlescope']->create_event_from_booking($booking);
        }
    }


    public function bulkAdminFooterNew()
    {
        $this->bulkAdminFooter(true);
    }

    public function bulkAdminFooterEdit()
    {
        $this->bulkAdminFooter(false);
    }

    public function bulkAdminFooter($isNew = false)
    {
        global $post;
        if ($post->post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#save-post').attr('value', '<?php echo __(
                        $isNew ? "Add booking" : 'Update booking',
                        'salon-booking-system'
                    ) ?>').addClass('sln-btn sln-btn--main');
                    $('#major-publishing-actions').css('display', 'none');
                    $('#submitdiv h3 span').text('<?php echo __('Booking', 'salon-booking-system') ?>');
                    <?php
                    foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {
                    $complete = '';
                    $label = '';
                    if ($post->post_status == $k) {
                        $complete = ' selected=\"selected\"';
                        $label = '<span id=\"post-status-display\">'.$v.'</span>';
                    }
                    ?>
                    $("select#post_status").append("<option value=\"<?php echo $k ?>\" <?php echo $complete ?>><?php echo $v ?></option>");
                    $(".misc-pub-section label").append("<?php echo $label ?>");
                    <?php
                    }
                    ?>
                });
            </script>
            <?php
        }
    }

    public function bulkPostStates()
    {
        global $post;
        $arg = get_query_var('post_status');
        if ($post->post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            foreach (SLN_Enum_BookingStatus::toArray() as $k => $v) {

                if ($arg != $k) {
                    if ($post->post_status == $k) {
                        return array($v);
                    }
                }
            }
        }

        return array();
    }

    public function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == SLN_Plugin::POST_TYPE_BOOKING) {
            echo $this->getPlugin()->loadView('metabox/_booking_head');
        }
    }

    /**
     * @param WP_Query $query
     */
    public function parse_query($query) {
        global $pagenow;

        if (isset($_GET['post_type']) && $_GET['post_type'] === $this->getPostType() && is_admin() && $pagenow=='edit.php' && $query->get('post_type') === $this->getPostType()) {
            $meta_queries = array();
            if (isset($_GET['m']) && !empty($_GET['m'])) {
                $m =  sanitize_text_field(wp_unslash($_GET['m']));
                $y =  substr ( $m , 0 ,4 );
                $m =  substr ( $m , 4, 2 );
                $meta_queries[] = array(
                    'key'     => '_sln_booking_date',
                    'value'   => array($y."-".$m."-01",$y."-".$m."-31"),
                    'compare' => 'BETWEEN',
                );
            }
            if (isset($_GET['attendant']) && !empty($_GET['attendant'])) {
                $attendant = sanitize_text_field(wp_unslash($_GET['attendant']));
                $meta_queries[] = array(
                    'key'     => '_sln_booking_services',
                    'value'   => "\"attendant\";i:{$_GET['attendant']};",
                    'compare' => 'LIKE',
                );
            }

            if (isset($_GET['username']) && !empty($_GET['username'])) {
                $username_parts = explode('|', sanitize_text_field(wp_unslash($_GET['username'])));
                if (!empty($username_parts[0])) {
                    $meta_queries[] = array(
                        'key'   => '_sln_booking_firstname',
                        'value' => $username_parts[0],
                    );
                }
                if (!empty($username_parts[1])) {
                    $meta_queries[] = array(
                        'key'   => '_sln_booking_lastname',
                        'value' => $username_parts[1],
                    );
                }
            }

            if (!empty($meta_queries)) {
                $meta_queries['relation'] = 'AND';

                $meta_query = $query->get('meta_query');

                $meta_query = array_merge(!empty($meta_query) ? $meta_query : array(), $meta_queries);
                $query->set('meta_query', $meta_query);
            }
        }
    }

    public function restrict_manage_posts($post_type, $which = null) {
        global $wpdb;
        if ($post_type === $this->getPostType() && $which === 'top') {
            $statuses = SLN_Enum_BookingStatus::toArray();

            $rows  = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key='_sln_booking_firstname' OR meta_key='_sln_booking_lastname'");

            $users = array();
            foreach($rows as $row) {
                $users[$row->post_id][$row->meta_key] = $row->meta_value;
            }

            $users_name = array();
            foreach($users as $user) {
                if (!isset($user['_sln_booking_firstname'])) {
                    $user['_sln_booking_firstname'] = '';
                }
                if (!isset($user['_sln_booking_lastname'])) {
                    $user['_sln_booking_lastname'] = '';
                }
                $users_name[$user['_sln_booking_firstname'].'|'.$user['_sln_booking_lastname']] = $user['_sln_booking_firstname'].' '.$user['_sln_booking_lastname'];
            }

            $repo       = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_ATTENDANT);
            $attendants = $repo->getAll();
            ?>
            <?php $current = isset($_GET['post_status']) ? sanitize_text_field(wp_unslash($_GET['post_status'])) : ''; ?>
            <select name="post_status" id="filter-by-post_status">
                <option value=""><?php _e('All Statuses', 'salon-booking-system') ?></option>
                <?php foreach($statuses as $k => $v): ?>
                    <option value="<?php echo $k; ?>" <?php echo ($current === $k ? 'selected' : ''); ?>><?php echo $v; ?></option>
                <?php endforeach ?>
            </select>

            <?php $current = isset($_GET['username']) ? sanitize_text_field(wp_unslash($_GET['username'])) : ''; ?>
            <select name="username" id="filter-by-username">
                <option value=""><?php _e('All users name', 'salon-booking-system') ?></option>
                <?php foreach($users_name as $k => $v): ?>
                    <option value="<?php echo $k; ?>" <?php echo ($current === $k ? 'selected' : ''); ?>><?php echo $v; ?></option>
                <?php endforeach ?>
            </select>

            <?php $current = isset($_GET['attendant']) ? (int) $_GET['attendant'] : ''; ?>
            <select name="attendant" id="filter-by-attendant">
                <option value=""><?php _e('All attendants', 'salon-booking-system') ?></option>
                <?php foreach($attendants as $v): ?>
                    <option value="<?php echo $v->getId(); ?>" <?php echo ($current === $v->getId() ? 'selected' : ''); ?>><?php echo $v->getTitle(); ?></option>
                <?php endforeach ?>
            </select>
            <?php
        }
    }

}
