<?php 

/*-----------------------------------------------------------------------------------*/
/*	One Click Demo Import
/*-----------------------------------------------------------------------------------*/

//Change the location, title and other parameters of the plugin page
function ocdi_plugin_page_setup( $default_settings ) {
    $default_settings['parent_slug'] = 'themes.php';
    $default_settings['menu_title']  = esc_html__( 'Visualmodo Demo' , 'vslmd' );
    $default_settings['menu_slug']   = 'visualmodo-demo-import';

    return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'ocdi_plugin_page_setup' );

//Disable the ProteusThemes branding notice with a WP filter
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

$product = wp_get_theme()->get( 'Name' );

if( $product == 'Edge' ) {

function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Edge Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/edge/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/edge/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/edge/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/edge/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                /*array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/edge/visual-elements.json',
                    'option_name' => 've_options',
                ),*/
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/edge/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Fitness' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Fitness Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/fitness/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/fitness/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/fitness/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/fitness/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                /*array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/fitness/visual-elements.json',
                    'option_name' => 've_options',
                ),*/
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/fitness/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Gym' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Gym Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/gym/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/gym/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/gym/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/gym/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/gym/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/gym/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Zenith' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Zenith Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/zenith/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/zenith/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/zenith/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/zenith/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/zenith/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/zenith/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer_menu->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Sport' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Sport Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/sport/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/sport/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/sport/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/sport/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/sport/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/sport/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $one_page = get_term_by( 'name', 'One Page', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'one_page' => $one_page->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Food' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Food Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/food/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/food/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/food/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/food/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/food/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/food/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'top_menu' => $top_menu->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Peak' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Peak Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/peak/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/peak/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/peak/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/peak/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/peak/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/peak/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'side_menu' => $side_menu->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Spark' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Spark Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/spark/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/spark/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/spark/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/spark/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/spark/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/spark/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'side' => $side_menu->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Stream' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Stream Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/stream/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/stream/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/stream/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/stream/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/stream/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/stream/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Side', 'nav_menu' );
    $one_page = get_term_by( 'name', 'One Page', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'side_menu' => $side_menu->term_id,
            'one_page' => $one_page->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Ink' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Ink Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/ink/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/ink/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/ink/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/ink/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/ink/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/ink/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side_menu = get_term_by( 'name', 'Side Menu', 'nav_menu' );
    $categories = get_term_by( 'name', 'Categories', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'side_menu' => $side_menu->term_id,
            'categories' => $categories->term_id 
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Beyond' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Beyond Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/beyond/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/beyond/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/beyond/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/beyond/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/beyond/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/beyond/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Rare' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Rare Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/rare/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/rare/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/rare/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/rare/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/rare/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/rare/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $side_navigation = get_term_by( 'Side Navigation', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id,
            'side_navigation' => $side_navigation->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Wedding' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Wedding Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/wedding/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/wedding/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/wedding/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/wedding/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                /*array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/wedding/visual-elements.json',
                    'option_name' => 've_options',
                ),*/
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/wedding/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Architect' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Architect Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/architect/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/architect/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/architect/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/architect/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/architect/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/architect/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Medical' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Medical Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/medical/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/medical/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/medical/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/medical/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/medical/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/medical/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Marvel' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Marvel Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/marvel/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/marvel/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/marvel/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/marvel/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/marvel/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/marvel/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $copyright_menu = get_term_by( 'name', 'Copyright Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'copyright_menu' => $copyright_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Seller' ) {

	function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Seller Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/seller/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/seller/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/seller/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/seller/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/seller/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/seller/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Winehouse' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Winehouse Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/winehouse/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/winehouse/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/winehouse/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/winehouse/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/winehouse/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/winehouse/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Nectar' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Nectar Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/nectar/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/nectar/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/nectar/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/nectar/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/nectar/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/nectar/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main / mobile', 'nav_menu' );
    $left = get_term_by( 'name', 'Left', 'nav_menu' );
    $right = get_term_by( 'name', 'Right', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'left' => $left->term_id,
            'right' => $right->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Mechanic' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Mechanic Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/mechanic/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/mechanic/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/mechanic/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/mechanic/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/mechanic/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/mechanic/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Construction' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Construction Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/construction/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/construction/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/construction/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/construction/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/construction/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/construction/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Traveler' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Traveler Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/traveler/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/traveler/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/traveler/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/traveler/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/traveler/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/traveler/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $categories = get_term_by( 'name', 'Categories', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'categories' => $categories->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Salon' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Salon Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/salon/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/salon/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/salon/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/salon/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/salon/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/salon/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Music' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Music Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/music/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/music/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/music/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/music/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/music/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/music/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Resume' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Resume Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/resume/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/resume/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/resume/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/resume/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/resume/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/resume/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $portfolio_menu = get_term_by( 'name', 'Portfolio Item Page Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'portfolio' => $portfolio_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    //$blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    //update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Hotel' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Hotel Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/hotel/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/hotel/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/hotel/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/hotel/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/hotel/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/hotel/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Cryptocurrency' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Cryptocurrency Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/cryptocurrency/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/cryptocurrency/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/cryptocurrency/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/cryptocurrency/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/cryptocurrency/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/cryptocurrency/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Dark' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Dark Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/dark/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/dark/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/dark/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/dark/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/dark/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/dark/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Nonprofit' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Nonprofit Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/nonprofit/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/nonprofit/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/nonprofit/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/nonprofit/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/nonprofit/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/nonprofit/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Employment' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Employment Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/employment/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/employment/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/employment/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/employment/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/employment/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/employment/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Forum' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Forum Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/forum/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/forum/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/forum/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/forum/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/forum/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/forum/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Petshop' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Petshop Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/petshop/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/petshop/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/petshop/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/petshop/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/petshop/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/petshop/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Photography' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Photography Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/photography/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/photography/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/photography/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/photography/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/photography/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/photography/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $main = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
            'footer' => $footer->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Education' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Education Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/education/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/education/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/education/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/education/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/education/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/education/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side = get_term_by( 'name', 'Side Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
            'side' => $side->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Minimalist' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Minimalist Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/minimalist/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/minimalist/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/minimalist/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/minimalist/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/minimalist/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/minimalist/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $side = get_term_by( 'name', 'Side Navigation', 'nav_menu' );
    $footer = get_term_by( 'name', 'Footer', 'nav_menu' );
    $fotter_right = get_term_by( 'name', 'Footer Right', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
            'side' => $side->term_id,
            'footer' => $footer->term_id,
            'fotter_right' => $fotter_right->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

} elseif( $product == 'Cafe' ) {

    function vslmd_import_files() {
    return array(
        array(
            'import_file_name'           => 'Cafe Demo',
            //'categories'                 => array( 'Business', 'portfolio' ),
            'import_file_url'            => 'https://download.visualmodo.com/archive/demo-import/cafe/demo-content.xml',
            'import_widget_file_url'     => 'https://download.visualmodo.com/archive/demo-import/cafe/widgets.wie',
            'import_customizer_file_url' => 'https://download.visualmodo.com/archive/demo-import/cafe/customizer.dat',
            'import_redux'               => array(
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/cafe/theme-options.json',
                    'option_name' => 'vslmd_options',
                ),
                array(
                    'file_url'    => 'https://download.visualmodo.com/archive/demo-import/cafe/visual-elements.json',
                    'option_name' => 've_options',
                ),
            ),
            'import_preview_image_url'   => 'https://download.visualmodo.com/archive/demo-import/import-demo-cover.png',
            'import_notice'              => __( 'After you import this demo, you will have to setup the slider separately.', 'vslmd' ),
            'preview_url'                => 'https://theme.visualmodo.com/minimalist/',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'vslmd_import_files' );


// Assign Front page, Posts page and menu locations after the importer

function vslmd_after_import_setup() {

    // Assign menus to their locations.
    $main = get_term_by( 'name', 'Main', 'nav_menu' );
    $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main->term_id,
            'top_menu' => $top_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );

}
add_action( 'pt-ocdi/after_import', 'vslmd_after_import_setup' );

}

