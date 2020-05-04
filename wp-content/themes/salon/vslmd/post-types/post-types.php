<?php

$options = get_option('vslmd_options');

if ($options['knowledgebase_post_type'] == '1') {

	/*-----------------------------------------------------------------------------------*/
/*	Register Knowledgebase
/*-----------------------------------------------------------------------------------*/  

function knowledgebase_post_type() {

	$labels = array(
		'name'                  => _x( 'Knowledgebases', 'Post Type General Name', 'vslmd' ),
		'singular_name'         => _x( 'Knowledgebase', 'Post Type Singular Name', 'vslmd' ),
		'menu_name'             => __( 'Knowledgebases', 'vslmd' ),
		'name_admin_bar'        => __( 'Knowledgebases', 'vslmd' ),
		'archives'              => __( 'Item Archives', 'vslmd' ),
		'attributes'            => __( 'Item Attributes', 'vslmd' ),
		'parent_item_colon'     => __( 'Parent Item:', 'vslmd' ),
		'all_items'             => __( 'All Items', 'vslmd' ),
		'add_new_item'          => __( 'Add New Item', 'vslmd' ),
		'add_new'               => __( 'Add New', 'vslmd' ),
		'new_item'              => __( 'New Item', 'vslmd' ),
		'edit_item'             => __( 'Edit Item', 'vslmd' ),
		'update_item'           => __( 'Update Item', 'vslmd' ),
		'view_item'             => __( 'View Item', 'vslmd' ),
		'view_items'            => __( 'View Items', 'vslmd' ),
		'search_items'          => __( 'Search Item', 'vslmd' ),
		'not_found'             => __( 'Not found', 'vslmd' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'vslmd' ),
		'featured_image'        => __( 'Featured Image', 'vslmd' ),
		'set_featured_image'    => __( 'Set featured image', 'vslmd' ),
		'remove_featured_image' => __( 'Remove featured image', 'vslmd' ),
		'use_featured_image'    => __( 'Use as featured image', 'vslmd' ),
		'insert_into_item'      => __( 'Insert into item', 'vslmd' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'vslmd' ),
		'items_list'            => __( 'Items list', 'vslmd' ),
		'items_list_navigation' => __( 'Items list navigation', 'vslmd' ),
		'filter_items_list'     => __( 'Filter items list', 'vslmd' ),
	);
	$args = array(
		'label'                 => __( 'Knowledgebase', 'vslmd' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions' ),
		'taxonomies'            => array( 'knowledgebase_categories', 'knowledgebase_tags' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-welcome-learn-more',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'knowledgebase', $args );

}
add_action( 'init', 'knowledgebase_post_type', 0 );

// Register Knowledgebase Categories

function knowledgebase_categories() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Categories', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'knowledgebase_categories', array( 'knowledgebase' ), $args );

}
add_action( 'init', 'knowledgebase_categories', 0 );

// Register Knowledgebase Categories Term Meta

class knowledgebasecatego {
	private $meta_fields = array(
		array(
			'label' => 'Color',
			'id' => 'knowledgebase-categories-color',
			'type' => 'color',
		),
		array(
			'label' => 'Icon',
			'id' => 'knowledgebase-categories-icons',
			'type' => 'text',
		),
	);
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'knowledgebase_categories_add_form_fields', array( $this, 'create_fields' ), 10, 2 );
			add_action( 'knowledgebase_categories_edit_form_fields', array( $this, 'edit_fields' ),  10, 2 );
			add_action( 'created_knowledgebase_categories', array( $this, 'save_fields' ), 10, 1 );
			add_action( 'edited_knowledgebase_categories',  array( $this, 'save_fields' ), 10, 1 );
		}
	}
	public function create_fields( $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			if ( empty( $meta_value ) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= '<div class="form-field">'.$this->format_rows( $label, $input ).'</div>';
		}
		echo $output;
	}
	public function edit_fields( $term, $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_term_meta( $term->term_id, $meta_field['id'], true );
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<div class="form-field">' . $output . '</div>';
	}
	public function format_rows( $label, $input ) {
		return '<tr class="form-field"><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $term_id ) {
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_term_meta( $term_id, $meta_field['id'], $_POST[ $meta_field['id']] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_term_meta( $term_id, $meta_field['id'], '0' );
			}
		}
	}
}
if (class_exists('knowledgebasecatego')) {
	new knowledgebasecatego;
};

// Register Knowledgebase Tags

function knowledgebase_tags() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Tags', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'knowledgebase_tags', array( 'knowledgebase' ), $args );

}
add_action( 'init', 'knowledgebase_tags', 0 );

}

if ($options['portfolio_post_type'] == '1') {

	/*-----------------------------------------------------------------------------------*/
/*	Register Portfolio
/*-----------------------------------------------------------------------------------*/  

function portfolio_post_type() {

	$labels = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'vslmd' ),
		'singular_name'         => _x( 'Portfolio', 'Post Type Singular Name', 'vslmd' ),
		'menu_name'             => __( 'Projects', 'vslmd' ),
		'name_admin_bar'        => __( 'Portfolio', 'vslmd' ),
		'archives'              => __( 'Item Archives', 'vslmd' ),
		'attributes'            => __( 'Item Attributes', 'vslmd' ),
		'parent_item_colon'     => __( 'Parent Item:', 'vslmd' ),
		'all_items'             => __( 'All Items', 'vslmd' ),
		'add_new_item'          => __( 'Add New Item', 'vslmd' ),
		'add_new'               => __( 'Add New', 'vslmd' ),
		'new_item'              => __( 'New Item', 'vslmd' ),
		'edit_item'             => __( 'Edit Item', 'vslmd' ),
		'update_item'           => __( 'Update Item', 'vslmd' ),
		'view_item'             => __( 'View Item', 'vslmd' ),
		'view_items'            => __( 'View Items', 'vslmd' ),
		'search_items'          => __( 'Search Item', 'vslmd' ),
		'not_found'             => __( 'Not found', 'vslmd' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'vslmd' ),
		'featured_image'        => __( 'Featured Image', 'vslmd' ),
		'set_featured_image'    => __( 'Set featured image', 'vslmd' ),
		'remove_featured_image' => __( 'Remove featured image', 'vslmd' ),
		'use_featured_image'    => __( 'Use as featured image', 'vslmd' ),
		'insert_into_item'      => __( 'Insert into item', 'vslmd' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'vslmd' ),
		'items_list'            => __( 'Items list', 'vslmd' ),
		'items_list_navigation' => __( 'Items list navigation', 'vslmd' ),
		'filter_items_list'     => __( 'Filter items list', 'vslmd' ),
	);
	$args = array(
		'label'                 => __( 'Portfolio', 'vslmd' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions' ),
		'taxonomies'            => array( 'portfolio_categories', 'portfolio_tags' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'portfolio', $args );

}
add_action( 'init', 'portfolio_post_type', 0 );

// Register Portfolio Categories

function portfolio_categories() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Categories', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'portfolio_categories', array( 'portfolio' ), $args );

}
add_action( 'init', 'portfolio_categories', 0 );

// Register Portfolio Categories Term Meta

class portfoliocategories {
	private $meta_fields = array(
		array(
			'label' => 'Color',
			'id' => 'portfolio-categories-color',
			'type' => 'color',
		),
		array(
			'label' => 'Icon',
			'id' => 'portfolio-categories-icons',
			'type' => 'text',
		),
	);
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'portfolio_categories_add_form_fields', array( $this, 'create_fields' ), 10, 2 );
			add_action( 'portfolio_categories_edit_form_fields', array( $this, 'edit_fields' ),  10, 2 );
			add_action( 'created_portfolio_categories', array( $this, 'save_fields' ), 10, 1 );
			add_action( 'edited_portfolio_categories',  array( $this, 'save_fields' ), 10, 1 );
		}
	}
	public function create_fields( $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			if ( empty( $meta_value ) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= '<div class="form-field">'.$this->format_rows( $label, $input ).'</div>';
		}
		echo $output;
	}
	public function edit_fields( $term, $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_term_meta( $term->term_id, $meta_field['id'], true );
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<div class="form-field">' . $output . '</div>';
	}
	public function format_rows( $label, $input ) {
		return '<tr class="form-field"><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $term_id ) {
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_term_meta( $term_id, $meta_field['id'], $_POST[ $meta_field['id']] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_term_meta( $term_id, $meta_field['id'], '0' );
			}
		}
	}
}
if (class_exists('portfoliocategories')) {
	new portfoliocategories;
};

// Register Portfolio Tags

function portfolio_tags() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Tags', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'portfolio_tags', array( 'portfolio' ), $args );

}
add_action( 'init', 'portfolio_tags', 0 );

}

if ($options['team_post_type'] == '1') {

	/*-----------------------------------------------------------------------------------*/
/*	Register Team
/*-----------------------------------------------------------------------------------*/  

function team_post_type() {

	$labels = array(
		'name'                  => _x( 'Team', 'Post Type General Name', 'vslmd' ),
		'singular_name'         => _x( 'Employee', 'Post Type Singular Name', 'vslmd' ),
		'menu_name'             => __( 'Team', 'vslmd' ),
		'name_admin_bar'        => __( 'Employee', 'vslmd' ),
		'archives'              => __( 'Item Archives', 'vslmd' ),
		'attributes'            => __( 'Item Attributes', 'vslmd' ),
		'parent_item_colon'     => __( 'Parent Item:', 'vslmd' ),
		'all_items'             => __( 'All Items', 'vslmd' ),
		'add_new_item'          => __( 'Add New Item', 'vslmd' ),
		'add_new'               => __( 'Add New', 'vslmd' ),
		'new_item'              => __( 'New Item', 'vslmd' ),
		'edit_item'             => __( 'Edit Item', 'vslmd' ),
		'update_item'           => __( 'Update Item', 'vslmd' ),
		'view_item'             => __( 'View Item', 'vslmd' ),
		'view_items'            => __( 'View Items', 'vslmd' ),
		'search_items'          => __( 'Search Item', 'vslmd' ),
		'not_found'             => __( 'Not found', 'vslmd' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'vslmd' ),
		'featured_image'        => __( 'Featured Image', 'vslmd' ),
		'set_featured_image'    => __( 'Set featured image', 'vslmd' ),
		'remove_featured_image' => __( 'Remove featured image', 'vslmd' ),
		'use_featured_image'    => __( 'Use as featured image', 'vslmd' ),
		'insert_into_item'      => __( 'Insert into item', 'vslmd' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'vslmd' ),
		'items_list'            => __( 'Items list', 'vslmd' ),
		'items_list_navigation' => __( 'Items list navigation', 'vslmd' ),
		'filter_items_list'     => __( 'Filter items list', 'vslmd' ),
	);
	$args = array(
		'label'                 => __( 'Employee', 'vslmd' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions' ),
		'taxonomies'            => array( 'team_categories', 'team_tags' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'team', $args );

}
add_action( 'init', 'team_post_type', 0 );

// Register Team Categories

function team_categories() {

	$labels = array(
		'name'                       => _x( 'Categories', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Categories', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'team_categories', array( 'team' ), $args );

}
add_action( 'init', 'team_categories', 0 );

// Register Team Categories Term Meta

class teamcategoriesextr {
	private $meta_fields = array(
		array(
			'label' => 'Color',
			'id' => 'team-categories-color',
			'type' => 'color',
		),
		array(
			'label' => 'Icon',
			'id' => 'team-categories-icons',
			'type' => 'text',
		),
	);
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'team_categories_add_form_fields', array( $this, 'create_fields' ), 10, 2 );
			add_action( 'team_categories_edit_form_fields', array( $this, 'edit_fields' ),  10, 2 );
			add_action( 'created_team_categories', array( $this, 'save_fields' ), 10, 1 );
			add_action( 'edited_team_categories',  array( $this, 'save_fields' ), 10, 1 );
		}
	}
	public function create_fields( $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			if ( empty( $meta_value ) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= '<div class="form-field">'.$this->format_rows( $label, $input ).'</div>';
		}
		echo $output;
	}
	public function edit_fields( $term, $taxonomy ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_term_meta( $term->term_id, $meta_field['id'], true );
			switch ( $meta_field['type'] ) {
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? '' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<div class="form-field">' . $output . '</div>';
	}
	public function format_rows( $label, $input ) {
		return '<tr class="form-field"><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $term_id ) {
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_term_meta( $term_id, $meta_field['id'], $_POST[ $meta_field['id']] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_term_meta( $term_id, $meta_field['id'], '0' );
			}
		}
	}
}
if (class_exists('teamcategoriesextr')) {
	new teamcategoriesextr;
};

// Register Team Tags

function team_tags() {

	$labels = array(
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'vslmd' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'vslmd' ),
		'menu_name'                  => __( 'Tags', 'vslmd' ),
		'all_items'                  => __( 'All Items', 'vslmd' ),
		'parent_item'                => __( 'Parent Item', 'vslmd' ),
		'parent_item_colon'          => __( 'Parent Item:', 'vslmd' ),
		'new_item_name'              => __( 'New Item Name', 'vslmd' ),
		'add_new_item'               => __( 'Add New Item', 'vslmd' ),
		'edit_item'                  => __( 'Edit Item', 'vslmd' ),
		'update_item'                => __( 'Update Item', 'vslmd' ),
		'view_item'                  => __( 'View Item', 'vslmd' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'vslmd' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'vslmd' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'vslmd' ),
		'popular_items'              => __( 'Popular Items', 'vslmd' ),
		'search_items'               => __( 'Search Items', 'vslmd' ),
		'not_found'                  => __( 'Not Found', 'vslmd' ),
		'no_terms'                   => __( 'No items', 'vslmd' ),
		'items_list'                 => __( 'Items list', 'vslmd' ),
		'items_list_navigation'      => __( 'Items list navigation', 'vslmd' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'team_tags', array( 'team' ), $args );

}
add_action( 'init', 'team_tags', 0 );

}

/*-----------------------------------------------------------------------------------*/
/*	Register Dynamic Post Types
/*-----------------------------------------------------------------------------------*/  

if ( $options['extra-custom-post-types'] != '0' ) {

	$options = get_option('vslmd_options');

	if ( $options['extra-custom-post-types'] != '0' || $options['extra-custom-post-types'] != '' ) {

		$CPT = 0;
		while ($CPT < $options['extra-custom-post-types']) {

			$CPT++;	

			if ( $options['custom-post-type-singular-name-'.$CPT.''] != '' && $options['custom-post-type-plural-name-'.$CPT.''] != '' && $options['custom-post-type-slug-'.$CPT.''] != '') {


	//Convert Post Type Name to Lowercase
				$custom_post_type_slug_lowercase = strtolower ($options['custom-post-type-slug-'.$CPT .'']);


				/*-----------------------------------------------------------------------------------*/
/*	CPT General
/*-----------------------------------------------------------------------------------*/  

	//General Label	
$custom_labels = array(
	'name' => __( $options['custom-post-type-plural-name-'.$CPT .''], 'taxonomy general name', 'vslmd'),
	'singular_name' => __( $options['custom-post-type-singular-name-'.$CPT .''], 'vslmd'),
	'search_items' =>  __( 'Search' .' '. $options['custom-post-type-singular-name-'.$CPT .''] .' '. 'Item', 'vslmd'),
	'all_items' => __( 'All' .' '. $options['custom-post-type-plural-name-'.$CPT .''], 'vslmd'),
	'parent_item' => __( 'Parent' .' '. $options['custom-post-type-singular-name-'.$CPT .''] .' '. 'Item', 'vslmd'),
	'edit_item' => __( 'Edit' .' '. $options['custom-post-type-singular-name-'.$CPT .''] .' '. 'Item', 'vslmd'),
	'update_item' => __( 'Update' .' '. $options['custom-post-type-singular-name-'.$CPT .''] .' '. 'Item', 'vslmd'),
	'add_new_item' => __( 'Add New' .' '. $options['custom-post-type-singular-name-'.$CPT .''] .' '. 'Item', 'vslmd')
);

	//Build Custom Post Type
$args = array(
	'public' => true,
	'labels' => $custom_labels,
	'menu_icon' => $options['custom-post-type-icon-'.$CPT .''],
	'rewrite' => array('slug' => $custom_post_type_slug_lowercase,'with_front' => false),
	'singular_label' => __($options['custom-post-type-singular-name-'.$CPT .''], 'vslmd'),
	'public' => true,
	'publicly_queryable' => true,
	'show_ui' => true,
	'hierarchical' => false,
	'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments' ), 
);
register_post_type( $custom_post_type_slug_lowercase, $args );

/*-----------------------------------------------------------------------------------*/
/*	CPT Category
/*-----------------------------------------------------------------------------------*/  

	//Build Custom Post Type Category
register_taxonomy($custom_post_type_slug_lowercase.'-category', 
	array($custom_post_type_slug_lowercase), 
	array("hierarchical" => true, 
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $custom_post_type_slug_lowercase.'-category' )
	));

/*-----------------------------------------------------------------------------------*/
/*	CPT Tag
/*-----------------------------------------------------------------------------------*/  

	//Custom Post Type Tag Label
$custom_post_type_tag_labels = array(
	'name' => __( 'Tags', 'vslmd'),
	'singular_name' => __( 'Tag', 'vslmd'),
	'search_items' =>  __( 'Search Tags', 'vslmd'),
	'all_items' => __( 'All Tags', 'vslmd'),
	'parent_item' => __( 'Parent Tag', 'vslmd'),
	'edit_item' => __( 'Edit Tag', 'vslmd'),
	'update_item' => __( 'Update Tag', 'vslmd'),
	'add_new_item' => __( 'Add New Tag', 'vslmd'),
	'new_item_name' => __( 'New Tag', 'vslmd'),
	'menu_name' => __( 'Tags', 'vslmd')
); 	

	//Build Custom Post Type Label
register_taxonomy($custom_post_type_slug_lowercase.'-tag',
	array($custom_post_type_slug_lowercase),
	array('hierarchical' => true,
		'labels' => $custom_post_type_tag_labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $custom_post_type_slug_lowercase.'-tag' )
	)); 

/*-----------------------------------------------------------------------------------*/ 

}
}
}


/*-----------------------------------------------------------------------------------*/
/*	CPT Icon Category
/*-----------------------------------------------------------------------------------*/ 

		// Add term page
function vslmd_taxonomy_add_new_meta_field() {
			// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[category_icon]"><?php _e( 'Icon', 'vslmd' ); ?></label>
		<input type="text" name="term_meta[category_icon]" id="term_meta[category_icon]" value="">
		<p class="description"><?php _e( 'Enter an icon for category. This icons are used for example in Post filter.','vslmd' ); ?></p>
	</div>
	<?php
}	

		// Edit term page
function vslmd_taxonomy_edit_meta_field($term) {

			// put the term ID into a variable
	$t_id = $term->term_id;

			// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[category_icon]"><?php _e( 'Icon', 'vslmd' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[category_icon]" id="term_meta[category_icon]" value="<?php echo esc_attr( $term_meta['category_icon'] ) ? esc_attr( $term_meta['category_icon'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter an icon for category. This icons are used for example in Post filter.','vslmd' ); ?></p>
		</td>
	</tr>
	<?php
}


		// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
				// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  

$options = get_option('vslmd_options');
$CPT = 0;
while ($CPT < $options['extra-custom-post-types']) {
	$CPT++;	
			//Convert Post Type Name to Lowercase
	$custom_post_type_slug_lowercase = strtolower ($options['custom-post-type-slug-'.$CPT .'']);  
	add_action( ''.$custom_post_type_slug_lowercase.'-category_add_form_fields', 'vslmd_taxonomy_add_new_meta_field', 10, 2 );
	add_action( ''.$custom_post_type_slug_lowercase.'-category_edit_form_fields', 'vslmd_taxonomy_edit_meta_field', 10, 2 );
	add_action( 'edited_'.$custom_post_type_slug_lowercase.'-category', 'save_taxonomy_custom_meta', 10, 2 );  
	add_action( 'create_'.$custom_post_type_slug_lowercase.'-category', 'save_taxonomy_custom_meta', 10, 2 );

}


/*-----------------------------------------------------------------------------------*/
/*	Include Single CPT
/*-----------------------------------------------------------------------------------*/ 

add_filter( 'template_include', 'include_template_CPT_function', 1 );

function include_template_CPT_function( $template_path ) {
	$options = get_option('vslmd_options');

	if ( $options['extra-custom-post-types'] != '0' || $options['extra-custom-post-types'] != '' ) {

		$CPT = 0;
		while ($CPT < $options['extra-custom-post-types']) {

			$CPT++;	

			if ( $options['custom-post-type-singular-name-'.$CPT.''] != '' && $options['custom-post-type-plural-name-'.$CPT.''] != '' && $options['custom-post-type-slug-'.$CPT.''] != '') {

				//Convert Post Type Name to Lowercase
				$custom_post_type_slug_lowercase = strtolower ($options['custom-post-type-slug-'.$CPT .'']);	

				if ( get_post_type() == $custom_post_type_slug_lowercase ) {
					if ( is_single() ) {
						$template_path = get_template_directory() . '/vslmd/post-types/single-cpt.php';
					}
				}
			}
		}
		return $template_path;
	}
}
}