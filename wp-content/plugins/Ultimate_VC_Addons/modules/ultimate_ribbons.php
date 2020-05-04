<?php
/*
* Add-on Name: Ultimate Ribbon
* Add-on URI: http://dev.brainstormforce.com
*/
if(!class_exists("Ultimate_Ribbons")){
	class Ultimate_Ribbons{
		//static $add_plugin_script;
		function __construct(){
			if ( Ultimate_VC_Addons::$uavc_editor_enable ) {
				add_action("init",array($this,"ultimate_ribbons_module_init"));
			}
			add_shortcode("ultimate_ribbon",array($this,"ultimate_ribbons_module_shortcode"));
			add_action("wp_enqueue_scripts", array($this, "register_ribbons_module_assets"),1);
		}//end-of-constructor

		function register_ribbons_module_assets(){

			Ultimate_VC_Addons::ultimate_register_style( 'ultimate-ribbons-style', 'ribbon_module' );
		}//end-of-register-style-script-function

		//Init function for Ribbon module
		function ultimate_ribbons_module_init(){
			if(function_exists("vc_map")){
				vc_map(
					array(
					   "name" => __("Ribbon","ultimate_vc"),
					   "base" => "ultimate_ribbon",
					   "class" => "vc_ultimate_ribbon",
					   "icon" => "vc_ultimate_ribbon",
					   "category" => "Ultimate VC Addons",
					   "description" => __("Design awesome Ribbon styles","ultimate_vc"),
					   "params" => array(
					   		array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Ribbon Message", "ultimate_vc"),
								"admin_label" => TRUE,
								"param_name" => "ribbon_msg",
								"value" => "SPECIAL OFFER",
								"group"	=> "Layout",
							),
							array(
								"type" => "icon_manager",
								"class" => "",
								"heading" => __("Left Icon ","ultimate_vc"),
								"param_name" => "left_icon",
								"value" => "",
								"group" => "Layout",
							),
							array(
								"type" => "icon_manager",
								"class" => "",
								"heading" => __("Right Icon ","ultimate_vc"),
								"param_name" => "right_icon",
								"value" => "",
								"group" => "Layout",
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Hide Ribbon Wings", "ultimate_vc"),
								"param_name" => "ribbon_wings",
								"value" => 
								array(
									__("None","ultimate_vc") => "none",
									__("Small Devices","ultimate_vc") => "small",
									__("Medium & Small Devices","ultimate_vc") => "medium",
								),
								"description" => "To hide Ribbon Wings on Small or Medium device use this option.",
								"group" => "Layout",
							),
							array(
								"type" => "ult_param_heading",
								"text" => __("Style","ultimate_vc"),
								"param_name" => "style_option",
								"class" => "ult-param-heading",
								'edit_field_class' => 'ult-param-heading-wrapper top-margin vc_column vc_col-sm-12',
								"group" => "Layout",
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Ribbon Width", "ultimate_vc"),
								"param_name" => "ribbon_width",
								"value" => 
								array(
									__("Auto","ultimate_vc") => "auto",
									__("Full","ultimate_vc") => "full",
									__("Custom","ultimate_vc") => "custom",
								),
								"group" => "Layout",
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Custom Width", "ultimate_vc"),
								"param_name" => "custom_width",
								"value" => "",
								"suffix" => "px",
								"dependency"=> Array("element" => "ribbon_width", "value" => array("custom")),
								"group" => "Layout",
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Alignment", "ultimate_vc"),
								"param_name" => "ribbon_alignment",
								"value" => 
								array(
									__("Center","ultimate_vc") => "center",
									__("Left","ultimate_vc") => "left",
									__("Right","ultimate_vc") => "right",
								),
								"group" => "Layout",
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Stitching", "ultimate_vc"),
								"param_name" => "ribbon_stitching",
								"value" => 
								array(
									__("Yes","ultimate_vc") => "yes",
									__("No","ultimate_vc") => "no",
								),
								"description" => "To give Stitch effect on Ribbon.",
								"group" => "Layout",
							),
							array(
								"type" => "dropdown",
								"class" => "",
								"heading" => __("Shadow", "ultimate_vc"),
								"param_name" => "rib_shadow",
								"value" => 
								array(
									__("Yes","ultimate_vc") => "yes",
									__("No","ultimate_vc") => "no",
								),
								"group" => "Layout",
							),
							array(
								"type" => "ult_param_heading",
								"text" => __("Ribbon Colors","ultimate_vc"),
								"param_name" => "ribbon_option",
								"class" => "ult-param-heading",
								'edit_field_class' => 'ult-param-heading-wrapper top-margin vc_column vc_col-sm-12',
								"group" => "Layout",
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Ribbon Color", "ultimate_vc"),
								"param_name" => "ribbon_color",
								"value"=> "",
								"group" => "Layout",
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Icon Color", "ultimate_vc"),
								"param_name" => "icon_color",
								"value"=> "",
								"group" => "Layout",
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Ribbon Fold Color", "ultimate_vc"),
								"param_name" => "rib_fold_color",
								"value"=> "",
								"group" => "Layout",
							),
							array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Ribbon Wings Color", "ultimate_vc"),
								"param_name" => "rib_wing_color",
								"value"=> "",
								"group" => "Layout",
							),
							array(
								"type" => "textfield",
								"heading" => __("Extra class name", "ultimate_vc"),
								"param_name" => "el_class",
								"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "ultimate_vc"),
								"group" => "Layout",
							),
							array(
								"type" => "ult_param_heading",
								"text" => __("Ribbon Text Settings","ultimate_vc"),
								"param_name" => "ribbon_text_typograpy",
								"class" => "ult-param-heading",
								'edit_field_class' => 'ult-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
								"group" => "Typography",
							),
							array(
								"type" => "dropdown",
								"heading" => __("Tag","ultimate_vc"),
								"param_name" => "heading_tag",
								"value" => array(
									__("Default","ultimate_vc") => "h3",
									__("H1","ultimate_vc") => "h1",
									__("H3","ultimate_vc") => "h2",
									__("H4","ultimate_vc") => "h4",
									__("H5","ultimate_vc") => "h5",
									__("H6","ultimate_vc") => "h6",
									__("Div","ultimate_vc") => "div",
									__("p","ultimate_vc") => "p",
									__("span","ultimate_vc") => "span",
								),
								"description" => __("Default is H3", "ultimate_vc"),
								"group" => "Typography",
							),
							array(
								"type" => "ultimate_google_fonts",
								"heading" => __("Font Family", "ultimate_vc"),
								"param_name" => "ribbon_font_family",
								"description" => __("Select the font of your choice.","ultimate_vc")." ".__("You can","ultimate_vc")." <a target='_blank' rel='noopener' href='".admin_url('admin.php?page=bsf-google-font-manager')."'>".__("add new in the collection here","ultimate_vc")."</a>.",
								"group" => "Typography",
							),
							array(
								"type" => "ultimate_google_fonts_style",
								"heading" 		=>	__("Font Style", "ultimate_vc"),
								"param_name"	=>	"ribbon_style",
								"group" => "Typography",
							),
							array(
                          	  	"type" => "ultimate_responsive",
                          	  	"class" => "font-size",
                          	  	"heading" => __("Font size", 'ultimate_vc'),
                          	  	"param_name" => "main_ribbon_font_size",
                          	  	"unit"  => "px",
                          	  	"media" => array(
                          	  	    "Desktop"           => '',
                          	  	    "Tablet"            => '',
                          	  	    "Tablet Portrait"   => '',
                          	  	    "Mobile Landscape"  => '',
                          	  	    "Mobile"            => '',
                          	  	),
								"group" => "Typography",
                          	),
                          	array(
                          	  	"type" => "ultimate_responsive",
                          	  	"class" => "font-size",
                          	  	"heading" => __("Line Height", 'ultimate_vc'),
                          	  	"param_name" => "main_ribbon_line_height",
                          	  	"unit"  => "px",
                          	  	"media" => array(
                          	  	    "Desktop"           => '',
                          	  	    "Tablet"            => '',
                          	  	    "Tablet Portrait"   => '',
                          	  	    "Mobile Landscape"  => '',
                          	  	    "Mobile"            => '',
                          	  	),
								"group" => "Typography",
                          	),
                          	array(
								"type" => "colorpicker",
								"class" => "",
								"heading" => __("Font Color", "ultimate_vc"),
								"param_name" => "ribbon_text_color",
								"value" => "",
								"group" => "Typography"
							),
							array(
								"type" => "dropdown",
								"heading" => __("Transform","ultimate_vc"),
								"param_name" => "ribbontext_trans",
								"value" => array(
									__("Default","ultimate_vc") => "unset",
									__("UPPERCASE","ultimate_vc") => "uppercase",
									__("lowercase","ultimate_vc") => "lowercase",
									__("Capitalize","ultimate_vc") => "capitalize",
								),
								"group" => "Typography",
							),
							array(
								"type" => "number",
								"class" => "",
								"heading" => __("Letter Spacing", "ultimate_vc"),
								"param_name" => "letter_space",
								"value" => "",
								"min" => 1,
								"max" => 15,
								"suffix" => "px",
								"group" => "Typography",
							),
							array(
					            'type' => 'css_editor',
					            'heading' => __( 'Css', 'ultimate_vc' ),
					            'param_name' => 'css_ribbon_design',
					            'group' => __( 'Design ', 'ultimate_vc' ),
					            'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
					        ),
						)
					)
				);
			}
		}//End of INit function

		function ultimate_ribbons_module_shortcode($atts, $content = null){
			$ribbon_msg = $left_icon = $right_icon = $ribbon_stitching = $ribbon_width = $ribbon_alignment = $custom_width = $rib_width = $rib_align = $rib_shadow = $ribbon_color = $icon_color = $rib_wing_color = $rib_left_color = $rib_right_color = $ribbon_style_inline = $rib_fold_color = $main_ribbon_font_size = $main_ribbon_line_height = $main_ribbon_responsive = $main_ribbon_style_inline = $ribbontext_trans = $ribbont_trans = $letter_space = $ribbon_spacer = $ribbon_wings = $el_class = $ribbon_design_style_css = $ribc_width = $heading_tag = $rib_media = $output = "";
			extract(shortcode_atts(array(
				"ribbon_msg"				=> "SPECIAL OFFER",
				"left_icon"					=> "",
				"right_icon"				=> "",
				"ribbon_stitching"			=> "yes",
				"ribbon_width"				=> "auto",
				"ribbon_alignment"			=> "center",
				"custom_width"				=> "",
				"rib_shadow"				=> "yes",
				"ribbon_color"				=> "",
				"icon_color"				=> "",
				"rib_wing_color"			=> "",
				"ribbon_font_family"		=> "",
				"ribbon_style"				=> "",
				"rib_fold_color"			=> "",
				"main_ribbon_font_size" 	=> "",
				"main_ribbon_line_height"	=> "",
				"ribbon_text_color"			=> "",
				"ribbontext_trans"			=> "unset",
				"letter_space"				=> "",
				"ribbon_wings"				=> "none",
				"el_class"					=> "",
				"css_ribbon_design"			=> "",
				"heading_tag"				=> "",
			),$atts));
			$vc_version = (defined('WPB_VC_VERSION')) ? WPB_VC_VERSION : 0;
			$is_vc_49_plus = (version_compare(4.9, $vc_version, '<=')) ? 'ult-adjust-bottom-margin' : '';

			//Default Design Editor
			$ribbon_design_style_css = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_ribbon_design, ' ' ), "ultimate_ribbons", $atts );

			 $ribbon_design_style_css = esc_attr( $ribbon_design_style_css );

			$micro = rand(0000,9999);
			$id = uniqid('ultimate-ribbon-'.$micro);
			/*
			* Style option for Ribbon Module
			*
			*/
			if($heading_tag == '')
				$heading_tag = 'h3';

			if($ribbon_alignment != '')
			{
				$rib_align = 'text-align:'.esc_attr($ribbon_alignment).';';
			}
			if($ribbon_width != '')
			{
				if($ribbon_width == 'auto')
				{
					$rib_width = 'auto';
				}
				else if($ribbon_width == 'full')
				{
					$rib_width .= 'full';
				}
				else if($ribbon_width = 'custom')
				{
					$rib_width .= 'custom';
					$ribc_width = 'width:calc('.esc_attr($custom_width).'px - 7em)';
				}
			}

			if($ribbon_wings != 'none')
			{
				$rib_media = 'media-width';
			}
			/*
			* Color option for Ribbon Module
			*
			*/
			if($ribbon_color != '')
			{
				$ribbon_color = 'background:'.esc_attr($ribbon_color).';';
			}
			if($icon_color != '')
			{
				$icon_color = 'color:'.esc_attr($icon_color).';';
			}
			if($rib_wing_color != '')
			{
				$rib_left_color = 'border-top-color:'.esc_attr($rib_wing_color).';';
				$rib_left_color .= 'border-bottom-color:'.esc_attr($rib_wing_color).';';
				$rib_left_color .= 'border-right-color:'.esc_attr($rib_wing_color).';';

				$rib_right_color = 'border-top-color:'.esc_attr($rib_wing_color).';';
				$rib_right_color .= 'border-bottom-color:'.esc_attr($rib_wing_color).';';
				$rib_right_color .= 'border-left-color:'.esc_attr($rib_wing_color).';';
			}
			if($rib_fold_color != '')
			{
				$output .= '<style>
					.'.esc_attr( $id ).' .ult-ribbon-text:before, .'.esc_attr( $id ).' .ult-ribbon-text:after {
						border-top-color: '.esc_attr($rib_fold_color).';
						border-right-color: transparent;
						border-bottom-color: transparent;
						border-left-color: transparent;
					}
					</style>';
			}

			/* ---- main heading styles ---- */
			if($ribbon_font_family != '')
			{
				$mrfont_family = get_ultimate_font_family($ribbon_font_family);
				if($mrfont_family)
					$ribbon_style_inline .= 'font-family:\''.$mrfont_family.'\';';
			}
			// main ribbon font style
			$ribbon_style_inline .= get_ultimate_font_style($ribbon_style);

			// FIX: set old font size before implementing responsive param
			if(is_numeric($main_ribbon_font_size)) 	{ 	$main_ribbon_font_size = 'desktop:'.$main_ribbon_font_size.'px;';		}
			if(is_numeric($main_ribbon_line_height)) 	{ 	$main_ribbon_line_height = 'desktop:'.$main_ribbon_line_height.'px;';		}

			// responsive {main} ribbon styles
		  	$args = array(
		  		'target'		=>	'.'.$id. ' .ult-ribbon-text-title',
		  		'media_sizes' 	=> array(
					'font-size' 	=> $main_ribbon_font_size,
					'line-height' 	=> $main_ribbon_line_height,
				),
		  	);
			$main_ribbon_responsive = get_ultimate_vc_responsive_media_css($args);

			//attach font color if set
			if($ribbon_text_color != '')
				$main_ribbon_style_inline .= 'color:'.$ribbon_text_color.';';

			//Text -Transform Property for Ribbon Text
			if($ribbontext_trans != '')
			{
				$ribbont_trans = 'text-transform: '.$ribbontext_trans.';';
			}
			//Letter spacing for Ribbon Text
			if($letter_space !== '')
					$ribbon_spacer = 'letter-spacing:'.$letter_space.'px';

			$output .= '<div id="'.esc_attr( $id ).'" class="ultr-ribbon '.esc_attr($ribbon_design_style_css).' '.esc_attr( $is_vc_49_plus ).' '.esc_attr( $id ).' '.esc_attr( $el_class ).'">';
				$output .= '<div class="ult-ribbon-wrap" style= "'.esc_attr($rib_align).'">
					<'.$heading_tag.' class="ult-ribbon '.esc_attr($rib_width).' '.esc_attr($rib_media).'" style="'.esc_attr($ribc_width).'">
						<span class="ult-left-ribb '.esc_attr($ribbon_wings).' '.esc_attr($rib_shadow).'" style= "'.esc_attr($rib_left_color).'"><i class="'.$left_icon.'" style="'.esc_attr($icon_color).'"></i></span>
						<span class="ult-ribbon-text '.esc_attr($ribbon_wings).'" style= "'.esc_attr($ribbon_color).'">';
							if ( $ribbon_stitching == 'yes' ) {
								$output .= '<div class="ult-ribbon-stitches-top"></div>'; }

								$output .= '<span class="ult-ribbon-text-title ult-responsive" '.$main_ribbon_responsive.' style="'.esc_attr($ribbon_style_inline ).' '.esc_attr($main_ribbon_style_inline).' '.esc_attr($ribbont_trans).' '.esc_attr($ribbon_spacer).'">'.esc_attr($ribbon_msg).'</span>';
							if ( $ribbon_stitching == 'yes' ) {
								$output .= '<div class="ult-ribbon-stitches-bottom"></div>';}
						$output .='</span>';
					$output .=	'<span class="ult-right-ribb  '.esc_attr($ribbon_wings).' '.esc_attr($rib_shadow).'" style= "'.esc_attr($rib_right_color).'"><i class="'.esc_attr($right_icon).'" style="'.esc_attr($icon_color).'"></i></span>
					</'.$heading_tag.'>
				</div>';
			$output .= '</div>';

		return $output;
		}
	}//End of class
	new Ultimate_Ribbons;
	if(class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_ultimate_ribbon'))
	{
		class WPBakeryShortCode_ultimate_ribbon extends WPBakeryShortCode {
		}
	}
}