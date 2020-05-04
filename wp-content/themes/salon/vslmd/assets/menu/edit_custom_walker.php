<?php
/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	    global $_wp_nav_menu_max_depth, $vslmdIconCollections;

	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );

	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }

	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );

	    $title = $item->title;

	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)', 'vslmd' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)', 'vslmd'), $item->title );
	    }

	    $title = empty( $item->label ) ? $title : $item->label;

	    ?>
	    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
                        <span class="spinner"></span>
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo esc_url(wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            ));
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'vslmd'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo esc_url(wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            ));
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'vslmd'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item', 'vslmd'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url(add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ));
	                    ?>"><?php _e( 'Edit Menu Item', 'vslmd' ); ?></a>
	                </span>
	            </dt>
	        </dl>

	        <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
	                        <?php _e( 'URL', 'vslmd' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Navigation Label', 'vslmd' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Title Attribute', 'vslmd' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
                        <?php _e( 'Open link in a new window/tab', 'vslmd' ); ?>
                    </label>
                </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'CSS Classes (optional)', 'vslmd' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Link Relationship (XFN)', 'vslmd' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
	                    <?php _e( 'Description', 'vslmd' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'vslmd'); ?></span>
	                </label>
	            </p>

                <?php
                /* New fields insertion starts here */
                ?>
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-anchor-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Anchor', 'vslmd' ); ?><br />
                        <input type="text" id="edit-menu-item-anchor-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-anchor" data-item-option data-name="menu_item_anchor_<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->anchor ); ?>" />
                    </label>
                </p>
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Icon', 'vslmd' ); ?><br />
                        <input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-icon" data-item-option data-name="menu_item_icon_<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->icon ); ?>" />
                    </label>
                </p>
                <p class="field-custom description description-wide">
                    <?php
                    $value = $item->nolink;
                    if($value != "") $value = "checked";
                    ?>
                    <label for="edit-menu-item-nolink-<?php echo esc_attr($item_id); ?>">
                        <input type="checkbox" id="edit-menu-item-nolink-<?php echo esc_attr($item_id); ?>" class="code edit-menu-item-custom" data-item-option data-name="menu_item_nolink_<?php echo esc_attr($item_id); ?>" value="nolink" <?php echo esc_attr($value); ?> />
                        <?php _e( "Don't link", 'vslmd' ); ?>
                    </label>
                </p>
                <p class="field-custom description description-wide">
                    <?php
                    $value = $item->hide;
                    if($value != "") $value = "checked";
                    ?>
                    <label for="edit-menu-item-hide-<?php echo esc_attr($item_id); ?>">
                        <input type="checkbox" id="edit-menu-item-hide-<?php echo esc_attr($item_id); ?>" class="code edit-menu-item-custom" data-item-option data-name="menu_item_hide_<?php echo esc_attr($item_id); ?>" value="hide" <?php echo esc_attr($value); ?> />
                        <?php _e( "Hide Text", 'vslmd' ); ?>
                    </label>
                </p>
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-col-width-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Column Width', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-col-width<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_col_width_<?php echo esc_attr($item_id); ?>">
                        	<option value="" <?php if($item->col_width == ""){echo 'selected="selected"';} ?>></option>
                            <option value="mm-one-full" <?php if($item->col_width == "mm-one-full"){echo 'selected="selected"';} ?>>1/1 - Full</option>
                            <option value="mm-one-half" <?php if($item->col_width == "mm-one-half"){echo 'selected="selected"';} ?>>1/2 - One Half</option>
                            <option value="mm-one-third" <?php if($item->col_width == "mm-one-third"){echo 'selected="selected"';} ?>>1/3 - One Third</option>
                            <option value="mm-one-fourth" <?php if($item->col_width == "mm-one-fourth"){echo 'selected="selected"';} ?>>1/4 - One Fourth</option>
                            <option value="mm-one-sixth" <?php if($item->col_width == "mm-one-sixth"){echo 'selected="selected"';} ?>>1/6 - One Sixth</option>
                        </select>
                    </label>
                </p>  
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-type-menu-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Type', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-type-menu<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_type_menu_<?php echo esc_attr($item_id); ?>">
                            <option value="boxed" <?php if($item->type_menu == "boxed"){echo 'selected="selected"';} ?>>Boxed</option>
                            <option value="wide" <?php if($item->type_menu == "wide"){echo 'selected="selected"';} ?>>Wide</option>
                        </select>
                    </label>
                </p>
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-position-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Submenu Position', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-position<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_position_<?php echo esc_attr($item_id); ?>">
                            <option value="left" <?php if($item->position == "left"){echo 'selected="selected"';} ?>>left</option>
                            <option value="right" <?php if($item->position == "right"){echo 'selected="selected"';} ?>>right</option>
                        </select>
                    </label>
                </p>
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-dropdown-type-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Dropdown Type', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-dropdown-type-<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_dropdown_type_<?php echo esc_attr($item_id); ?>">
                            <option value="1" <?php if($item->dropdown_type == "1"){echo 'selected="selected"';} ?>>Standard Dropdown</option>
                            <option value="2" <?php if($item->dropdown_type == "2"){echo 'selected="selected"';} ?>>Mega Menu Dropdown</option>
                        </select>
                    </label>
                </p> 
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-visibility-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Visibility Control', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-visibility<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_visibility_<?php echo esc_attr($item_id); ?>">
                            <option value="all" <?php if($item->visibility == "all"){echo 'selected="selected"';} ?>>Always Visible</option>
                            <option value="logged" <?php if($item->visibility == "logged"){echo 'selected="selected"';} ?>>Visible Only to Logged Users</option>
                            <option value="visitors" <?php if($item->visibility == "visitors"){echo 'selected="selected"';} ?>>Visible Only to Unlogged visitors</option>
                        </select>
                    </label>
                </p>    
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-sidebar-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Custom widget area', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-sidebar<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_sidebar_<?php echo esc_attr($item_id); ?>">
                            <option value="" <?php if($item->sidebar == ""){echo 'selected="selected"';} ?>></option>
                            <?php
                            foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
                                <option value="<?php echo ucwords( $sidebar['id'] ); ?>" <?php if ($item->sidebar == ucwords( $sidebar['id'] ) ) { ?> selected="selected" <?php } ?>>
                                    <?php echo ucwords( $sidebar['name'] ); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </label>
                </p>
                
                <!--Background Image-->
                
                <p class="field-custom description description-wide">
                    <label for="edit-menu-item-bg-image-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Submenu Background Image', 'vslmd' ); ?><br />
                        <input type="text" id="edit-menu-item-bg-image-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-bg-image" data-item-option data-name="menu_item_bg_image_<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->bg_image ); ?>" />
                    </label>
                </p>   
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-bg-repeat-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Background Repeat', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-bg-repeat<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_bg_repeat_<?php echo esc_attr($item_id); ?>">
                            <option value="no-repeat" <?php if($item->bg_repeat == "no-repeat"){echo 'selected="selected"';} ?>>No Repeat</option>
                            <option value="repeat" <?php if($item->bg_repeat == "repeat"){echo 'selected="selected"';} ?>>Repeat All</option>
                            <option value="repeat-x" <?php if($item->bg_repeat == "repeat-x"){echo 'selected="selected"';} ?>>Repeat Horizontally</option>
                            <option value="repeat-y" <?php if($item->bg_repeat == "repeat-y"){echo 'selected="selected"';} ?>>Repeat Vertically</option>
                            <option value="inherit" <?php if($item->bg_repeat == "inherit"){echo 'selected="selected"';} ?>>Inherit</option>
                        </select>
                    </label>
                </p>  
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-bg-size-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Background Size', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-bg-size<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_bg_size_<?php echo esc_attr($item_id); ?>">
                            <option value="inherit" <?php if($item->bg_size == "inherit"){echo 'selected="selected"';} ?>>Inherit</option>
                            <option value="cover" <?php if($item->bg_size == "cover"){echo 'selected="selected"';} ?>>Cover</option>
                            <option value="contain" <?php if($item->bg_size == "contain"){echo 'selected="selected"';} ?>>Contain</option>
                        </select>
                    </label>
                </p> 
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-bg-attachment-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Background Attachment', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-bg-attachment<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_bg_attachment_<?php echo esc_attr($item_id); ?>">
                            <option value="fixed" <?php if($item->bg_attachment == "fixed"){echo 'selected="selected"';} ?>>Fixed</option>
                            <option value="scroll" <?php if($item->bg_attachment == "scroll"){echo 'selected="selected"';} ?>>Scroll</option>
                            <option value="inherit" <?php if($item->bg_attachment == "inherit"){echo 'selected="selected"';} ?>>Inherit</option>
                        </select>
                    </label>
                </p>    
                <p class="field-custom description description-thin description-thin-custom">
                    <label for="edit-menu-item-bg-position-<?php echo esc_attr($item_id); ?>">
                        <?php _e( 'Background Postion', 'vslmd' ); ?><br />
                        <select class="widefat" id="edit-menu-item-bg-position<?php echo esc_attr($item_id); ?>" data-item-option data-name="menu_item_bg_position_<?php echo esc_attr($item_id); ?>">
                            <option value="left top" <?php if($item->bg_position == "left top"){echo 'selected="selected"';} ?>>Left Top</option>
                            <option value="left center" <?php if($item->bg_position == "left center"){echo 'selected="selected"';} ?>>Left Center</option>
                            <option value="left bottom" <?php if($item->bg_position == "left bottom"){echo 'selected="selected"';} ?>>Left Bottom</option>
                            <option value="center top" <?php if($item->bg_position == "center top"){echo 'selected="selected"';} ?>>Center Top</option>
                            <option value="center center" <?php if($item->bg_position == "center center"){echo 'selected="selected"';} ?>>Center Center</option>
                            <option value="center bottom" <?php if($item->bg_position == "center bottom"){echo 'selected="selected"';} ?>>Center Bottom</option>
                            <option value="right top" <?php if($item->bg_position == "right top"){echo 'selected="selected"';} ?>>Right Top</option>
                            <option value="right center" <?php if($item->bg_position == "right center"){echo 'selected="selected"';} ?>>Right Center</option>
                            <option value="right bottom" <?php if($item->bg_position == "right bottom"){echo 'selected="selected"';} ?>>Right Bottom</option>
                        </select>
                    </label>
                </p>    
				
                <?php
                /* New fields insertion ends here */
                ?>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s', 'vslmd'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
	                echo esc_url(wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                )); ?>"><?php _e('Remove', 'vslmd'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php _e('Cancel', 'vslmd'); ?></a>
	            </div>

	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php

	    $output .= ob_get_clean();

	    }
}
