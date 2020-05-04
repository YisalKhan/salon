<?php

abstract class SLN_PostType_Abstract
{
    private $postType;
    private $plugin;

    public function __construct(SLN_Plugin $plugin, $postType)
    {
        $this->plugin   = $plugin;
        $this->postType = $postType;
        $this->init();
        if(is_admin()){
            global $pagenow;
            if($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $this->getPostType()) {
                add_filter( 'wpseo_use_page_analysis', '__return_false' );
            }
        }
        add_filter('post_updated_messages', array($this, 'updated_messages'));
        add_filter('enter_title_here', array($this, 'enter_title_here'), 10, 2);

	add_filter('post_row_actions', array($this, 'duplicateActionMakeLink'), 10, 2);
	add_action('admin_action_sln_duplicate_post', array($this, 'duplicatePostSaveAsNewPost'));
    }

    public function init()
    {
        register_post_type($this->getPostType(), $this->getPostTypeArgs());
    }

    abstract protected function getPostTypeArgs();

    abstract public function enter_title_here($title, $post);

    abstract public function updated_messages($messages);

    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @return SLN_Plugin
     */
    protected function getPlugin()
    {
        return $this->plugin;
    }

    public function duplicateActionMakeLink($actions, $post) {

	if (current_user_can('copy_posts') && in_array($post->post_type, array(SLN_Plugin::POST_TYPE_SERVICE, SLN_Plugin::POST_TYPE_ATTENDANT))) {

	    $actions['clone'] = '<a href="'.$this->getDuplicateActionPostLink($post->ID).'" title="'
				. esc_attr__("Duplicate this item", 'salon-booking-system')
				. '">' .  esc_html__('Duplicate', 'salon-booking-system') . '</a>';
	}

	return $actions;
    }

    public function getDuplicateActionPostLink( $id = 0, $context = 'display') {

	$action_name = "sln_duplicate_post";

	if ( 'display' == $context ) {
	    $action = '?action='.$action_name.'&amp;post='.$id;
	} else {
	    $action = '?action='.$action_name.'&post='.$id;
	}

	return wp_nonce_url(admin_url( "admin.php". $action ), 'sln_duplicate-post_' . $id);
    }

    public function duplicatePostSaveAsNewPost() {

	if(!current_user_can('copy_posts')){
	    wp_die(esc_html__('Current user is not allowed to copy posts.', 'salon-booking-system'));
	}

	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'sln_duplicate_post' == $_REQUEST['action'] ) ) ) {
	    wp_die(esc_html__('No post to duplicate has been supplied!', 'salon-booking-system'));
	}

	// Get the original post
	$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

	check_admin_referer('sln_duplicate-post_' . $id);

	$post = get_post($id);

	if (!$post) {
	    wp_die(esc_html__('Copy creation failed, could not find original:', 'salon-booking-system') . ' ' . htmlspecialchars($id));
	}

	$new_id = $this->duplicatePostCreateDuplicate($post);

	wp_redirect( add_query_arg( array( 'cloned' => 1, 'ids' => $post->ID), admin_url( 'post.php?action=edit&post=' . $new_id ) ) );

	exit;
    }

    public function duplicatePostCreateDuplicate($post) {

	$new_post_status = $post->post_status;

	$title = $post->post_title . ' ' . __('[Copy]');

	if ( 'publish' == $new_post_status || 'future' == $new_post_status ){
	    // check if the user has the right capability
	    if(is_post_type_hierarchical( $post->post_type )){
		if(!current_user_can('publish_pages')){
			$new_post_status = 'pending';
		}
	    } else {
		if(!current_user_can('publish_posts')){
			$new_post_status = 'pending';
		}
	    }
	}

	$new_post_author = wp_get_current_user();
	$new_post_author_id = $new_post_author->ID;

	$menu_order = $post->menu_order + 1;

	$post_name = $post->post_name;

	$new_post = array(
	    'menu_order'	    => $menu_order,
	    'comment_status'	    => $post->comment_status,
	    'ping_status'	    => $post->ping_status,
	    'post_author'	    => $new_post_author_id,
	    'post_content'	    => $post->post_content,
	    'post_content_filtered' => $post->post_content_filtered,
	    'post_excerpt'	    => $post->post_excerpt,
	    'post_mime_type'	    => $post->post_mime_type,
	    'post_parent'	    => $post->post_parent,
	    'post_password'	    => $post->post_password,
	    'post_status'	    => $new_post_status,
	    'post_title'	    => $title,
	    'post_type'		    => $post->post_type,
	    'post_name'		    => $post_name
	);

	$new_post_id = wp_insert_post(wp_slash($new_post));

	if($new_post_id !== 0 && !is_wp_error($new_post_id)) {

	    $post_meta_keys = get_post_custom_keys($post->ID);

	    $meta_blacklist   = array();
	    $meta_blacklist[] = '_edit_lock'; // edit lock
	    $meta_blacklist[] = '_edit_last'; // edit lock

	    $meta_blacklist_string = '('.implode(')|(',$meta_blacklist).')';
	    if(strpos($meta_blacklist_string, '*') !== false){
		$meta_blacklist_string = str_replace(array('*'), array('[a-zA-Z0-9_]*'), $meta_blacklist_string);

		$meta_keys = array();
		foreach($post_meta_keys as $meta_key){
			if(!preg_match('#^'.$meta_blacklist_string.'$#', $meta_key))
				$meta_keys[] = $meta_key;
		}
	    } else {
		$meta_keys = array_diff($post_meta_keys, $meta_blacklist);
	    }

	    foreach ($meta_keys as $meta_key) {
		$meta_values = get_post_custom_values($meta_key, $post->ID);
		foreach ($meta_values as $meta_value) {
		    $meta_value = maybe_unserialize($meta_value);
		    add_post_meta($new_post_id, $meta_key, wp_slash($meta_value));
		}
	    }

	    global $wpdb;

	    if (isset($wpdb->terms)) {
		    // Clear default category (added by wp_insert_post)
		    wp_set_object_terms( $new_post_id, NULL, 'category' );

		    $post_taxonomies = get_object_taxonomies($post->post_type);
		    // several plugins just add support to post-formats but don't register post_format taxonomy
		    if(post_type_supports($post->post_type, 'post-formats') && !in_array('post_format', $post_taxonomies)){
			    $post_taxonomies[] = 'post_format';
		    }

		    $taxonomies = $post_taxonomies;
		    foreach ($taxonomies as $taxonomy) {
			    $post_terms = wp_get_object_terms($post->ID, $taxonomy, array( 'orderby' => 'term_order' ));
			    $terms = array();
			    for ($i=0; $i<count($post_terms); $i++) {
				    $terms[] = $post_terms[$i]->slug;
			    }
			    wp_set_object_terms($new_post_id, $terms, $taxonomy);
		    }
	    }

	// get thumbnail ID
	$old_thumbnail_id = get_post_thumbnail_id($post->ID);
	// get children
	$children = get_posts(array( 'post_type' => 'any', 'numberposts' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ));
	// clone old attachments
	foreach($children as $child){
		if ($child->post_type != 'attachment') continue;
		$url = wp_get_attachment_url($child->ID);
		// Let's copy the actual file
		$tmp = download_url( $url );
		if( is_wp_error( $tmp ) ) {
			@unlink($tmp);
			continue;
		}

		$desc = wp_slash($child->post_content);

		$file_array = array();
		$file_array['name'] = basename($url);
		$file_array['tmp_name'] = $tmp;
		// "Upload" to the media collection
		$new_attachment_id = media_handle_sideload( $file_array, $new_post_id, $desc );

		if ( is_wp_error($new_attachment_id) ) {
			@unlink($file_array['tmp_name']);
			continue;
		}
		$new_post_author = wp_get_current_user();
		$cloned_child = array(
				'ID'           => $new_attachment_id,
				'post_title'   => $child->post_title,
				'post_exceprt' => $child->post_title,
				'post_author'  => $new_post_author->ID
		);
		wp_update_post( wp_slash($cloned_child) );

		$alt_title = get_post_meta($child->ID, '_wp_attachment_image_alt', true);
		if($alt_title) update_post_meta($new_attachment_id, '_wp_attachment_image_alt', wp_slash($alt_title));

		// if we have cloned the post thumbnail, set the copy as the thumbnail for the new post
		if($old_thumbnail_id == $child->ID){
		    set_post_thumbnail($new_post_id, $new_attachment_id);
		}
	    }

	    delete_post_meta($new_post_id, '_sln_dp_original');
	    add_post_meta($new_post_id, '_sln_dp_original', $post->ID);
	}

	return $new_post_id;
    }


}
