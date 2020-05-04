<?php

class SLN_PostType_Attendant extends SLN_PostType_Abstract
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
            add_action('wp_ajax_sln_attendant', array($this, 'ajax'));
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
            /** @var SLN_Repository_AttendantRepository $repo */
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

	    wp_enqueue_script('salon-admin-attendant-edit-js', SLN_PLUGIN_URL.'/js/admin/adminAttendantEdit.js', array('jquery'), SLN_Action_InitScripts::ASSETS_VERSION, true);
	}

    }

    public function wp_insert_post($post_id, $wp_error = false)
    {
        global $post_type;

        if ($post_type == $this->getPostType()) {
            if (!get_post_meta($post_id, '_sln_attendant_order', true)) {
                $count_pages = wp_count_posts($this->getPostType());
                $pos = $count_pages->publish + 1;
                add_post_meta($post_id, '_sln_attendant_order', $pos, true);
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
            update_post_meta($post_id, '_sln_attendant_order', $pos);
        }
    }

    public function manage_columns($columns)
    {

        $new_columns = array(
            'cb' => $columns['cb'],
            'ID' => __('Attendant ID', 'salon-booking-system'),
            'sln_thumb' => __('Thumbnail', 'salon-booking-system'),
            'title' => $columns['title'],
            'sln_services' => __('Skills', 'salon-booking-system'),
            'sln_email' => __('Email', 'salon-booking-system'),
            'sln_phone' => __('Telephone', 'salon-booking-system'),
            'sln_days_off' => __('Availability', 'salon-booking-system'),
        );
//        return array_merge(
//            $columns,
//            array(
//            )
//        );
        return $new_columns;
    }

    public function manage_column($column, $post_id)
    {
        $obj = $this->getPlugin()->createAttendant($post_id);
        switch ($column) {
	    case 'ID' :
                echo edit_post_link($post_id, '<p>', '</p>', $post_id);
                break;
            case 'sln_email':
                echo $obj->getEmail();
                break;
            case 'sln_phone':
                echo $obj->getPhone();
                break;
            case 'sln_days_off':
                echo implode('<br/>', $obj->getAvailabilityItems()->toArray());
                break;
            case 'sln_services':
                if ($obj->hasAllServices()) {
                    echo __("All", 'salon-booking-system');
                } else {
                    $tmp = array();
                    foreach ($obj->getServices() as $s) {
                        $tmp[] = sprintf('<a href="%s" class="sln-service" data-id="%s">%s</a>', get_edit_post_link($s->getId()), $s->getId(), $s->getName());
                    }
                    echo implode(', ', $tmp);
                }
                break;
            case 'sln_thumb':
                echo get_the_post_thumbnail($post_id, array(70, 70));
                break;
        }
    }

    public function enter_title_here($title, $post)
    {

        if ($this->getPostType() === $post->post_type) {
            $title = __('Enter the assistant name', 'salon-booking-system');
        }

        return $title;
    }

    public function updated_messages($messages)
    {
        global $post, $post_ID;

        $messages[$this->getPostType()] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf(
                __('Assistant updated.', 'salon-booking-system')
            ),
            2 => '',
            3 => '',
            4 => __('Assistant updated.', 'salon-booking-system'),
            5 => isset($_GET['revision']) ? sprintf(
                __('Assistant restored to revision from %s', 'salon-booking-system'),
                wp_post_revision_title((int)$_GET['revision'], false)
            ) : false,
            6 => sprintf(
                __('Assistant published.', 'salon-booking-system')
            ),
            7 => __('Assistant saved.', 'salon-booking-system'),
            8 => sprintf(
                __('Assistant submitted.', 'salon-booking-system')
            ),
            9 => sprintf(
                __(
                    'Assistant scheduled for: <strong>%1$s</strong>. ',
                    'salon-booking-system'
                ),
                SLN_TimeFunc::translateDate(__('M j, Y @ G:i', 'salon-booking-system'), SLN_TimeFunc::getPostTimestamp($post))
            ),
            10 => sprintf(
                __('Assistant draft updated.', 'salon-booking-system')
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
                'name' => __('Assistants', 'salon-booking-system'),
                'singular_name' => __('Assistant', 'salon-booking-system'),
                'menu_name' => __('Salon', 'salon-booking-system'),
                'name_admin_bar' => __('Salon Assistant', 'salon-booking-system'),
                'all_items' => __('Assistants', 'salon-booking-system'),
                'add_new' => __('Add Assistant', 'salon-booking-system'),
                'add_new_item' => __('Add New Assistant', 'salon-booking-system'),
                'edit_item' => __('Edit Assistant', 'salon-booking-system'),
                'new_item' => __('New Assistant', 'salon-booking-system'),
                'view_item' => __('View Assistant', 'salon-booking-system'),
                'search_items' => __('Search Assistants', 'salon-booking-system'),
                'not_found' => __('No assistants found', 'salon-booking-system'),
                'not_found_in_trash' => __('No assistants found in trash', 'salon-booking-system'),
                'archive_title' => __('Assistants Archive', 'salon-booking-system'),
            ),
            'capability_type' => array($this->getPostType(), $this->getPostType().'s'),
            'map_meta_cap' => true,
        );
    }

    public function posttype_admin_css()
    {
        global $post_type;
        if ($post_type == SLN_Plugin::POST_TYPE_SERVICE) {
            $this->getPlugin()->loadView('metabox/_attendant_head');
        }
    }

    public function quick_edit_custom_box( $column_name, $post_type ) {

	if ($this->getPostType() !== $post_type || $column_name !== 'sln_email') {
	    return;
	}

	$sRepo    = $this->getPlugin()->getRepository(SLN_Plugin::POST_TYPE_SERVICE);
	$services = $sRepo->getAll();

	usort($services, function ($service1, $service2) {

	    $service1Title = strtolower($service1->getName());
	    $service2Title = strtolower($service2->getName());

	    if ($service1Title === $service2Title) {
		return 0;
	    }

	    return $service1Title > $service2Title ? 1 : -1;
	});

	?>

	    <?php wp_nonce_field( plugin_basename( __FILE__ ), 'sln_attendant_edit_nonce' ); ?>

	    <fieldset class="inline-edit-col-left sln-inline-attendant-edit-col">
		<legend class="inline-edit-legend"><?php echo __( 'Quick Edit' ); ?></legend>
		<div class="inline-edit-col">
		    <label>
			<span class="title"><?php _e( 'Title' ); ?></span>
			<span class="input-text-wrap"><input type="text" name="sln_post_title" class="ptitle" value="" /></span>
		    </label>
		</div>
		<div class="inline-edit-col">
		    <label>
			<span class="title"><?php _e( 'Email' ); ?></span>
			<span class="input-text-wrap"><input type="text" name="sln_email" value="" /></span>
		    </label>
		</div>
		<div class="inline-edit-col">
		    <label>
			<span class="title"><?php _e( 'Phone' ); ?></span>
			<span class="input-text-wrap"><input type="text" name="sln_phone" value="" /></span>
		    </label>
		</div>
	    </fieldset>

	    <fieldset class="inline-edit-col-right sln-inline-attendant-edit-col">
		<div class="inline-edit-col">
		    <label class="alignleft">
			<span class="title"><?php _e( 'Skills' ); ?></span>
			<select name="sln_services[]" multiple="multiple">
			    <?php foreach ($services as $service) : ?>
				<?php if (!$service->isAttendantsEnabled()) continue; ?>
				<option value="<?php echo $service->getId() ?>">
				    <?php echo $service->getName(); ?>
				    (<?php echo $this->getPlugin()->format()->money($service->getPrice()) ?>)
				</option>
			    <?php endforeach ?>
			</select>
		    </label>
		</div>
	    </fieldset>

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

	if ( ! wp_verify_nonce( $_POST["sln_attendant_edit_nonce"], plugin_basename( __FILE__ ) ) ) {
	    return;
	}

	remove_action( 'save_post', array( $this, 'save_post' ), 50 );

	update_post_meta($post_id, '_' . $this->getPostType() . '_email', isset($_POST['sln_email']) ? $_POST['sln_email'] : '');
	update_post_meta($post_id, '_' . $this->getPostType() . '_phone', isset($_POST['sln_phone']) ? $_POST['sln_phone'] : '');
	update_post_meta($post_id, '_' . $this->getPostType() . '_services', isset($_POST['sln_services']) ? $_POST['sln_services'] : array());

	wp_update_post(array(
	   'ID'		 => $post_id,
	    'post_title' => $_POST['sln_post_title'],
	));
    }
}
