<?php

class Vslmd_Menu_Widget extends WP_Widget {

	/**
	 * Sets up a new Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'description' => __( 'Add a custom menu to your sidebar.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'vslmd_nav_menu', __('Visualmodo - Menu'), $widget_ops );
	}

	/**
	 * Outputs the content for the current Custom Menu widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	public function widget( $args, $instance ) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		$alignment = empty($instance['alignment']) ? '' : apply_filters('widget_alignment', $instance['alignment']);
		$color_scheme = empty($instance['color_scheme']) ? '' : apply_filters('widget_color_scheme', $instance['color_scheme']);
		$navigation_structure = empty($instance['navigation_structure']) ? '' : apply_filters('widget_navigation_structure', $instance['navigation_structure']);

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$nav_menu_args = array(
			'fallback_cb' => '',
			'container' => 'nav',
			'container_class' => 'navbar navbar-expand-md '.$instance['color_scheme'].' '.$instance['alignment'].'',
			'menu_class' => 'navbar-nav t_link '.$instance['navigation_structure'].'',
			'menu_id' => 'widget-menu',
			'fallback_cb' => '',
			'walker' => new wp_bootstrap_navwalker(),
			'menu'        => $nav_menu
		);
		
		

		/**
		 * Filters the arguments for the Custom Menu widget.
		 *
		 * @since 4.2.0
		 * @since 4.4.0 Added the `$instance` parameter.
		 *
		 * @param array    $nav_menu_args {
		 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 *     @type mixed         $menu        Menu ID, slug, or name.
		 * }
		 * @param WP_Term  $nav_menu      Nav menu object for the current menu.
		 * @param array    $args          Display arguments for the current widget.
		 * @param array    $instance      Array of settings for the current widget.
		 */
		wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) );

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		if ( ! empty( $new_instance['alignment'] ) ) {
			$instance['alignment'] = $new_instance['alignment'];
		}
		if ( ! empty( $new_instance['color_scheme'] ) ) {
			$instance['color_scheme'] = $new_instance['color_scheme'];
		}
		if ( ! empty( $new_instance['navigation_structure'] ) ) {
			$instance['navigation_structure'] = $new_instance['navigation_structure'];
		}
		return $instance;
	}

	/**
	 * Outputs the settings form for the Custom Menu widget.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {
		global $wp_customize;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$alignment = isset( $instance['alignment'] ) ? $instance['alignment'] : '';
		$color_scheme = isset( $instance['color_scheme'] ) ? $instance['color_scheme'] : '';
		$navigation_structure = isset( $instance['navigation_structure'] ) ? $instance['navigation_structure'] : '';

		// Get menus
		$menus = wp_get_nav_menus();

		// If no menus exists, direct the user to go and create some.
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.' ), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'navigation_structure' ); ?>"><?php _e( 'Navigation Structure:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'navigation_structure' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'navigation_structure' ); ?>">
					<option value="navbar-horizontal" <?php selected( $navigation_structure, 'navbar-horizontal'); ?>><?php _e( 'Horizontal Navigation' ); ?></option>
				    <option value="navbar-vertical" <?php selected( $navigation_structure, 'navbar-vertical'); ?>><?php _e( 'Vertical Navigation' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'alignment' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
					<option value=""><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<option value="justify-content-start text-left" <?php selected( $alignment, 'justify-content-start text-left'); ?>><?php _e( 'Left' ); ?></option>
				    <option value="justify-content-center text-center" <?php selected( $alignment, 'justify-content-center text-center'); ?>><?php _e( 'Center' ); ?></option>
				    <option value="justify-content-end text-right" <?php selected( $alignment, 'justify-content-end text-right'); ?>><?php _e( 'Right' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'color_scheme' ); ?>"><?php _e( 'Color Scheme:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'color_scheme' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'color_scheme' ); ?>">
					<option value=""><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<option value="navbar-light" <?php selected( $color_scheme, 'navbar-light'); ?>><?php _e( 'For Light Background Colors' ); ?></option>
				    <option value="navbar-dark" <?php selected( $color_scheme, 'navbar-dark'); ?>><?php _e( 'For Dark Background Colors' ); ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
				<p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) { echo 'display: none;'; } ?>">
					<button type="button" class="button"><?php _e( 'Edit Menu' ) ?></button>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}

add_action('widgets_init', 'vslmd_menu_widget');

function vslmd_menu_widget() {
	register_widget('Vslmd_Menu_Widget');
}
