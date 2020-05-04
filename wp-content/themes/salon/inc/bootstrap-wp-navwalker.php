<?php
/**
 * Adapted from Edward McIntyre's wp_bootstrap_navwalker class.
 * Removed support for glyphicon and added support for Font Awesome.
 *
 * @package cornerstone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_Bootstrap_Navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4
 * navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
	/**
	 * The starting level of the menu.
	 *
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 * @param mixed  $args Rest of arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$options = get_option('vslmd_options');
		$indent = str_repeat( "\t", $depth );
		$header_color_scheme = (!empty($options['header_color_scheme'])) ? $options['header_color_scheme'] : 'light navbar-light';
		$dropdown_menu_color = (!empty($options['dropdown_menu_color'])) ? $options['dropdown_menu_color'] : 'dropdown-menu-dark';

		/* Dropdown Menu Color Scheme */

		if (strpos($header_color_scheme, 'bg-white') !== false ) {
			$dropdown_menu_color = 'dropdown-menu-white';
		}elseif (strpos($header_color_scheme, 'light') !== false) {
			$dropdown_menu_color = 'dropdown-menu-light';
		} elseif (strpos($header_color_scheme, 'dark') !== false) {
			$dropdown_menu_color = 'dropdown-menu-dark';
		} elseif ($dropdown_menu_color == 'dropdown-menu-dark') {
			$dropdown_menu_color = 'dropdown-menu-dark-no-bg';
		} else {
			$dropdown_menu_color = 'dropdown-menu-light-no-bg';
		}

		$output .= "\n$indent<ul class=\" dropdown-menu $dropdown_menu_color\" role=\"menu\">\n";
	}

	/**
	 * Open element.
	 *
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 * @param mixed  $args Rest arguments.
	 * @param int    $id Element's ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider" role="presentation">';
		} else if ( strcasecmp( $item->title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="divider" role="presentation">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li class="dropdown-header" role="presentation">' . esc_html( $item->title );
		} else if ( strcasecmp( $item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li class="disabled" role="presentation"><a href="#">' . esc_html( $item->title ) . '</a>';
		} else {
			$class_names = $value = '';
			$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[]   = 'nav-item menu-item-' . $item->ID;
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			/*
			if ( $args->has_children )
			  $class_names .= ' dropdown';
			*/
			  if ( $args->has_children && $depth === 0 ) {
			  	$class_names .= ' dropdown';
			  } elseif ( $args->has_children && $depth > 0 ) {
			  	$class_names .= ' dropdown-submenu';
			  }
			  if ( in_array( 'current-menu-item', $classes ) ) {
			  	$class_names .= ' active';
			  }
			// remove Font Awesome icon from classes array and save the icon
			// we will add the icon back in via a <span> below so it aligns with
			// the menu item
			  $icon = $sidebar = $sidebar_structure = $position = $type_menu = $col_width = '';

			  $icon = $item->icon;
			  $sidebar = $item->sidebar;
			  $position = $item->position;
			  $type_menu = $item->type_menu;
			  $col_width = $item->col_width;

			  if ( !empty($sidebar) ) { $sidebar_structure = 'dropdown sidebar-dropdown-menu'; }

			  if ( $depth == 0 && $item->dropdown_type == '2') {

			  	$class_names = $class_names ? ' class="' . esc_attr( $class_names ) .' '. $position .' '. $type_menu .' '. $col_width .' '. $sidebar_structure . ' mm"' : '';

				// Background For Mega Menu

			  	if(!empty($item->bg_image)){ ?>

			  	<style>
			  	@media (min-width: 768px) {

			  		<?php echo '.menu-item-' . $item->ID; ?>.dropdown.mm>ul.dropdown-menu {
			  			background-image: url( <?php echo $item->bg_image ?> );
			  			<?php if(!empty($item->bg_repeat)){ ?> background-repeat: <?php echo $item->bg_repeat ?>; <?php } ?>
			  			<?php if(!empty($item->bg_size)){ ?> background-size: <?php echo $item->bg_size ?>; <?php } ?>
			  			<?php if(!empty($item->bg_attachment)){ ?> background-attachment: <?php echo $item->bg_attachment ?>; <?php } ?>
			  			<?php if(!empty($item->bg_position)){ ?> background-position: <?php echo $item->bg_position ?>; <?php } ?>
			  		}
			  		<?php echo '.menu-item-' . $item->ID; ?>.dropdown.mm>ul.dropdown-menu li.dropdown-submenu ul.dropdown-menu {
			  			background-color: transparent !important;
			  		} 



			  	}
			  </style>

			  <?php }

				// End Background For Mega Menu

			} else {
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) .' '. $sidebar_structure . '"' : '';
			}

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			// If item has_children add atts to a.

			if ( !empty( $item->nolink ) ) {

				if ( $args->has_children && $depth === 0 ) {
					$atts['href']          = '';
					$atts['data-toggle']   = 'dropdown';
					$atts['class']         = 'dropdown-toggle';
					$atts['aria-haspopup']    = 'true';
				} else {
					$atts['href'] = '';
				}

			} elseif ( empty( $item->anchor ) ) {

				if ( $args->has_children && $depth === 0 ) {
					$atts['href']          = !empty( $item->url ) ? $item->url : '#';
					$atts['data-toggle']   = 'dropdown';
					$atts['class']         = 'nav-link dropdown-toggle';
					$atts['aria-haspopup']    = 'true';
				} else {
					$atts['href'] = !empty( $item->url ) ? $item->url : '';
					$atts['class'] = 'nav-link';
				}

			} else {
				
				if ( $args->has_children && $depth === 0 ) {
					$atts['href']          = $item->anchor;
					$atts['data-toggle']   = 'dropdown';
					$atts['class']         = 'dropdown-toggle';
					$atts['aria-haspopup']    = 'true';
				} else {
					$atts['href'] = $item->anchor;
					$atts['class'] = 'nav-link';
				}
				
			}
			$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = $args->before;
			
			// Font Awesome icons
			if ( ! empty( $icon ) && !empty( $item->description ) ) {
				$item_output .= '<a'. $attributes .'><span class="icon-title-description fa ' . esc_attr( $icon ) . '"></span>';
			} elseif ( ! empty( $icon ) ) {
				$item_output .= '<a'. $attributes .'><span class="icon-title fa ' . esc_attr( $icon ) . '"></span>';
			} else {
				$item_output .= '<a'. $attributes .'>';	
			}
			
			if ( empty( $item->hide ) ) {
				$item_output .= '<div class="title-content">';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				if ( !empty( $item->description ) ) {
					$item_output .= '<span class="item-description">' . $item->description . '</span>';
				}
				$item_output .= '</div>';
			} else {
				$item_output .= $args->link_before . $args->link_after;
			}
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <i class="fa fa-angle-down dropdown-icon" aria-hidden="true"></i></a>' : '</a>';
			$item_output .= $args->after;	
			
			
			// Sidebar
			if ( !empty($sidebar) ) {	
				$item_output .= '<ul role="menu" class="dropdown-menu"><li class="menu-item dropdown-submenu">';
				ob_start();
				dynamic_sidebar( $sidebar );
				$item_output .= ob_get_contents();
				ob_end_clean();
				$item_output .= '</ul></li>';
			}
			
			if ( $item->visibility == 'all' || $item->visibility == '' ) {

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				
			} else if ( $item->visibility == 'logged' && is_user_logged_in() ) {

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				
			} else if ( $item->visibility == 'visitors' && ! is_user_logged_in() ) {

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			}
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth Max depth to traverse.
	 * @param int    $depth Depth of current element.
	 * @param array  $args
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}
		$id_field = $this->db_fields['id'];
		// Display this element.
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {
			extract( $args );
			$fb_output = null;
			if ( $container ) {
				$fb_output = '<' . $container;
				if ( $container_class ) {
					$fb_output .= ' class="' . $container_class . '"';
				}
				if ( $container_id ) {
					$fb_output .= ' id="' . $container_id . '"';
				}
				$fb_output .= '>';
			}
			$fb_output .= '<ul';
			if ( $menu_class ) {
				$fb_output .= ' class="' . $menu_class . '"';
			}
			if ( $menu_id ) {
				$fb_output .= ' id="' . $menu_id . '"';
			}
			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';
			if ( $container ) {
				$fb_output .= '</' . $container . '>';
			}
			echo $fb_output;
		}
	}
}
