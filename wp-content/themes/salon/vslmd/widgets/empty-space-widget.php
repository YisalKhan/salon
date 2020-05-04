<?php
/**
 * Adds Vslmd_Empty_Space widget.
 */
class Vslmd_Empty_Space extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'vslmd_empty_space', // Base ID
			__( 'Visualmodo - Empty Space', 'vslmd' ), // Name
			array( 'description' => __( 'Blank space with custom height.', 'vslmd' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
        public function widget( $args, $instance ) {
            extract( $args );
            $height = empty($instance['height']) ? '' : apply_filters('widget_height', $instance['height']);
			
            
            echo $before_widget;
            if ( ! empty( $height ) ) { ?>
            
                <div class="empty-space-widget" style="height:<?php echo $instance['height'] ?>;"></div>

            <?php } ?>
            <?php echo $after_widget;
        }
        /**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
        public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

                $instance['height'] = strip_tags( $new_instance['height'] );
               
                return $instance;
        }
        /**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
        public function form( $instance ) {
		//print_r($instance);
		
		if ( isset( $instance[ 'height' ] ) ) {
			$height = $instance[ 'height' ];
		}
		else {
			$height = null;
		}
                
        ?>

                
        <p>
            <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height - CSS measurement units allowed' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />
        </p>
                
<?php }
        
} // class Vslmd_Empty_Space ends



// register Vslmd_Empty_Space widget
function register_vslmd_empty_space() {
    register_widget( 'Vslmd_Empty_Space' );
}
add_action( 'widgets_init', 'register_vslmd_empty_space' );