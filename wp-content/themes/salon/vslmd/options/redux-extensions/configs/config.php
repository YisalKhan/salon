<?php

// INCLUDE THIS BEFORE you load your ReduxFramework object config file.


// You may replace $redux_opt_name with a string if you wish. If you do so, change loader.php
// as well as all the instances below.
$redux_opt_name = "vslmd_options";

if ( !function_exists( "redux_add_metaboxes" ) ):
    function redux_add_metaboxes($metaboxes) {
        
        $options = get_option('vslmd_options');
        
        //Query Sliders
        
        if ( class_exists( 'RevSlider' ) ) {
            
            $slider = new RevSlider();
            $arrSliders = $slider->getArrSliders();
            $revsliders = array();
            
            if ( $arrSliders ) {
                foreach ( $arrSliders as $slider ) {
                    /** @var $slider RevSlider */
                    $revsliders[ $slider->getAlias() ] = $slider->getTitle();
                }
            } else { $revsliders[] = __( 'No Slider Found', 'vslmd' ); }
            
        } else { $revsliders[] = __( 'No Slider Found', 'vslmd' ); }
        
        //Query Sliders End
        
        $defaultSections[] = array(
            'title' => __('Layout', 'vslmd'),
            'desc' => __('Control and configure the layout.', 'vslmd'),
            'icon' => 'el-icon-screen',
            'fields' => array(  
                array(
                    'id'       => 'layout_structure',
                    'type'     => 'button_set',
                    'title'    => __('Layout Manager', 'vslmd'),
                    'desc'     => __('Organize how you want the layout to appear.', 'vslmd'),
                    'options' => array(
                        '1' => 'No',
                        '2' => 'Only Footer', 
                        '3' => 'Only Header',
                        '4' => 'Header and Footer',
                    ), 
                    'default' => '4',
                    
                ),
                array(
                    'id'       => 'layout_header_title',
                    'type'     => 'button_set',
                    'title'    => __('Layout Header Title', 'vslmd'),
                    'subtitle'     => __('Organize how you want the layout to appear.', 'vslmd'),
                    'required' => array('layout_structure','>=','3'),
                    'options' => array(
                        '1' => 'No', 
                        '2' => 'Background Color', 
                        '3' => 'Background Image',
                        '4' => 'Slider Revolution',
                        '5' => 'Simple Slider',
                    ), 
                    'default' => '2'
                ),
                array(
                    'id'        => 'header_title_color_overlay',
                    'type'      => 'color_rgba',
                    'title'     => 'Background Color',
                    'required' => array(
                        array('layout_header_title', '<=', 3),
                        array('layout_header_title', '!=', 1),
                    ),
                    'desc'      => 'Set Background Color and Opacity.',
                    'output'    => array(
                        'background-color' => '.header-presentation .hp-background-color'
                        )
                    ),
                    array(         
                        'id'       => 'header_title_background',
                        'type'     => 'background',
                        'background-color' => false,
                        'required' => array('layout_header_title','equals','3'),
                        'title'    => __('Background Image', 'vslmd'),
                        'desc'     => __('Upload your image should be between 1920px x 1080px (or more) for best results.', 'vslmd'),
                        'output'    => array('.header-presentation'),
                    ),  
                    array(
                        'id'       => 'custom_header_title_height',
                        'type'     => 'button_set',
                        'title'    => __('Header Title Height', 'vslmd'),
                        'desc'     => __('Choose the height you want.', 'vslmd'),
                        'required' => array(
                            array('layout_header_title', '<=', 3),
                            array('layout_header_title', '>', 1),
                        ),
                        'options' => array(
                            'small' => 'Small', 
                            'medium' => 'Medium', 
                            'full' => 'Full height',
                            'custom-header-title-height' => 'Custom height'
                        ), 
                        'default' => 'medium'
                    ),
                    array(
                        'id'       => 'custom_header_title_height_dimension',
                        'type'     => 'dimensions',
                        'units'    => array('em','px','%'),
                        'width'    => false,
                        'title'         => __('Custom Header Title Height', 'vslmd'),
                        'desc'          => __('Enter a number. Min: 1, max: 100, step: 1, default value: 250', 'vslmd'),
                        'required' => array('custom_header_title_height','equals','custom-header-title-height'),
                        'output'    => array('section.header-presentation.custom-header-title-height'),
                        'default'  => array(
                            'Height'  => '250'
                        ),
                    ),
                    array(
                        'id'               => 'title_editor',
                        'type'             => 'text',
                        'title'            => __('Title', 'vslmd'), 
                        'desc'         => __('You can insert a custom text caption.', 'vslmd'),
                        'required' => array(
                            array('layout_header_title', '<=', 3),
                            array('layout_header_title', '>', 1),
                        ),
                    ),
                    array(
                        'id'               => 'caption_editor',
                        'type'             => 'text',
                        'title'            => __('Caption', 'vslmd'), 
                        'desc'         => __('You can insert a custom title instead of default title.', 'vslmd'),
                        'required' => array(
                            array('layout_header_title', '<=', 3),
                            array('layout_header_title', '>', 1),
                        ),
                    ),
                    array(
                        'id'        => 'header_title_color',
                        'type'      => 'color_rgba',
                        'title'     => 'Title Color',
                        'output'    => array(
                            'color' => '.header-presentation .hp-background-color .container .hp-content h1'
                        ),
                        'required' => array(
                            array('layout_header_title', '<=', 3),
                            array('layout_header_title', '>', 1),
                        ),
                        'desc'      => 'Set Color and Opacity.',
                    ),
                    array(
                        'id'        => 'header_caption_color',
                        'type'      => 'color_rgba',
                        'title'     => 'Caption Color',
                        'output'    => array(
                            'color' => '.header-presentation .hp-background-color .container .hp-content p'
                        ),
                        'required' => array(
                            array('layout_header_title', '<=', 3),
                            array('layout_header_title', '>', 1),
                        ),
                        'desc'      => 'Set Color and Opacity.',
                    ),
                    array(
                        'id' => 'slider_rev_header', 
                        'title' => __('Slider Revolution', 'vslmd'),
                        'desc' => __('Choose Slide Template', 'vslmd'),
                        'required' => array('layout_header_title','equals','4'),
                        'type' => 'select',
                        'options'   => $revsliders,
                    ),		
                    array(
                        'id'          => 'simple_slider',
                        'type'        => 'slides',
                        'title'       => __('Slides Options', 'vslmd'),
                        'show' => array(
                            'title' => true,
                            'description' => true,
                            'url' => false,
                        ),
                        'subtitle'    => __('Unlimited slides with drag and drop sortings.', 'vslmd'),
                        'desc'        => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'vslmd'),
                        'required' => array('layout_header_title','equals','5'),
                        'placeholder' => array(
                            'title'           => __('This is a title', 'vslmd'),
                            'description'     => __('Description Here', 'vslmd'),
                        ),
                    ),	                                                  
                ),
            );
            
            $defaultSections[] = array(
                'title' => __('Settings', 'vslmd'),
                'desc' => __('Control and configure the general settings.', 'vslmd'),
                'icon' => 'el-icon-cog',
                'fields' => array(  
                    array(
                        'id'       => 'menu_overlay_switch',
                        'type'     => 'button_set',
                        'required' => array('layout_structure','>=','3'),
                        'title'    => __('Overlay Navigation', 'vslmd'),
                        'desc' => __('The navigation will overlay the content on top.', 'vslmd'),
                        'options' => array(
                            'no-overlay' => 'No', 
                            'default-colors-overlay colors-overlay-enabled' => 'Default Colors', 
                            'light-colors-overlay colors-overlay-enabled' => 'Light Colors',
                            'dark-colors-overlay colors-overlay-enabled' => 'Dark Colors'
                        ), 
                        'default' => 'no-overlay'
                    ),
                    /*
                    array(
                        'id'        => 'menu_overlay_switch_background_color',
                        'type'      => 'color_rgba',
                        'title'     => __('Header Background Color', 'vslmd'),
                        'subtitle'  => __('Set color and alpha channel', 'vslmd'),
                        'required'  => array('menu_overlay_switch','!=','no-overlay'),
                        'output'    => array(
                            'background-color' => '.desktop-mode.colors-overlay-enabled .header-top,.desktop-mode.colors-overlay-enabled .header-bottom'
                            )
                        ),
                        */
                        array(
                            'id' => 'change_menu',
                            'title' => __( 'Change Menu', 'vslmd' ),
                            'desc'=> __('Select the Menu that you want to show.', 'vslmd'),
                            'required' => array('layout_structure','>=','3'),
                            'type' => 'select',
                            'data' => 'menu_location',
                        ),
                        )
                    );
                    
                    $metaboxes = array();
                    
                    $cpt1 = $cpt2 = $cpt3 = $cpt4 = $cpt5 = '';
                    $cpt_counter = 1;
                    $cpt_list = array();
                    
                    while( $cpt_counter <= 5 ){
                        if(isset($options['custom-post-type-slug-' . $cpt_counter]) && $options['custom-post-type-slug-' . $cpt_counter] != ''){
                            $cpt_list[] = $options['custom-post-type-slug-' . $cpt_counter];
                        }
                        $cpt_counter++;
                    }
                    
                    array_push($cpt_list, "post", "page", "product", "portfolio", "knowledgebase", "team", "forum" ,"reply", "topic", "events");
                    
                    
                    $metaboxes[] = array(
                        'id' => 'default-options',
                        'title' => __('Options', 'vslmd'),
                        'post_types' => $cpt_list,
                        //'page_template' => array('page-test.php'),
                        //'post_format' => array('image'),
                        'position' => 'normal', // normal, advanced, side
                        'priority' => 'default', // high, core, default, low
                        //'sidebar' => false, // enable/disable the sidebar in the normal/advanced positions
                        'sections' => $defaultSections
                    );
                    
                    /*-----------------------------------------------------------------------------------*/
                    /*	Team
                    /*-----------------------------------------------------------------------------------*/	
                    
                    $SectionsTeam = array();
                    $SectionsTeam[] = array(
                        'title' => __('Occupation', 'vslmd'),
                        'fields' => array( 
                            array(
                                'id'=>'meta-box-team-career',
                                'type' => 'text', 
                                'title' => __('Occupation', 'vslmd'),
                                'desc'=> __('Please input the Occupation', 'vslmd'),
                            ),           
                        ),
                    );
                    $SectionsTeam[] = array(
                        'title' => __('Contact Settings', 'vslmd'),
                        'fields' => array( 
                            array(
                                'id'=>'meta-box-team-email',
                                'type' => 'text', 
                                'title' => __('Email', 'vslmd'),
                                'desc'=> __('Please input the Email', 'vslmd'),
                            ), 
                            array(
                                'id'=>'meta-box-team-facebook',
                                'type' => 'text', 
                                'title' => __('Facebook Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ), 
                            array(
                                'id'=>'meta-box-team-github',
                                'type' => 'text', 
                                'title' => __('Github Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ), 
                            array(
                                'id'=>'meta-box-team-google-plus',
                                'type' => 'text', 
                                'title' => __('Google Plus Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),
                            array(
                                'id'=>'meta-box-team-instagram',
                                'type' => 'text', 
                                'title' => __('Instagram Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),
                            array(
                                'id'=>'meta-box-team-linkedin',
                                'type' => 'text', 
                                'title' => __('Linked In Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),
                            array(
                                'id'=>'meta-box-team-twitter',
                                'type' => 'text', 
                                'title' => __('Twitter Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),
                            array(
                                'id'=>'meta-box-team-vimeo',
                                'type' => 'text', 
                                'title' => __('Vimeo Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),
                            array(
                                'id'=>'meta-box-team-youtube',
                                'type' => 'text', 
                                'title' => __('Youtube Profile URL', 'vslmd'),
                                'desc'=> __('Please input the URL', 'vslmd'),
                            ),            
                        ),
                    );
                    
                    $metaboxes[] = array(
                        'id' => 'team-layout-post-type',
                        'title' => __('Team Options', 'vslmd'),
                        'post_types' => array('team'),
                        //'page_template' => array('page-test.php'),
                        //'post_format' => array('link'),
                        'position' => 'side', // normal, advanced, side
                        'priority' => 'low', // high, core, default, low
                        //'sidebar' => true, // enable/disable the sidebar in the normal/advanced positions
                        'sections' => $SectionsTeam
                    );
                    
                    /*-----------------------------------------------------------------------------------*/
                    /*	Knowledgebase
                    /*-----------------------------------------------------------------------------------*/	
                    
                    $SectionsKb = array();
                    $SectionsKb[] = array(
                        'title' => __('Knowledgebase', 'vslmd'),
                        'fields' => array( 
                            array(
                                'id'=>'meta-box-header-custom-icon',
                                'type' => 'text', 
                                'title' => __('Custom Icon', 'vslmd'),
                                'desc'=> __('You can insert a custom icon code "icon-book".', 'vslmd'),
                            ), 
                            array(
                                'id'=>'meta-box-kb-description',
                                'type' => 'editor', 
                                'title' => __('Knowledgebase Description', 'vslmd'),
                                'desc'=> __('Write a description for your Knowledgebase.', 'vslmd'),
                                'args'   => array(
                                    'teeny'            => true,
                                    'textarea_rows'    => 10,
                                    'media_buttons'    => false
                                    )
                                ), 
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'kb-post-type',
                            'title' => __('Knowledgebase Options', 'vslmd'),
                            'post_types' => array('knowledgebase'),
                            //'page_template' => array('page-test.php'),
                            //'post_format' => array('link'),
                            'position' => 'normal', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            //'sidebar' => true, // enable/disable the sidebar in the normal/advanced positions
                            'sections' => $SectionsKb
                        );
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Aside Setting
                        /*-----------------------------------------------------------------------------------*/
                        
                        $asideSectionsPost = array();
                        $asideSectionsPost[] = array(
                            //'title' => __('Aside Settings', 'vslmd'),
                            'fields' => array( 
                                array(
                                    'id'=>'meta-box-post-aside-text',
                                    'type' => 'textarea', 
                                    //'title' => __('Aside Content', 'vslmd'),
                                    'desc'=> __('Please Write a content.', 'vslmd'),
                                ), 
                                
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'aside-layout-post',
                            'title' => __('Aside Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('aside'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $asideSectionsPost
                        );
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Quote Setting
                        /*-----------------------------------------------------------------------------------*/
                        
                        $quoteSectionsPost = array();
                        $quoteSectionsPost[] = array(
                            //'title' => __('Quote Settings', 'vslmd'),
                            'fields' => array( 
                                array(
                                    'id'=>'meta-box-post-quote-text',
                                    'type' => 'textarea', 
                                    //'title' => __('Quote Content', 'vslmd'),
                                    'desc'=> __('Please Write a content for your quote.', 'vslmd'),
                                ), 
                                
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'quote-layout-post',
                            'title' => __('Quote Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('quote'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $quoteSectionsPost
                        );
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Link Setting
                        /*-----------------------------------------------------------------------------------*/	
                        
                        $linkSectionsPost = array();
                        $linkSectionsPost[] = array(
                            //'title' => __('Link Settings', 'vslmd'),
                            'fields' => array( 
                                array(
                                    'id'=>'meta-box-link-url',
                                    'type' => 'text', 
                                    //'title' => __('Link URL', 'vslmd'),
                                    'desc'=> __('Please input the URL for your link.', 'vslmd'),
                                ),            
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'link-layout-post',
                            'title' => __('Link Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('link'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $linkSectionsPost
                        );
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Video Setting
                        /*-----------------------------------------------------------------------------------*/	
                        
                        $videoSectionsPost = array();
                        $videoSectionsPost[] = array(
                            //'title' => __('Video Settings', 'vslmd'),
                            'icon' => 'el-icon-chevron-up',
                            'fields' => array( 
                                array(
                                    'id'       => 'meta-box-video-post-format',
                                    'type'     => 'select',
                                    //'title'    => __('Source Video', 'vslmd'), 
                                    'options'  => array(
                                        '1' => 'Youtube or Vimeo',
                                        '2' => 'Self Hosted Video'
                                    ),
                                    'default'  => '1',
                                ),
                                array(
                                    'id'=>'meta-box-video-embedded-code',
                                    'type' => 'text', 
                                    'title' => __('Embedded Code', 'vslmd'),
                                    'desc'=> __('Enter a Youtube or Vimeo URL here.', 'vslmd'),
                                    'required' => array('meta-box-video-post-format','equals','1'),
                                ), 
                                array(
                                    'id'=>'meta-box-video-webm-url',
                                    'type' => 'text', 
                                    'title' => __('WEBM File URL', 'vslmd'),
                                    'desc'=> __('You must include both formats.', 'vslmd'),
                                    'required' => array('meta-box-video-post-format','equals','2'),
                                ), 
                                array(
                                    'id'=>'meta-box-video-mp4-url',
                                    'type' => 'text', 
                                    'title' => __('MP4 File URL', 'vslmd'),
                                    'desc'=> __('You must include both formats.', 'vslmd'),
                                    'required' => array('meta-box-video-post-format','equals','2'),
                                ), 
                                array(
                                    'id'=>'meta-box-video-ogv-url',
                                    'type' => 'text', 
                                    'title' => __('OGV File URL', 'vslmd'),
                                    'desc'=> __('You must include both formats.', 'vslmd'),
                                    'required' => array('meta-box-video-post-format','equals','2'),
                                ),                             
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'video-layout-post',
                            'title' => __('Video Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('video'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $videoSectionsPost
                        );
                        
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Audio Setting
                        /*-----------------------------------------------------------------------------------*/	
                        
                        $audioSectionsPost = array();
                        $audioSectionsPost[] = array(
                            //'title' => __('Audio Settings', 'vslmd'),
                            'fields' => array( 
                                array(
                                    'id'=>'meta-box-audio-url',
                                    'type' => 'text', 
                                    //'title' => __('MP3 File URL', 'vslmd'),
                                    'desc'=> __('Please Paste MP3 URL.', 'vslmd'),
                                ),            
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'audio-layout-post',
                            'title' => __('Audio Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('audio'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $audioSectionsPost
                        );
                        
                        
                        /*-----------------------------------------------------------------------------------*/
                        /*	Gallery Setting
                        /*-----------------------------------------------------------------------------------*/	
                        
                        $gallerySectionsPost = array();
                        $gallerySectionsPost[] = array(
                            //'title' => __('Gallery Settings', 'vslmd'),
                            'fields' => array( 
                                array(
                                    'id'          => 'meta-box-gallery',
                                    'type'        => 'slides',
                                    //'title'       => __('Gallery Slides', 'vslmd'),
                                    'desc'        => __('Please put the slides.', 'vslmd'),
                                    'show' => array(
                                        'title' => true,
                                        'description' => false,
                                        'url' => false,
                                    ),
                                    'placeholder' => array(
                                        'title'           => __('Title', 'vslmd'),
                                    ),
                                ),          
                            ),
                        );
                        
                        $metaboxes[] = array(
                            'id' => 'gallery-layout-post',
                            'title' => __('Gallery Format Options', 'vslmd'),
                            'post_types' => array('post'),
                            'post_format' => array('gallery'),
                            'position' => 'side', // normal, advanced, side
                            'priority' => 'low', // high, core, default, low
                            'sections' => $gallerySectionsPost
                        );	
                        
                        
                        
                        
                        return $metaboxes;
                    }
                    add_action('redux/metaboxes/'.$redux_opt_name.'/boxes', 'redux_add_metaboxes');
                endif;
                
                
                
                
                
                // The loader will load all of the extensions automatically based on your $redux_opt_name
                require_once(dirname(__FILE__).'../../loader.php');