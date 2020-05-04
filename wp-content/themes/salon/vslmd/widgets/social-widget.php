<?php
/**
 * Adds Vslmd_Social_Icons widget.
 */
class Vslmd_Social_Icons extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'vslmd_social_icons', // Base ID
			__( 'Visualmodo - Social Icons', 'vslmd' ), // Name
			array( 'description' => __( 'Show your social Profiles.', 'vslmd' ), ) // Args
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
            $amazon = empty($instance['amazon']) ? '' : apply_filters('widget_amazon', $instance['amazon']);
			$apple = empty($instance['apple']) ? '' : apply_filters('widget_apple', $instance['apple']); 
			$behance = empty($instance['behance']) ? '' : apply_filters('widget_behance', $instance['behance']); 
			$delicious = empty($instance['delicious']) ? '' : apply_filters('widget_delicious', $instance['delicious']);  
			$deviantart = empty($instance['deviantart']) ? '' : apply_filters('widget_deviantart', $instance['deviantart']);
			$dropbox = empty($instance['dropbox']) ? '' : apply_filters('widget_dropbox', $instance['dropbox']); 
			$digg = empty($instance['digg']) ? '' : apply_filters('widget_digg', $instance['digg']);
			$dribbble = empty($instance['dribbble']) ? '' : apply_filters('widget_dribbble', $instance['dribbble']); 
            $facebook = empty($instance['facebook']) ? '' : apply_filters('widget_facebook', $instance['facebook']);
			$flickr = empty($instance['flickr']) ? '' : apply_filters('widget_flickr', $instance['flickr']);
			$github = empty($instance['github']) ? '' : apply_filters('widget_github', $instance['github']);
			$google = empty($instance['google']) ? '' : apply_filters('widget_google', $instance['google']); 
			$googleplus = empty($instance['googleplus']) ? '' : apply_filters('widget_googleplus', $instance['googleplus']);
			$googlewallet = empty($instance['googlewallet']) ? '' : apply_filters('widget_googlewallet', $instance['googlewallet']);
			$html5 = empty($instance['html5']) ? '' : apply_filters('widget_html5', $instance['html5']);
			$instagram = empty($instance['instagram']) ? '' : apply_filters('widget_instagram', $instance['instagram']);
			$lastfm = empty($instance['lastfm']) ? '' : apply_filters('widget_lastfm', $instance['lastfm']);
			$linkedin = empty($instance['linkedin']) ? '' : apply_filters('widget_linkedin', $instance['linkedin']);
			$medium = empty($instance['medium']) ? '' : apply_filters('widget_medium', $instance['medium']);
			$paypal = empty($instance['paypal']) ? '' : apply_filters('widget_paypal', $instance['paypal']); 
			$pinterest = empty($instance['pinterest']) ? '' : apply_filters('widget_pinterest', $instance['pinterest']);
			$reddit = empty($instance['reddit']) ? '' : apply_filters('widget_reddit', $instance['reddit']);
			$rss = empty($instance['rss']) ? '' : apply_filters('widget_rss', $instance['rss']); 
			$skype = empty($instance['skype']) ? '' : apply_filters('widget_skype', $instance['skype']); 
			$slack = empty($instance['slack']) ? '' : apply_filters('widget_slack', $instance['slack']);
			$steam = empty($instance['steam']) ? '' : apply_filters('widget_steam', $instance['steam']);
            $tripadvisor = empty($instance['tripadvisor']) ? '' : apply_filters('widget_tripadvisor', $instance['tripadvisor']);
			$tumblr = empty($instance['tumblr']) ? '' : apply_filters('widget_tumblr', $instance['tumblr']);
			$twitch = empty($instance['twitch']) ? '' : apply_filters('widget_twitch', $instance['twitch']); 
            $twitter = empty($instance['twitter']) ? '' : apply_filters('widget_twitter', $instance['twitter']);
			$vimeo = empty($instance['vimeo']) ? '' : apply_filters('widget_vimeo', $instance['vimeo']);
			$whatsapp = empty($instance['whatsapp']) ? '' : apply_filters('widget_whatsapp', $instance['whatsapp']);
			$wordpress = empty($instance['wordpress']) ? '' : apply_filters('widget_wordpress', $instance['wordpress']);
			$yahoo = empty($instance['yahoo']) ? '' : apply_filters('widget_yahoo', $instance['yahoo']); 
			$yelp = empty($instance['yelp']) ? '' : apply_filters('widget_yelp', $instance['yelp']);
            $youtube = empty($instance['youtube']) ? '' : apply_filters('widget_youtube', $instance['youtube']);
			
			
            
            echo $before_widget;
            if ( ! empty( $title ) )
			echo $before_title . $title . $after_title; ?>
            
            <!-- front display here -->
            <div>
                <div style="font-weight: bold; padding: 0 0 2px 0;">
                    <?php echo $name; ?>
                </div>
                <div class="vslmd-widget-container social-icons-widget <?php echo $instance['alignment'] .' '. $instance['color_scheme'] ?>">
                <ul>

                	<?php if($amazon) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $amazon; ?>" target="_blank" title="Amazon"><i class="fa fa-amazon"></i></a>
                    </li>
                    <?php } ?>
                	
                    <?php if($apple) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $apple; ?>" target="_blank" title="Apple"><i class="fa fa-apple"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($behance) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $behance; ?>" target="_blank" title="Behance"><i class="fa fa-behance"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($delicious) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $delicious; ?>" target="_blank" title="Delicious"><i class="fa fa-delicious"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($deviantart) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $deviantart; ?>" target="_blank" title="Deviantart"><i class="fa fa-deviantart"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($dropbox) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $dropbox; ?>" target="_blank" title="Dropbox"><i class="fa fa-dropbox"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($digg) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $digg; ?>" target="_blank" title="Digg"><i class="fa fa-digg"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($dribbble) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $dribbble; ?>" target="_blank" title="Dribbble"><i class="fa fa-dribbble"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($facebook) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $facebook; ?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($flickr) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $flickr; ?>" target="_blank" title="Flickr"><i class="fa fa-flickr"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($github) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $github; ?>" target="_blank" title="Github"><i class="fa fa-github"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($google) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $google; ?>" target="_blank" title="Google"><i class="fa fa-google"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($googleplus) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $googleplus; ?>" target="_blank" title="Google Plus"><i class="fa fa-google-plus"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($googlewallet) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $googlewallet; ?>" target="_blank" title="Google Wallet"><i class="fa fa-google-wallet"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($html5) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $html5; ?>" target="_blank" title="Html5"><i class="fa fa-html5"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($instagram) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $instagram; ?>" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($lastfm) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $lastfm; ?>" target="_blank" title="Lastfm"><i class="fa fa-lastfm"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($linkedin) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $linkedin; ?>" target="_blank" title="Linkedin"><i class="fa fa-linkedin"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($medium) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $medium; ?>" target="_blank" title="Medium"><i class="fa fa-medium"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($paypal) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $paypal; ?>" target="_blank" title="Paypal"><i class="fa fa-paypal"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($pinterest) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $pinterest; ?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($reddit) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $reddit; ?>" target="_blank" title="Reddit"><i class="fa fa-reddit"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($rss) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $rss; ?>" target="_blank" title="Rss"><i class="fa fa-rss"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($skype) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $skype; ?>" target="_blank" title="Skype"><i class="fa fa-skype"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($slack) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $slack; ?>" target="_blank" title="Slack"><i class="fa fa-slack"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($steam) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $steam; ?>" target="_blank" title="Steam"><i class="fa fa-steam"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($tripadvisor) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $tripadvisor; ?>" target="_blank" title="TripAdvisor"><i class="fa fa-tripadvisor"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($tumblr) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $tumblr; ?>" target="_blank" title="Tumblr"><i class="fa fa-tumblr"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($twitch) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $twitch; ?>" target="_blank" title="Twitch"><i class="fa fa-twitch"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($twitter) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $twitter; ?>" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($vimeo) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $vimeo; ?>" target="_blank" title="Vimeo"><i class="fa fa-vimeo"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($whatsapp) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $whatsapp; ?>" target="_blank" title="Whatsapp"><i class="fa fa-whatsapp"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($wordpress) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $wordpress; ?>" target="_blank" title="Wordpress"><i class="fa fa-wordpress"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($yahoo) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $yahoo; ?>" target="_blank" title="Yahoo"><i class="fa fa-yahoo"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($yelp) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $yelp; ?>" target="_blank" title="Yelp"><i class="fa fa-yelp"></i></a>
                    </li>
                    <?php } ?>

                    <?php if($youtube) { ?>
                    <li class="social-widget-icon">
                        <a href="<?php echo $youtube; ?>" target="_blank" title="Youtube"><i class="fa fa-youtube"></i></a>
                    </li>
                    <?php } ?>

                </ul>
                </div>
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
				$instance['amazon'] = strip_tags( $new_instance['amazon'] );
				$instance['apple'] = strip_tags( $new_instance['apple'] );
				$instance['behance'] = strip_tags( $new_instance['behance'] );
				$instance['delicious'] = strip_tags( $new_instance['delicious'] );
				$instance['deviantart'] = strip_tags( $new_instance['deviantart'] );
				$instance['dropbox'] = strip_tags( $new_instance['dropbox'] );
				$instance['digg'] = strip_tags( $new_instance['digg'] );
				$instance['dribbble'] = strip_tags( $new_instance['dribbble'] );
                $instance['facebook'] = strip_tags( $new_instance['facebook'] );
				$instance['flickr'] = strip_tags( $new_instance['flickr'] );
				$instance['github'] = strip_tags( $new_instance['github'] );
				$instance['google'] = strip_tags( $new_instance['google'] );
				$instance['googleplus'] = strip_tags( $new_instance['googleplus'] );
				$instance['googlewallet'] = strip_tags( $new_instance['googlewallet'] );
				$instance['html5'] = strip_tags( $new_instance['html5'] );
				$instance['instagram'] = strip_tags( $new_instance['instagram'] );
				$instance['lastfm'] = strip_tags( $new_instance['lastfm'] );
				$instance['linkedin'] = strip_tags( $new_instance['linkedin'] );
				$instance['medium'] = strip_tags( $new_instance['medium'] );
				$instance['paypal'] = strip_tags( $new_instance['paypal'] );
				$instance['pinterest'] = strip_tags( $new_instance['pinterest'] );
				$instance['reddit'] = strip_tags( $new_instance['reddit'] );
				$instance['rss'] = strip_tags( $new_instance['rss'] );
				$instance['skype'] = strip_tags( $new_instance['skype'] );
				$instance['slack'] = strip_tags( $new_instance['slack'] );
				$instance['steam'] = strip_tags( $new_instance['steam'] );
                $instance['tripadvisor'] = strip_tags( $new_instance['tripadvisor'] );
				$instance['tumblr'] = strip_tags( $new_instance['tumblr'] );
				$instance['twitch'] = strip_tags( $new_instance['twitch'] );
                $instance['twitter'] = strip_tags( $new_instance['twitter'] );
                $instance['vimeo'] = strip_tags( $new_instance['vimeo'] );
				$instance['whatsapp'] = strip_tags( $new_instance['whatsapp'] );
				$instance['wordpress'] = strip_tags( $new_instance['wordpress'] );
				$instance['yahoo'] = strip_tags( $new_instance['yahoo'] );
				$instance['yelp'] = strip_tags( $new_instance['yelp'] );
                $instance['youtube'] = strip_tags( $new_instance['youtube'] );
				
				
               
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
				if ( isset( $instance[ 'amazon' ] ) ) {
			$amazon = $instance[ 'amazon' ];
		}
		else {
			$amazon = null;
		}
				if ( isset( $instance[ 'apple' ] ) ) {
			$apple = $instance[ 'apple' ];
		}
		else {
			$apple = null;
		}
				if ( isset( $instance[ 'behance' ] ) ) {
			$behance = $instance[ 'behance' ];
		}
		else {
			$behance = null;
		}
				if ( isset( $instance[ 'delicious' ] ) ) {
			$delicious = $instance[ 'delicious' ];
		}
		else {
			$delicious = null;
		}	
				if ( isset( $instance[ 'deviantart' ] ) ) {
			$deviantart = $instance[ 'deviantart' ];
		}
		else {
			$deviantart = null;
		}
				if ( isset( $instance[ 'dropbox' ] ) ) {
			$dropbox = $instance[ 'dropbox' ];
		}
		else {
			$dropbox = null;
		}
				if ( isset( $instance[ 'digg' ] ) ) {
			$digg = $instance[ 'digg' ];
		}
		else {
			$digg = null;
		}
				if ( isset( $instance[ 'dribbble' ] ) ) {
			$dribbble = $instance[ 'dribbble' ];
		}
		else {
			$dribbble = null;
		}
				if ( isset( $instance[ 'facebook' ] ) ) {
			$facebook = $instance[ 'facebook' ];
		}
		else {
			$facebook = __( '', 'text_domain' );
		}
				if ( isset( $instance[ 'flickr' ] ) ) {
			$flickr = $instance[ 'flickr' ];
		}
		else {
			$flickr = null;
		}
				if ( isset( $instance[ 'github' ] ) ) {
			$github = $instance[ 'github' ];
		}
		else {
			$github = null;
		}
				if ( isset( $instance[ 'google' ] ) ) {
			$google = $instance[ 'google' ];
		}
		else {
			$google = null;
		}
				if ( isset( $instance[ 'googleplus' ] ) ) {
			$googleplus = $instance[ 'googleplus' ];
		}
		else {
			$googleplus = null;
		}
				if ( isset( $instance[ 'googlewallet' ] ) ) {
			$googlewallet = $instance[ 'googlewallet' ];
		}
		else {
			$googlewallet = null;
		}
				if ( isset( $instance[ 'html5' ] ) ) {
			$html5 = $instance[ 'html5' ];
		}
		else {
			$html5 = null;
		}
				if ( isset( $instance[ 'instagram' ] ) ) {
			$instagram = $instance[ 'instagram' ];
		}
		else {
			$instagram = null;
		}
				if ( isset( $instance[ 'lastfm' ] ) ) {
			$lastfm = $instance[ 'lastfm' ];
		}
		else {
			$lastfm = null;
		}
				if ( isset( $instance[ 'linkedin' ] ) ) {
			$linkedin = $instance[ 'linkedin' ];
		}
		else {
			$linkedin = null;
		}
				if ( isset( $instance[ 'medium' ] ) ) {
			$medium = $instance[ 'medium' ];
		}
		else {
			$medium = null;
		}
				if ( isset( $instance[ 'paypal' ] ) ) {
			$paypal = $instance[ 'paypal' ];
		}
		else {
			$paypal = null;
		}
				if ( isset( $instance[ 'pinterest' ] ) ) {
			$pinterest = $instance[ 'pinterest' ];
		}
		else {
			$pinterest = __( '', 'text_domain' );
		}
				if ( isset( $instance[ 'reddit' ] ) ) {
			$reddit = $instance[ 'reddit' ];
		}
		else {
			$reddit = null;
		}
				if ( isset( $instance[ 'rss' ] ) ) {
			$rss = $instance[ 'rss' ];
		}
		else {
			$rss = null;
		}
				if ( isset( $instance[ 'skype' ] ) ) {
			$skype = $instance[ 'skype' ];
		}
		else {
			$skype = null;
		}
				if ( isset( $instance[ 'slack' ] ) ) {
			$slack = $instance[ 'slack' ];
		}
		else {
			$slack = null;
		}
				if ( isset( $instance[ 'steam' ] ) ) {
			$steam = $instance[ 'steam' ];
		}
		else {
			$steam = null;
		}
                if ( isset( $instance[ 'tripadvisor' ] ) ) {
            $tripadvisor = $instance[ 'tripadvisor' ];
        }
        else {
            $tripadvisor = null;
        }
				if ( isset( $instance[ 'tumblr' ] ) ) {
			$tumblr = $instance[ 'tumblr' ];
		}
		else {
			$tumblr = null;
		}
				if ( isset( $instance[ 'twitch' ] ) ) {
			$twitch = $instance[ 'twitch' ];
		}
		else {
			$twitch = null;
		}
				if ( isset( $instance[ 'twitter' ] ) ) {
			$twitter = $instance[ 'twitter' ];
		}
		else {
			$twitter = null;
		}
				if ( isset( $instance[ 'vimeo' ] ) ) {
			$vimeo = $instance[ 'vimeo' ];
		}
		else {
			$vimeo = null;
		}
				if ( isset( $instance[ 'whatsapp' ] ) ) {
			$whatsapp = $instance[ 'whatsapp' ];
		}
		else {
			$whatsapp = null;
		}
				if ( isset( $instance[ 'wordpress' ] ) ) {
			$wordpress = $instance[ 'wordpress' ];
		}
		else {
			$wordpress = null;
		}
				if ( isset( $instance[ 'yahoo' ] ) ) {
			$yahoo = $instance[ 'yahoo' ];
		}
		else {
			$yahoo = null;
		}
				if ( isset( $instance[ 'yelp' ] ) ) {
			$yelp = $instance[ 'yelp' ];
		}
		else {
			$yelp = null;
		}
				if ( isset( $instance[ 'youtube' ] ) ) {
			$youtube = $instance[ 'youtube' ];
		}
		else {
			$youtube = null;
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
                        <option value="social-icons-light" <?php selected( $color_scheme, 'social-icons-light'); ?>><?php _e( 'For Light Background Colors' ); ?></option>
                        <option value="social-icons-dark" <?php selected( $color_scheme, 'social-icons-dark'); ?>><?php _e( 'For Dark Background Colors' ); ?></option>
                    </select>
                </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'amazon' ); ?>"><?php _e( 'Amazon:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'amazon' ); ?>" name="<?php echo $this->get_field_name( 'amazon' ); ?>" type="text" value="<?php echo esc_attr( $amazon ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'apple' ); ?>"><?php _e( 'Apple:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'apple' ); ?>" name="<?php echo $this->get_field_name( 'apple' ); ?>" type="text" value="<?php echo esc_attr( $apple ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'behance' ); ?>"><?php _e( 'Behance:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'behance' ); ?>" name="<?php echo $this->get_field_name( 'behance' ); ?>" type="text" value="<?php echo esc_attr( $behance ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'delicious' ); ?>"><?php _e( 'Delicious:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'delicious' ); ?>" name="<?php echo $this->get_field_name( 'delicious' ); ?>" type="text" value="<?php echo esc_attr( $delicious ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'deviantart' ); ?>"><?php _e( 'Deviantart:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'deviantart' ); ?>" name="<?php echo $this->get_field_name( 'deviantart' ); ?>" type="text" value="<?php echo esc_attr( $deviantart ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'dropbox' ); ?>"><?php _e( 'Dropbox:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'dropbox' ); ?>" name="<?php echo $this->get_field_name( 'dropbox' ); ?>" type="text" value="<?php echo esc_attr( $dropbox ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'digg' ); ?>"><?php _e( 'digg:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'digg' ); ?>" name="<?php echo $this->get_field_name( 'digg' ); ?>" type="text" value="<?php echo esc_attr( $digg ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'dribbble' ); ?>"><?php _e( 'Dribbble:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'dribbble' ); ?>" name="<?php echo $this->get_field_name( 'dribbble' ); ?>" type="text" value="<?php echo esc_attr( $dribbble ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" />
                 </p>
				  <p>
                    <label for="<?php echo $this->get_field_id( 'flickr' ); ?>"><?php _e( 'Flickr:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'flickr' ); ?>" name="<?php echo $this->get_field_name( 'flickr' ); ?>" type="text" value="<?php echo esc_attr( $flickr ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'github' ); ?>"><?php _e( 'Github:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'github' ); ?>" name="<?php echo $this->get_field_name( 'github' ); ?>" type="text" value="<?php echo esc_attr( $github ); ?>" />
                 </p>   
                 <p>
                    <label for="<?php echo $this->get_field_id( 'google' ); ?>"><?php _e( 'Google:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" type="text" value="<?php echo esc_attr( $google ); ?>" />
                 </p>  
				  <p>
                    <label for="<?php echo $this->get_field_id( 'googleplus' ); ?>"><?php _e( 'Googleplus:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'googleplus' ); ?>" name="<?php echo $this->get_field_name( 'googleplus' ); ?>" type="text" value="<?php echo esc_attr( $googleplus ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'googlewallet' ); ?>"><?php _e( 'Googlewallet:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'googlewallet' ); ?>" name="<?php echo $this->get_field_name( 'googlewallet' ); ?>" type="text" value="<?php echo esc_attr( $googlewallet ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'html5' ); ?>"><?php _e( 'Html5:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'html5' ); ?>" name="<?php echo $this->get_field_name( 'html5' ); ?>" type="text" value="<?php echo esc_attr( $html5 ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>" />
                 </p> 
				  <p>
                    <label for="<?php echo $this->get_field_id( 'lastfm' ); ?>"><?php _e( 'Lastfm:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'lastfm' ); ?>" name="<?php echo $this->get_field_name( 'lastfm' ); ?>" type="text" value="<?php echo esc_attr( $lastfm ); ?>" />
                 </p> 
				  <p>
                    <label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e( 'Linkedin:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'medium' ); ?>"><?php _e( 'Medium:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'medium' ); ?>" name="<?php echo $this->get_field_name( 'medium' ); ?>" type="text" value="<?php echo esc_attr( $medium ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'paypal' ); ?>"><?php _e( 'Paypal:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'paypal' ); ?>" name="<?php echo $this->get_field_name( 'paypal' ); ?>" type="text" value="<?php echo esc_attr( $paypal ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'pinterest' ); ?>"><?php _e( 'Pinterest:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" type="text" value="<?php echo esc_attr( $pinterest ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'reddit' ); ?>"><?php _e( 'Reddit:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'reddit' ); ?>" name="<?php echo $this->get_field_name( 'reddit' ); ?>" type="text" value="<?php echo esc_attr( $reddit ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e( 'Rss:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo esc_attr( $rss ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'skype' ); ?>"><?php _e( 'Skype:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'skype' ); ?>" name="<?php echo $this->get_field_name( 'skype' ); ?>" type="text" value="<?php echo esc_attr( $skype ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'slack' ); ?>"><?php _e( 'Slack:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'slack' ); ?>" name="<?php echo $this->get_field_name( 'slack' ); ?>" type="text" value="<?php echo esc_attr( $slack ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'steam' ); ?>"><?php _e( 'Steam:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'slack' ); ?>" name="<?php echo $this->get_field_name( 'steam' ); ?>" type="text" value="<?php echo esc_attr( $steam ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'tripadvisor' ); ?>"><?php _e( 'TripAdvisor:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'tripadvisor' ); ?>" name="<?php echo $this->get_field_name( 'tripadvisor' ); ?>" type="text" value="<?php echo esc_attr( $tripadvisor ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'tumblr' ); ?>"><?php _e( 'Tumblr:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'tumblr' ); ?>" name="<?php echo $this->get_field_name( 'tumblr' ); ?>" type="text" value="<?php echo esc_attr( $tumblr ); ?>" />
                 </p>  
                 <p>
                    <label for="<?php echo $this->get_field_id( 'twitch' ); ?>"><?php _e( 'Twitch:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'twitch' ); ?>" name="<?php echo $this->get_field_name( 'twitch' ); ?>" type="text" value="<?php echo esc_attr( $twitch ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'vimeo' ); ?>"><?php _e( 'Vimeo:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'vimeo' ); ?>" name="<?php echo $this->get_field_name( 'vimeo' ); ?>" type="text" value="<?php echo esc_attr( $vimeo ); ?>" />
                 </p>
                 <p>
                    <label for="<?php echo $this->get_field_id( 'whatsapp' ); ?>"><?php _e( 'Whatsapp:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'whatsapp' ); ?>" name="<?php echo $this->get_field_name( 'whatsapp' ); ?>" type="text" value="<?php echo esc_attr( $whatsapp ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'wordpress' ); ?>"><?php _e( 'Wordpress:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'wordpress' ); ?>" name="<?php echo $this->get_field_name( 'wordpress' ); ?>" type="text" value="<?php echo esc_attr( $wordpress ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'yahoo' ); ?>"><?php _e( 'Yahoo:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'yahoo' ); ?>" name="<?php echo $this->get_field_name( 'yahoo' ); ?>" type="text" value="<?php echo esc_attr( $yahoo ); ?>" />
                 </p> 
				  <p>
                    <label for="<?php echo $this->get_field_id( 'yelp' ); ?>"><?php _e( 'Yelp:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'yelp' ); ?>" name="<?php echo $this->get_field_name( 'yelp' ); ?>" type="text" value="<?php echo esc_attr( $yelp ); ?>" />
                 </p> 
                 <p>
                    <label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube:' ); ?></label> 
                    <input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>" />
                 </p>
                 
                
<?php        }
        
} // class Vslmd_Social_Icons ends



// register Vslmd_Social_Icons widget
function register_vslmd_social_icons() {
    register_widget( 'Vslmd_Social_Icons' );
}
add_action( 'widgets_init', 'register_vslmd_social_icons' );