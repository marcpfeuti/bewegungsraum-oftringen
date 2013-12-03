<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 */

function optionsframework_option_name() {


	$theme  = wp_get_theme();
	$themename = $theme['Template'];
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );

}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *
 */

function optionsframework_options() {

// Background Defaults

$body_background_defaults = array(
'color' => '#fcfcfc',
'image' => site_url('wp-content/themes/skeleton/images/border_top.png'),
'repeat' => 'repeat-x',
'position' => 'top center',
'attachment'=>'fixed');


// If using image radio buttons, define a directory path
$imagepath =  get_bloginfo('template_directory') . '/images/';

$options = array();


$options[] = array( "name" => "General",
					"type" => "heading");


$options[] = array( "name" => "Customization",
					"desc" => "In Dynamic Mode customizations are cached to a dynamic stylesheet, however larger sites may notice a decrease in performance. Static Mode simply puts the styles in the document head. Advanced mode does neither and allows you to copy the styles to the stylesheet yourself.",
					"id" => "dev_mode",
					"std" => "0",
					"type" => "radio",
					"class" => "linear",
					"options" => array(
						"1" => "Dynamic Mode",
						"0" => "Static Mode",
						"2" => "Advanced Mode"
						)
					);


$options[] = array( "name" =>  "Body Background",
					"desc" => "Change the background CSS.",
					"id" => "body_background",
					"std" => $body_background_defaults,
					"type" => "background");


$options[] = array( "name" => "Extra Header Text",
					"desc" => "HTML or text can be inserted into the header. You might add twitter icons, badges, or a site announcement here.",
					"id" => "header_extra",
					"std" => "",
					"type" => "textarea");


$options[] = array( "name" => "Footer Fine Print",
					"desc" => "HTML or text to be inserted into the very bottom after the widgets.",
					"id" => "footer_text",
					"std" => "",
					"type" => "editor",
					"options" => array(
						'cols' => 4)
					);


$options[] = array( "name" => "Footer Scripts",
					"desc" => "Add custom footer scripts such as Google Analytics. Do not include the &lt;script&gt; tag. This is already done for you.",
					"id" => "footer_scripts",
					"std" => "",
					"type" => "textarea");


$options[] = array( "name" => "Header",
					"type" => "heading");


$options[] = array( "name" => "Logo Style",
					"desc" => "Display a custom image/logo image in place of title header.",
					"id" => "use_logo_image",
					"type" => "checkbox");


$options[] = array( "name" => "Header Logo",
					"desc" => "If you prefer to show a graphic logo in place of the header, you can upload or paste the URL here. Set the width and height below. <strong>Your logo should be resized prior to uploading</strong>",
					"id" => "header_logo",
					"class" => "hidden",
					"type" => "upload");

$options[] = array( "name" => "Logo Width",
					"desc" => "Width (in px) of Your logo.",
					"id" => "logo_width",
					"std" => "300",
					"class" => "mini hidden",
					"type" => "text");

$options[] = array( "name" => "Logo Height",
					"desc" => "Height (in px) of Your logo.",
					"id" => "logo_height",
					"std" => "80",
					"class" => "mini hidden",
					"type" => "text");


$options[] = array( "name" => "Text Header Settings",
					"desc" => "If you choose not to upload a logo for your header, the options below allow you to customize the text and tagline. If you have uploaded a logo, the settings below have no effect.",
					"class" => "none",
					"type" => "info"
					);

$options[] = array( "name" => "Header Text Style",
					"desc" => "Header text style.",
					"id" => "header_typography",
					"std" => array(
						'size' => '40px',
						'face' => 'helvetica',
						'style' => 'normal',
						'color' => '#181818'
						),
					"type" => "typography"
					);

$options[] = array( "name" => "Tagline Text Style",
					"desc" => "Tagline text style.",
					"id" => "tagline_typography",
					"std" => array('size' => '24px','face' => 'helvetica','style' => 'normal','color' => '#CCCCCC'),
					"type" => "typography");


$options[] = array( "name" => "Typography",
					"type" => "heading");

$options[] = array( "name" => "Customization",
					"desc" => "The following options allow you to apply basic customizations to the main theme typography. In some cases however, you will need to edit CSS. This can be achieved through the <a href=\"theme-editor.php\">stylesheet editor</a> or by navigating to Appearance &rarr; Editor.",
					"type" => "info");

$options[] = array( "name" => "Link Color",
					"desc" => "Default hyperlink colors.",
					"id" => "link_color",
					"std" => "#3568A9",
					"type" => "color");

$options[] = array( "name" => "Main Body Typography",
					"desc" => "Body Typography.",
					"id" => "body_typography",
					"std" => array('size' => '14px','face' => 'helvetica','style' => 'normal','color' => '#444444'),
					"type" => "typography");


$options[] = array( "name" => "(H1) Heading Typography",
					"desc" => "Heading typography.",
					"id" => "h1_typography",
					"std" => array('size' => '40px','face' => 'helvetica','style' => 'normal','color' => '#181818'),
					"type" => "typography");

$options[] = array( "name" => "(H2) Heading Typography",
					"desc" => "Heading Two typography.",
					"id" => "h2_typography",
					"std" => array('size' => '35px','face' => 'helvetica','style' => 'normal','color' => '#181818'),
					"type" => "typography");


$options[] = array( "name" => "(H3) Heading Typography",
					"desc" => "Heading Three typography.",
					"id" => "h3_typography",
					"std" => array('size' => '28px','face' => 'helvetica','style' => 'normal','color' => '#181818'),
					"type" => "typography");


$options[] = array( "name" => "(H4) Heading Typography",
					"desc" => "Heading Four typography.",
					"id" => "h4_typography",
					"std" => array('size' => '21px','face' => 'helvetica','style' => 'bold','color' => '#181818'),
					"type" => "typography");

$options[] = array( "name" => "(H5) Heading Typography",
					"desc" => "Heading Five typography.",
					"id" => "h5_typography",
					"std" => array('size' => '17px','face' => 'helvetica','style' => 'bold','color' => '#181818'),
					"type" => "typography");

$options[] = array( "name" => "Layout",
					"type" => "heading");

$options[] = array( "name" => "Max Layout Width",
					"desc" => "Select preferred container maximum layout width.",
					"id" => "max_layout_width",
					"std" => "960",
					"type" => "radio",
					"class" => "linear",
					"options" => array(
						'960' => '960px',
						'1140' => '1140px',
						'1200' => '1200px')
					);

$options[] = array( "name" => "Sidebar Position",
					"desc" => "Select a sidebar layout position (left or right). You can also select a wide page layout on a per-page basis.",
					"id" => "page_layout",
					"std" => "right",
					"type" => "images",
					"options" => array(
						'left' => $imagepath . '2cl.png',
						'right' => $imagepath . '2cr.png')
					);


$options[] = array( "name" => "Show Featured Thumbnails",
					"desc" => "Enables display of blog post featured thumbnails. Use the <a href=\"http://wordpress.org/plugins/regenerate-thumbnails/\">Regenate Thumbnails Plugin</a> if you have existing images that need to be regenerated.",
					"id" => "display_featured_thumbnails",
					"std" => "none",
					"type" => "radio",
					"class" => "linear",
					"options" => array(
						"disabled" => "Do not display",
						"square_left" => "Square Aligned Left",
						"square_right" => "Square Aligned Right",
						"43_left" => "4:3 Aligned Left",
						"43_right" => "4:3 Aligned Right",
						"169_left" => "16:9 Aligned Left",
						"169_right" => "16:9 Aligned Right",
						"169_top" => "16:9 Banner Top Wide",
						"banner_top" => "Thin Banner Top Wide",
						)
					);

$options[] = array( "name" => "Display Featured Thumbnails on the following pages.",
					"desc" => "",
					"id" => "featured_thumbnail_places",
					"type" => "multicheck",
					"options" => array(
						'archive' => 'Archive Pages',
						'single' => 'Single Posts',
						'page' => 'Single Pages',
						)
					);



	return $options;
}
