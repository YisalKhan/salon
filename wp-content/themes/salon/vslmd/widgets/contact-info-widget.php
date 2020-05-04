<?php
/**
 * Adds Vslmd_Contat_Information widget.
 */
class Vslmd_Contat_Information extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'vslmd_contat_information', // Base ID
			__( 'Visualmodo - Contact Information', 'vslmd' ), // Name
			array( 'description' => __( 'Show your contact information.', 'vslmd' ), ) // Args
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
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $name = empty($instance['name']) ? '' : apply_filters('widget_name', $instance['name']);
			
            $alignment = empty($instance['alignment']) ? '' : apply_filters('widget_alignment', $instance['alignment']);
            $color_scheme = empty($instance['color_scheme']) ? '' : apply_filters('widget_color_scheme', $instance['color_scheme']);
			$whatsapp = empty($instance['whatsapp']) ? '' : apply_filters('widget_whatsapp', $instance['whatsapp']);
            $phone = empty($instance['phone']) ? '' : apply_filters('widget_phone', $instance['phone']);
            $hours = empty($instance['hours']) ? '' : apply_filters('widget_hours', $instance['hours']);
            $address = empty($instance['address']) ? '' : apply_filters('widget_address', $instance['address']);
            $email = empty($instance['email']) ? '' : apply_filters('widget_email', $instance['email']);

			
			
            
            echo $before_widget;
            if ( ! empty( $title ) )
			echo $before_title . $title . $after_title; ?>
            
            <!-- front display here -->
                <div class="vslmd-widget-container contat-information-widget <?php echo $instance['alignment'] .' '. $instance['color_scheme'] ?>">
                <ul>

                    <?php if($whatsapp) { ?>
                    <li class="contat-information-widget-item">
                        <span><i class="fa fa-whatsapp"></i><?php echo $whatsapp; ?></span>
                    </li>
                    <?php } ?>

                    <?php if($phone) { ?>
                    <li class="contat-information-widget-item">
                        <span><i class="fa fa-phone"></i><?php echo $phone; ?></span>
                    </li>
                    <?php } ?>

                    <?php if($hours) { ?>
                    <li class="contat-information-widget-item">
                        <span><i class="fa fa-clock-o"></i><?php echo $hours; ?></span>
                    </li>
                    <?php } ?>

                    <?php if($address) { ?>
                    <li class="contat-information-widget-item">
                        <span><i class="fa fa-map-marker"></i><?php echo $address; ?></span>
                    </li>
                    <?php } ?>

                    <?php if($email) { ?>
                    <li class="contat-information-widget-item">
                        <span><i class="fa fa-envelope-o"></i><?php echo $email; ?></span>
                    </li>
                    <?php } ?>

                </ul>
                </div>
            
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

                $instance['title'] = strip_tags( $new_instance['title'] );
                $instance['name'] = strip_tags( $new_instance['name'] );
                
                $instance['alignment'] = strip_tags( $new_instance['alignment'] );
                $instance['color_scheme'] = $new_instance['color_scheme'];
				$instance['whatsapp'] = strip_tags( $new_instance['whatsapp'] );
                $instance['phone'] = strip_tags( $new_instance['phone'] );
                $instance['hours'] = strip_tags( $new_instance['hours'] );
                $instance['address'] = strip_tags( $new_instance['address'] );
                $instance['email'] = strip_tags( $new_instance['email'] );
               
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
		
		if ( isset( $instance[ 'alignment' ] ) ) {
			$alignment = $instance[ 'alignment' ];
		}
		else {
			$alignment = null;
        }
        if ( isset( $instance[ 'color_scheme' ] ) ) {
			$color_scheme = $instance[ 'color_scheme' ];
		}
		else {
			$color_scheme = null;
		}
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = null;
        } 

        if ( isset( $instance[ 'name' ] ) ) {
            $name = $instance[ 'name' ];
        }
        else {
            $name = null;
        }
		
		if ( isset( $instance[ 'whatsapp' ] ) ) {
			$whatsapp = $instance[ 'whatsapp' ];
		}
		else {
			$whatsapp = null;
        }

        if ( isset( $instance[ 'phone' ] ) ) {
            $phone = $instance[ 'phone' ];
        }
        else {
            $phone = null;
        }

        if ( isset( $instance[ 'hours' ] ) ) {
            $hours = $instance[ 'hours' ];
        }
        else {
            $hours = null;
        }

        if ( isset( $instance[ 'address' ] ) ) {
            $address = $instance[ 'address' ];
        }
        else {
            $address = null;
        }

        if ( isset( $instance[ 'email' ] ) ) {
            $email = $instance[ 'email' ];
        }
        else {
            $email = null;
        }
                
        ?>
            
		
        <p>
			<label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e('Alignment:', 'vslmd') ?></label>
			<select id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>" class="widefat">
		    <option value='widget-align-left'<?php selected( $alignment, 'widget-align-left'); ?>><?php _e( 'Left' ); ?></option>
          	<option value='widget-align-center'<?php selected( $alignment, 'widget-align-center'); ?>><?php _e( 'Center' ); ?></option> 
          	<option value='widget-align-right'<?php selected( $alignment, 'widget-align-right'); ?>><?php _e( 'Right' ); ?></option> 
			</select>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id( 'color_scheme' ); ?>"><?php _e( 'Color Scheme:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'color_scheme' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'color_scheme' ); ?>">
                <option value=""><?php _e( '&mdash; Select &mdash;' ); ?></option>
                <option value="contact-information-light" <?php selected( $color_scheme, 'contact-information-light'); ?>><?php _e( 'For Light Background Colors' ); ?></option>
                <option value="contact-information-dark" <?php selected( $color_scheme, 'contact-information-dark'); ?>><?php _e( 'For Dark Background Colors' ); ?></option>
            </select>
        </p>        
        <p>
            <label for="<?php echo $this->get_field_id( 'whatsapp' ); ?>"><?php _e( 'Whatsapp:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'whatsapp' ); ?>" name="<?php echo $this->get_field_name( 'whatsapp' ); ?>" type="text" value="<?php echo esc_attr( $whatsapp ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Phone:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'hours' ); ?>"><?php _e( 'Business hours:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'hours' ); ?>" name="<?php echo $this->get_field_name( 'hours' ); ?>" type="text" value="<?php echo esc_attr( $hours ); ?>" />
        </p>  
        <p>
            <label for="<?php echo $this->get_field_id( 'address' ); ?>"><?php _e( 'Address:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" type="text" value="<?php echo esc_attr( $address ); ?>" />
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Email:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" />
        </p> 
                
<?php }
        
} // class Vslmd_Contat_Information ends



// register Vslmd_Contat_Information widget
function register_vslmd_contat_information() {
    register_widget( 'Vslmd_Contat_Information' );
}
add_action( 'widgets_init', 'register_vslmd_contat_information' );