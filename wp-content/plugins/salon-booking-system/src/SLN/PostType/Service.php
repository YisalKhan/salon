<?php

class SLN_PostType_Service extends SLN_PostType_Abstract
{

    public function init()
    {
        parent::init();

        if (is_admin()) {
            add_action('pre_get_posts', array($this, 'admin_posts_sort'));
            add_action('wp_insert_post', array($this, 'wp_insert_post'));
            add_action('manage_'.$this->getPostType().'_posts_custom_column', array($this, 'manage_column'), 10, 2);
            add_filter('manage_'.$this->getPostType().'_posts_columns', array($this, 'manage_columns'));
            add_filter('manage_edit-'.$this->getPostType().'_sortable_columns', array($this, 'custom_columns_sort'));
            add_action('admin_head-post-new.php', array($this, 'posttype_admin_css'));
            add_action('admin_head-post.php', array($this, 'posttype_admin_css'));
            add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
            add_action('wp_ajax_sln_service', array($this, 'ajax'));
	    add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_custom_box' ), 10, 2 );
	    add_action( 'save_post', array( $this, 'save_post' ), 50);
        }
    }

    public function custom_columns_sort( $columns ) {
        $custom = array(
            'title' => 'title',
        );
        return $custom;
    }

    /**
     * @param WP_Query $query
     */
    function admin_posts_sort($query)
    {
        global $pagenow, $post_type;

        if (is_admin() && 'edit.php' == $pagenow && $post_type == $this->getPostType() && $query->get('orderby') !== 'title') {
            /** @var SLN_Repository_ServiceRepository $repo */
            $repo = $this->getPlugin()->getRepository($this->getPostType());
            foreach ($repo->getStandardCriteria() as $k => $v) {
                $query->set($k, $v);
            }

            $this->setPostsOrderByFilter();
        }
    }

	public function setPostsOrderByFilter() {
		add_filter('posts_orderby', array($this, 'postsOrderby'), 10, 2);
	}

	/**
	 * @param string $orderby
	 * @param WP_Query $query
	 *
	 * @return string
	 */
	public function postsOrderby($orderby, $query) {
        global $wpdb;
		remove_filter('posts_orderby', array($this, 'postsOrderby'), 10);

		return str_replace("{$wpdb->postmeta}.meta_value", "CAST({$wpdb->postmeta}.meta_value AS DECIMAL)", $orderby);
	}



    public function load_scripts($hook)
    {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');

	if ( 'edit.php' === $hook &&
		isset( $_GET['post_type'] ) &&
		$this->getPostType() === $_GET['post_type'] ) {

	    wp_enqueue_script('salon-admin-service-edit-js', SLN_PLUGIN_URL.'/js/admin/adminServiceEdit.js', array('jquery'), SLN_Action_InitScripts::ASSETS_VERSION, true);
	}
    }

    public function wp_insert_post($post_id, $wp_error = false)
    {
        global $post_type;

        if ($post_type == $this->getPostType()) {
            if (!get_post_meta($post_id, '_sln_service_order', true)) {
                $count_pages = wp_count_posts($this->getPostType());
                $pos = $count_pages->publish + 1;
                add_post_meta($post_id, '_sln_service_order', $pos, true);
            }
        }
    }

    public function ajax()
    {
        if (isset($_POST['method'])) {
            $method = 'ajax_'.sanitize_text_field(wp_unslash($_POST['method']));
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
        die();
    }

    public function ajax_save_position()
    {
        parse_str(sanitize_text_field(wp_unslash($_POST['data'])), $params);

        if (!isset($params['positions'])) {
            return;
        }

        foreach (explode(',', $params['positions']) as $item) {
            list($post_id, $pos) = explode('_', $item);
            update_post_meta($post_id, '_sln_service_order', $pos);
        }
    }

    public function ajax_save_cat_position()
    {
        global $wpdb;
        parse_str(sanitize_text_field(wp_unslash($_POST['data'])), $params);

        if (!isset($params['positions'])) {
            return;
        }

        update_option(SLN_Plugin::CATEGORY_ORDER, $params['positions']);
    }

    public function manage_columns($columns)
    {
        $new_columns = array(
            'cb' => $columns['cb'],
            'ID' => __('Service ID', 'salon-booking-system'),
            'title' => $columns['title'],
            'service_duration' => __('Duration', 'salon-booking-system'),
            'service_price' => __('Price', 'salon-booking-system'),
            'secondary' => __('Secondary', 'salon-booking-system'),
            'taxonomy-sln_service_category' => $columns['taxonomy-sln_service_category'],
            'sln_days_off' => __('Availability', 'salon-booking-system'),
        );

//        return array_merge(
//            $columns, array(
//            'service_duration' => __('Duration', 'salon-booking-system'),
//            'service_price' => __('Price', 'salon-booking-system')
//            )
//        );
        return $new_columns;
    }

    public function manage_column($column, $post_id)
    {
        $obj = $this->getPlugin()->createService($post_id);
        switch ($column) {
            case 'ID' :
                echo edit_post_link($post_id, '<p>', '</p>', $post_id);
		echo '<div class="sln-service-unit" data-value="'.$obj->getUnitPerHour().'"></div>';
                break;
            case 'service_duration':
                $time = SLN_Func::filter($obj->getDuration(), 'time');
                echo $time ? $time : '-';
                break;
            case 'service_price' :
                echo $this->getPlugin()->format()->money($obj->getPrice());
                break;
            case 'secondary' :
                echo ($obj->isSecondary() ? __('YES', 'salon-booking-system') : '');
                break;
            case 'sln_days_off' :
                echo implode('<br/>',$obj->getAvailabilityItems()->toArray());
                break;
        }
    }

    public function enter_title_here($title, $post)
    {

        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter service name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Service updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Service updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Service restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Service published.', 'salon-booking-system')
            ),
            7 => __('Service saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Service submitted.', 'salon-booking-system')
            ),
            9 => sprintf(
                __(
                    'Service scheduled for: <strong>%1$s</strong>. ',
                    'salon-booking-system'
                ),
                SLN_TimeFunc::translateDate(__('M j, Y @ G:i', 'salon-booking-system'), SLN_TimeFunc::getPostTimestamp($post))
            ),
            10 => sprintf(
                __('Service draft updated.', 'salon-booking-system')
            ),
        );


        return $messages;
    }

    protected function getPostTypeArgs()
    {
        return array(
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'show_in_menu' => 'salon',
            'rewrite' => false,
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
                'revisions',
            ),
            'labels' => array(
                'name' => __('Services', 'salon-booking-system'),
                'singular_name' => __('Service', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Service', 'salon-booking-system'),
                'all_items' => __('Services', 'salon-booking-system'),
                'add_new' => __('Add Service', 'salon-booking-system'),
                'add_new_item' => __('Add New Service', 'salon-booking-system'),
                'edit_item' => __('Edit Service', 'salon-booking-system'),
                'new_item' => __('New Service', 'salon-booking-system'),
                'view_item' => __('View Service', 'salon-booking-system'),
                'search_items' => __('Search Services', 'salon-booking-system'),
                'not_found' => __('No services found', 'salon-booking-system'),
                'not_found_in_trash' => __('No services found in trash', 'salon-booking-system'),
                'archive_title' => __('Services Archive', 'salon-booking-system'),
            ),
            'capability_type' => array($this->getPostType(), $this->getPostType().'s'),
            'map_meta_cap' => true
        );
    }

    public function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == $this->getPostType()) {
            $this->getPlugin()->loadView('metabox/_service_head');
        }
    }

    public function quick_edit_custom_box( $column_name, $post_type ) {

	if ($this->getPostType() !== $post_type || $column_name !== 'service_price') {
	    return;
	}

	?>

	    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'sln_service_edit_nonce' ); ?>

	    <div class="row">
		<div class="col-sm-12">
		    <legend class="inline-edit-legend"><?php echo __( 'Quick Edit' ); ?></legend>
		</div>
	    </div>
	    <div class="row">
		<div class="col-sm-12 sln-inline-edit-service-fields">
		    <div class="row">
			<div class="col-sm-3">
			    <label>
				<span class="title"><?php _e( 'Title' ); ?></span>
				<span class="input-text-wrap"><input type="text" name="sln_post_title" class="ptitle" value="" /></span>
			    </label>
			</div>
			<div class="col-sm-3">
			    <label>
				<span class="title"><?php _e( 'Slug' ); ?></span>
				<span class="input-text-wrap"><input type="text" name="sln_post_name" value="" /></span>
			    </label>
			</div>
			<div class="col-sm-3">
			    <label>
				<span class="title"><?php echo __( 'Price' ) . ' (' . $this->getPlugin()->getSettings()->getCurrencySymbol() . ')' ?></span>
				<span class="input-text-wrap"><input type="text" name="sln_service_price" value="" /></span>
			    </label>
			</div>
			<div class="col-sm-3">
			    <label>
				<span class="title title-inline"><?php _e( 'Units per hour' ); ?></span>
				<?php SLN_Form::fieldNumeric('sln_service_unit'); ?>
			    </label>
			</div>
		    </div>
		    <div class="row">
			<div class="col-sm-3">
			    <label>
				<span class="title title-inline"><?php _e( 'Duration' ); ?></span>
				<?php SLN_Form::fieldTime('sln_service_duration'); ?>
			    </label>
			</div>
			<div class="col-sm-3">
			    <label>
				<span class="title title-inline"><?php _e( 'Secondary' ); ?></span>
				<?php SLN_Form::fieldCheckbox('sln_service_secondary') ?>
			    </label>
			</div>
			<div class="col-sm-3">
			    <label>
				<span class="title"><?php _e( 'Service Categories' ); ?></span>
				<span class="input-text-wrap">
				    <textarea data-wp-taxonomy="sln_service_category" cols="22" rows="1" name="sln_service_category" class="sln_service_category"></textarea>
				</span>
			    </label>
			</div>
		    </div>
		</div>
	    </div>

	<?php
    }

    public function save_post( $post_id ) {

	if (!isset($_POST['action']) || $_POST['action'] !== 'inline-save') {
	    return;
	}

	$post_type = get_post_type( $post_id );

	if ( $this->getPostType() !== $post_type ) {
	    return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
	    return;
	}

	if ( ! wp_verify_nonce( $_POST["sln_service_edit_nonce"], plugin_basename( __FILE__ ) ) ) {
	    return;
	}

	remove_action( 'save_post', array( $this, 'save_post' ), 50 );

	update_post_meta($post_id, '_' . $this->getPostType() . '_unit', isset($_POST['sln_service_unit']) ? $_POST['sln_service_unit'] : '');
	update_post_meta($post_id, '_' . $this->getPostType() . '_price', isset($_POST['sln_service_price']) ? $_POST['sln_service_price'] : '');
	update_post_meta($post_id, '_' . $this->getPostType() . '_duration', isset($_POST['sln_service_duration']) ? $_POST['sln_service_duration'] : '');
	update_post_meta($post_id, '_' . $this->getPostType() . '_secondary', isset($_POST['sln_service_secondary']) && $_POST['sln_service_secondary'] ? 1 : 0);

	$selected_service_categories = array();

	$service_categories = get_terms( array(
	    'taxonomy'	    => SLN_Plugin::TAXONOMY_SERVICE_CATEGORY,
	    'hide_empty'    => false,
	) );

	$tmp_service_categories = array_map('trim', explode(',', $_POST['sln_service_category']));

	foreach ($service_categories as $service_category) {
	    if (in_array($service_category->name, $tmp_service_categories)) {
		$selected_service_categories[] = $service_category->term_id;
	    }
	}

	wp_update_post(array(
	   'ID'		 => $post_id,
	    'post_title' => isset($_POST['sln_post_title']) ? $_POST['sln_post_title'] : '',
	    'post_name'  => isset($_POST['sln_post_name']) ? $_POST['sln_post_name'] : '',
	    'tax_input'	 => array(
		  SLN_Plugin::TAXONOMY_SERVICE_CATEGORY => $selected_service_categories,
	    ),
	));
    }

}
