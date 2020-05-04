<?php
/**
 * Visualmodo Flickr Widget
 */

 
class Vslmd_Flickr_Widget extends WP_Widget {
	
	var $prefix;
	var $textdomain;
	
	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.7
	 */
	function __construct() {
		$this->prefix = 'vslmd';
		$this->textdomain = 'vslmd';
		
		$this->plugin_file = 'flickr-widget/flickr-widget.php';
		$this->settings_url = admin_url( 'widgets.php' );
		
		$widget_ops = array('classname' => 'widget-flickr', 'description' => __('Displays photos on flickr.', $this->textdomain) );
		parent::__construct("{$this->prefix}-flickr-widget", __('Visualmodo - Flickr', $this->textdomain), $widget_ops); 
		
		// Filtering pluginn action links and plugin row meta
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'),  10, 2 );
		add_filter( 'plugin_row_meta', array(&$this, 'plugin_row_meta'),  10, 2 );
	}
	
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.7
	 */
	function widget($args, $instance) {  
		extract( $args );
		
		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		$title = $instance['title'];
		if ( $title) {
			$title =  apply_filters( 'widget_title',  $title, $instance, $this->id_base );
			$title = str_replace('flickr', '<span>flick<span>r</span></span>', $title);
			echo $before_title . $title . $after_title;
		}
		
		$query_args = array();	
		$query_args['size'] = !empty($instance['size']) ? $instance['size'] : '';
		$query_args['count'] = !empty($instance['count']) ? $instance['count'] : '';
		$query_args['display'] = !empty($instance['display']) ? $instance['display'] : 'latest';
		$query_args['layout'] = !empty($instance['layout']) ? $instance['layout'] : 'x';
		$query_args['source'] = !empty($instance['source']) ? $instance['source'] : 'user';
		if(!empty($instance['tag'])) {
			if($instance['source'] == 'user')
				$query_args['source'] = 'user_tag';
			elseif($instance['source'] == 'group')
				$query_args['source'] = 'group_tag';
			elseif($instance['source'] == 'all')
				$query_args['source'] = 'all_tag';
		}
		if($instance['source'] == 'user')
			$query_args['user'] = $instance['id'];
		elseif($instance['source'] == 'user_set')
			$query_args['set'] = $instance['id'];
		elseif($instance['source'] == 'group')
			$query_args['group'] = $instance['id'];
		
		echo '<div class="flickr-badges flickr-badges-'.$instance['size'].'">';
        echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'.http_build_query($query_args).'"></script>'; 
		echo '</div>';
			
	   echo $after_widget;
   }
	
	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.7
	 */
	function update($new_instance, $old_instance) {                
       return $new_instance;
   }
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.7
	 */
	function form($instance) {  
		$defaults = array(
			'title' => 'Photos on flickr',
			'source' => 'user', //user, group, user_set, all
			'id' => '',
			'size' => 's',
			'count' => '9',
			'display' => 'latest', // latest, random
			'tag' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
   
		$display = array( 'latest' => __( 'Latest', $this->textdomain ), 'random' => __( 'Random', $this->textdomain ) );
		$size = array( 's' => __( 'Small', $this->textdomain ), 't' => __( 'Thumbnail', $this->textdomain ), 'm' => __( 'Medium', $this->textdomain ) );
		$source = array( 'user' => __( 'User', $this->textdomain ), 'group' => __( 'Group', $this->textdomain ), 'user_set' => __( 'Set', $this->textdomain ), 'all' => __( 'Public', $this->textdomain ) );
		$count = array(1,2,3,4,5,6,7,8,9,10);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->textdomain ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'source' ); ?>">Source:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'source' ); ?>" name="<?php echo $this->get_field_name( 'source' ); ?>">
				<?php foreach ( $source as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['source'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a target="_blank" href="http://www.idgettr.com">idGettr</a>):', $this->textdomain); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo esc_attr( $instance['id'] ); ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e( 'Tags:', $this->textdomain ); ?> <span class="description"><?php _e( 'Separate tag with commas', $this->textdomain ); ?></span></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo esc_attr( $instance['tag'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>">Number:</label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>">
				<?php foreach ( $count as $option_value ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['count'], $option_value ); ?>><?php echo $option_value; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>">Sorting:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
				<?php foreach ( $display as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['display'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>">Size:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
				<?php foreach ( $size as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['size'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
       <?php
	}
	
	function plugin_action_links( $actions, $plugin_file ) {
			if ( $plugin_file == $this->plugin_file && $this->settings_url)
				$actions[] = '<a href="'.$this->settings_url.'">' . __('Settings', 'liber-core') .'</a>';
			
			return $actions;
		}
	
	function plugin_row_meta( $plugin_meta, $plugin_file ){
			if ( $plugin_file == $this->plugin_file ) {
				$plugin_meta[] = '<a href="'.$this->donate_url.'">' . __('Donate', 'liber-core') .'</a>';
				$plugin_meta[] = '<a href="'.$this->support_url.'">' . __('Support', 'liber-core') .'</a>';
			}

			return $plugin_meta;
		}

}
 
add_action('widgets_init', 'register_dp_flickr_widget');

function register_dp_flickr_widget() {
	register_widget('Vslmd_Flickr_Widget');
}