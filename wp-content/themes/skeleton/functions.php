<?php
/**
 * @package Skeleton WordPress Theme Framework
 * @subpackage skeleton
 * @author Simple Themes - www.simplethemes.com
 *
 * Layout Functions:
 *
 * st_header  // Opening header tag and logo/header text
 * st_header_extras // Additional content may be added to the header
 * st_navbar // Opening navigation element and WP3 menus
 * st_before_content // Opening content wrapper
 * st_after_content // Closing content wrapper
 * st_before_sidebar // Opening sidebar wrapper
 * st_after_sidebar // Closing sidebar wrapper
 * st_before_footer // Opening footer wrapper
 * st_footer // The footer (includes sidebar-footer.php)
 * st_after_footer // The closing footer wrapper
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, skeleton_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'skeleton_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage skeleton
 * @since skeleton 0.1
 */

/*-----------------------------------------------------------------------------------*/
/* Set Proper Parent/Child theme paths for inclusion
/*-----------------------------------------------------------------------------------*/

@define( 'PARENT_DIR', get_template_directory() );
@define( 'CHILD_DIR', get_stylesheet_directory() );

@define( 'PARENT_URL', get_template_directory_uri() );
@define( 'CHILD_URL', get_stylesheet_directory_uri() );


/*-----------------------------------------------------------------------------------*/
/* Initialize the Options Framework
/* http://wptheming.com/options-framework-theme/
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'optionsframework_init' ) ) {

define('OPTIONS_FRAMEWORK_URL', get_template_directory_uri() . '/admin/');
define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory() . '/admin/');

require_once ( OPTIONS_FRAMEWORK_DIRECTORY.'options-framework.php');

} // endif



/*-----------------------------------------------------------------------------------*/
/* Customizeable Color Palette Preset
/*-----------------------------------------------------------------------------------*/

if (! function_exists('st_colorpicker_options'))  {

function st_colorpicker_options() {
	wp_enqueue_script( 'colorpicker-options', get_template_directory_uri() . '/javascripts/colorpicker.js', array( 'jquery','wp-color-picker' ),1,true );
}
add_action( 'optionsframework_custom_scripts', 'st_colorpicker_options' );

} // endif function exists


/*-----------------------------------------------------------------------------------*/
/* Define the sidebar and content widths for use in multiple functions
/* These values can be overridden on a conditional basis later on. See comments.
/*-----------------------------------------------------------------------------------*/


if (!of_get_option('sidebar_width')) {
	define('SIDEBARWIDTH', 'five');
} else {
	define('SIDEBARWIDTH', of_get_option('sidebar_width'));
}

if (!of_get_option('content_width')) {
	define('CONTENTWIDTH', 'eleven');
} else {
	define('CONTENTWIDTH', of_get_option('content_width'));
}


// Load theme-specific shortcodes and helpers
require_once (PARENT_DIR . '/shortcodes.php');

/*-----------------------------------------------------------------------------------*/
/* Register Core Stylesheets
/* These are necessary for the theme to function as intended
/* Supports the 'Better WordPress Minify' plugin to properly minimize styleshsets into one.
/* http://wordpress.org/extend/plugins/bwp-minify/
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_registerstyles' ) ) {

function st_registerstyles() {

	// Set a dynamic version for cache busting
	$theme = wp_get_theme();
	if(is_child_theme()) {
		$parent = $theme->parent();
		$version = $parent['Version'];
		} else {
		$version = $theme['Version'];
	}

	$stylesheets = '';

	// register the various widths based on max_layout_width option
	$maxwidth = of_get_option('max_layout_width');

	if ($maxwidth) {
		// load the appropriate stylesheet
  		$stylesheets .= wp_register_style('skeleton', get_bloginfo('template_directory').'/css/skeleton-'.$maxwidth.'.css', array(), $version, 'screen, projection');
	} else {
		//fallback to original for legacy theme compatibility
  		$stylesheets .= wp_register_style('skeleton', get_bloginfo('template_directory').'/css/skeleton-960.css', array(), $version, 'screen, projection');
	}

	// Register all other applicable stylesheets
    $stylesheets .= wp_register_style('layout', get_bloginfo('template_directory').'/css/layout.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('formalize', get_bloginfo('template_directory').'/css/formalize.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('superfish', get_bloginfo('template_directory').'/css/superfish.css', array(), $version, 'screen, projection');
    $stylesheets .= wp_register_style('theme', get_bloginfo('stylesheet_directory').'/style.css', array(), $version, 'screen, projection');

	// hook to add additional stylesheets from a child theme
	echo apply_filters ('child_add_stylesheets',$stylesheets);

	// enqueue registered styles
	wp_enqueue_style( 'skeleton');
	wp_enqueue_style( 'theme');
	wp_enqueue_style( 'layout');
	wp_enqueue_style( 'formalize');
	wp_enqueue_style( 'superfish');
}

add_action( 'wp_enqueue_scripts', 'st_registerstyles');

}

/*-----------------------------------------------------------------------------------*/
/* Build Query vars for dynamic theme option CSS from Options Framework
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'production_stylesheet' )) {

	if (of_get_option('dev_mode') == '1') {

		function production_stylesheet($public_query_vars) {
		    $public_query_vars[] = 'get_styles';
		    return $public_query_vars;
		}
		add_filter('query_vars', 'production_stylesheet');
	}

} // endif function_exists


if ( !function_exists( 'theme_css' ) ) {

	if (of_get_option('dev_mode') == '1') {
		function theme_css(){
		    $css = get_query_var('get_styles');
	    	if ($css == 'css'){
				get_template_part( 'st_loadstyles' );
	        	exit;  //This stops WP from loading any further
	    	}
		}
		add_action('template_redirect', 'theme_css');
	}

} // endif function_exists


// Customization Mode - load custom styles in wp_head

if ( !function_exists( 'custom_mode' ) ) {

	if (of_get_option('dev_mode') == '0') {

		function custom_mode() {
			echo '<style type="text/css">';
			get_template_part( 'st_styles' );
	 		echo '</style>';
		}
		add_action( 'wp_head', 'custom_mode' );

	}
} // endif function_exists



/*-----------------------------------------------------------------------------------*/
/* Register Core Javascript
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'st_header_scripts' ) ) {

	add_action('init', 'st_header_scripts');
	function st_header_scripts() {
		$javascripts  = wp_enqueue_script('jquery');
		$javascripts .= wp_enqueue_script('custom',get_bloginfo('template_url') ."/javascripts/app.js",array('jquery'),'1.2.3',true);
		$javascripts .= wp_enqueue_script('superfish',get_bloginfo('template_url') ."/javascripts/superfish.js",array('jquery'),'1.2.3',true);
		$javascripts .= wp_enqueue_script('formalize',get_bloginfo('template_url') ."/javascripts/jquery.formalize.min.js",array('jquery'),'1.2.3',true);
		echo apply_filters ('child_add_javascripts',$javascripts);
	}

}

/*-----------------------------------------------------------------------------------*/
/* Instead of remove_filter('the_content', 'wpautop');
/* Removes wpautop from specified pages with a custom field:
/* Name: wpautop Value: false
/*-----------------------------------------------------------------------------------*/


function st_remove_wpautop($content) {
    global $post;
    // Get the keys and values of the custom fields:
    $rmwpautop = get_post_meta($post->ID, 'wpautop', true);
    // Remove the filter
    remove_filter('the_content', 'wpautop');
    if ('false' === $rmwpautop) {
    } else {
    add_filter('the_content', 'wpautop');
    }
    return $content;
}
// Hook into the Plugin API
add_filter('the_content', 'st_remove_wpautop', 9);


/** Tell WordPress to run skeleton_setup() when the 'after_setup_theme' hook is run. */

add_action( 'after_setup_theme', 'skeleton_setup' );

if ( ! function_exists( 'skeleton_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override skeleton_setup() in a child theme, add your own skeleton_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails, custom-header and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Skeleton 1.0
 */
function skeleton_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

 	// Use Regenerate Thumbnails Plugin to create these images on an existing install..
	// Set default thumbnail size
  	set_post_thumbnail_size( 150, 150 );
	// 75px square
	add_image_size( $name = 'squared75', $width = 75, $height = 75, $crop = true );
	// 150px square
	add_image_size( $name = 'squared150', $width = 150, $height = 150, $crop = true );
	// 250px square
	add_image_size( $name = 'squared250', $width = 250, $height = 250, $crop = true );
	// 4:3 Video
	add_image_size( $name = 'video43', $width = 320, $height = 240, $crop = true );
	// 16:9 Video
	add_image_size( $name = 'video169', $width = 320, $height = 180, $crop = true );
	// 4:3 Video Banner
	add_image_size( $name = 'largevideo43', $width = 640, $height = 480, $crop = true );
	// 16:9 Video Banner
	add_image_size( $name = 'largevideo169', $width = 640, $height = 360, $crop = true );
	// Thin Banner
	add_image_size( $name = 'banner', $width = 640, $height = 150, $crop = true );


	// Register the available menus
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'skeleton' ),
	));

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'skeleton', PARENT_DIR . '/languages' );

	$locale = get_locale();
	$locale_file = PARENT_DIR . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );


		// No support for text inside the header image.
		if ( ! defined( 'NO_HEADER_TEXT' ) )
			define( 'NO_HEADER_TEXT', true );

		if ( ! defined( 'HEADER_IMAGE_WIDTH') )
			define( 'HEADER_IMAGE_WIDTH', apply_filters( 'skeleton_header_image_width',960));


		if ( ! defined( 'HEADER_IMAGE_HEIGHT') )
			define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'skeleton_header_image_height',185 ));

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See skeleton_admin_header_style(), below.
		add_theme_support( 'custom-header', array('admin-head-callback' => 'skeleton_admin_header_style') );

		// ... and thus ends the changeable header business.

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'berries' => array(
				'url' => '%s/images/headers/berries.jpg',
				'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Berries', 'skeleton' )
			),
			'cherryblossom' => array(
				'url' => '%s/images/headers/cherryblossoms.jpg',
				'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Cherry Blossoms', 'skeleton' )
			),
			'concave' => array(
				'url' => '%s/images/headers/concave.jpg',
				'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Concave', 'skeleton' )
			),
			'fern' => array(
				'url' => '%s/images/headers/fern.jpg',
				'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Fern', 'skeleton' )
			),
			'forestfloor' => array(
				'url' => '%s/images/headers/forestfloor.jpg',
				'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Forest Floor', 'skeleton' )
			),
			'inkwell' => array(
				'url' => '%s/images/headers/inkwell.jpg',
				'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Inkwell', 'skeleton' )
			),
			'path' => array(
				'url' => '%s/images/headers/path.jpg',
				'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Path', 'skeleton' )
			),
			'sunset' => array(
				'url' => '%s/images/headers/sunset.jpg',
				'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
				/* translators: header image description */
				'description' => __( 'Sunset', 'skeleton' )
			)
		) );
	}
endif;


/*-----------------------------------------------------------------------------------*/
// Builds our thumbnail images
// See options.php and skeleton_setup() for customization
/*-----------------------------------------------------------------------------------*/

	function st_thumbnailer($content) {

		global $post;
		global $id;

		$theimage = get_image_path();
		$showthumbs = of_get_option('display_featured_thumbnails');
		$options = of_get_option('featured_thumbnail_places');

		switch ($showthumbs) {
			//Square Left
			case 'square_left':
				$size = 'squared150';
				$align = 'alignleft scale-with-grid';
			break;
			// Square Right
			case 'square_right':
				$size = 'squared150';
				$align = 'alignright scale-with-grid';
			break;
			// 4:3 Left
			case '43_left':
				$size = 'video43';
				$align = 'alignleft scale-with-grid';
			break;
			// 4:3 Right
			case '43_right':
				$size = 'video43';
				$align = 'alignright scale-with-grid';
			break;
			// 16:9 Left
			case '169_left':
				$size = 'video169';
				$align = 'alignleft scale-with-grid';
			break;
			// 16:9 Right
			case '169_right':
				$size = 'video169';
				$align = 'alignright scale-with-grid';
			break;
			// 16:9 Center Top
			case '169_top':
				$size = 'largevideo169';
				$align = 'aligncenter scale-with-grid';
			break;
			// Web Banner Top
			case 'banner_top':
				$size = 'banner';
				$align = 'aligncenter scale-with-grid';
			break;
			case 'disabled':
			break;
		}


		// Archives
		if ((is_archive() || is_home()) && $showthumbs != 'disabled' && $options['archive'] == 1 && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail($id, $size, array('class' => $align));
			$content =  $image . $content;
		}

		// Single Posts
		if (is_single() && $showthumbs != 'disabled' && $options['single'] == 1 && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail($id, $size, array('class' => $align));
			$content =  $image . $content;
		}

		// Single Pages
		if (is_page() && $showthumbs != 'disabled' && $options['page'] == 1 && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail($id, $size, array('class' => $align));
			$content =  $image . $content;
		}

		return $content;
	}
	add_filter('the_content','st_thumbnailer');



/*-----------------------------------------------------------------------------------*/
// Styles the header image displayed on the Appearance > Header admin panel.
// Referenced via add_theme_support( 'custom-header', $args )  in skeleton_setup().
/*-----------------------------------------------------------------------------------*/


	if ( !function_exists( 'skeleton_admin_header_style' ) ) :

	function skeleton_admin_header_style() {
	?>
	<style type="text/css">
	/* Shows the same border as on front end */
	#headimg {
		border-bottom: 100px solid #000;
		border-top: 4px solid #000;
	}
	/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
		#headimg #name { }
		#headimg #desc { }
	*/
	</style>
	<?php
	}
	endif;


/*-----------------------------------------------------------------------------------*/
// Sets the post excerpt length to 40 characters.
// To override this length in a child theme, remove the filter and add your own
// function tied to the excerpt_length filter hook.
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_excerpt_length' ) ) {

	function skeleton_excerpt_length( $length ) {
		return 40;
	}
	add_filter( 'excerpt_length', 'skeleton_excerpt_length' );

}



/*-----------------------------------------------------------------------------------*/
// Returns a "Continue Reading" link for excerpts
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_continue_reading_link' ) ) {

	function skeleton_continue_reading_link() {
		return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'skeleton' ) . '</a>';
	}
}


/*-----------------------------------------------------------------------------------*/
// Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis
// and skeleton_continue_reading_link().
//
// To override this in a child theme, remove the filter and add your own
// function tied to the excerpt_more filter hook.
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'skeleton_auto_excerpt_more' ) ) {

	function skeleton_auto_excerpt_more( $more ) {
		return ' &hellip;' . skeleton_continue_reading_link();
	}
	add_filter( 'excerpt_more', 'skeleton_auto_excerpt_more' );

}

/*-----------------------------------------------------------------------------------*/
// Adds a pretty "Continue Reading" link to custom post excerpts.
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'skeleton_custom_excerpt_more' ) ) {

	function skeleton_custom_excerpt_more( $output ) {
		if ( has_excerpt() && ! is_attachment() ) {
			$output .= skeleton_continue_reading_link();
		}
		return $output;
	}
	add_filter( 'get_the_excerpt', 'skeleton_custom_excerpt_more' );

}


/*-----------------------------------------------------------------------------------*/
// Removes inline styles printed when the gallery shortcode is used.
// Galleries are styled by the theme in Skeleton's style.css. This is just
// a simple filter call that tells WordPress to not use the default styles.
/*-----------------------------------------------------------------------------------*/

add_filter( 'use_default_gallery_style', '__return_false' );



/*-----------------------------------------------------------------------------------*/
// Removes the page jump when read more is clicked through
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'remove_more_jump_link' ) ) {

	function remove_more_jump_link($link) {
		$offset = strpos($link, '#more-');
		if ($offset) {
		$end = strpos($link, '"',$offset);
		}
		if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}
	add_filter('the_content_more_link', 'remove_more_jump_link');

}


/*-----------------------------------------------------------------------------------*/
//	Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
//	To override st_widgets_init() in a child theme, remove the action hook and add your own
//	function tied to the init hook.
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_widgets_init' ) ) {

function st_widgets_init() {
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
		'name' => __( 'Posts Widget Area', 'skeleton' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Shown only in Blog Posts, Archives, Categories, etc.', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Pages Widget Area', 'skeleton' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'Shown only in Pages', 'skeleton' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'skeleton' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'skeleton' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'skeleton' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'skeleton' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'skeleton' ),
		'before_widget' => '<div class="%1$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

/** Register sidebars by running skeleton_widgets_init() on the widgets_init hook. */

add_action( 'widgets_init', 'st_widgets_init' );

}

/*-----------------------------------------------------------------------------------*/
//	Comment Styles
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'st_comments' ) ) :
function st_comments($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="single-comment clearfix">
				<div class="comment-author vcard"> <?php echo get_avatar($comment,$size='64'); ?></div>
				<div class="comment-meta commentmetadata">
						<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Comment is awaiting moderation','smpl');?></em> <br />
						<?php endif; ?>
						<h6><?php echo __('By','smpl').' '.get_comment_author_link(). ' '. get_comment_date(). '  -  ' . get_comment_time(); ?></h6>
						<?php comment_text() ?>
						<?php edit_comment_link(__('Edit comment','smpl'),'  ',''); ?>
						<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply','smpl'),'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
				</div>
		</div>
<!-- </li> -->
<?php  }
endif;

if ( ! function_exists( 'skeleton_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Skeleton 1.0
 */
function skeleton_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'skeleton' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'skeleton' ), get_the_author() ),
			get_the_author()
		)
	);
}

endif;

if ( ! function_exists( 'skeleton_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Skeleton 1.0
 */
function skeleton_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'skeleton' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

endif;


// Header Functions

// Hook to add content before header

if ( !function_exists( 'st_above_header' ) ) {

function st_above_header() {
    do_action('st_above_header');
}

} // endif

// Primary Header Function

if ( !function_exists( 'st_header' ) ) {

function st_header() {
  do_action('st_header');
}

}


/*-----------------------------------------------------------------------------------*/
// Opening #header div with flexible grid
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_header_open' ) ) {

	function st_header_open() {
	  echo "<div id=\"header\" class=\"sixteen columns\">\n<div class=\"inner\">\n";
	}

} // endif

add_action('st_header','st_header_open', 1);


/*-----------------------------------------------------------------------------------*/
// Hookable theme option field to add add'l content to header
// Child Theme Override: child_header_extras();
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'st_header_extras' ) ) {

	function st_header_extras() {
		if (of_get_option('header_extra')) {
			$extras  = "<div class=\"header_extras\">";
			$extras .= of_get_option('header_extra');
			$extras .= "</div>";
			echo apply_filters ('child_header_extras',$extras);
		}
	}

} // endif

add_action('st_header','st_header_extras', 2);


/*-----------------------------------------------------------------------------------*/
/* SEO Logo
/* Displays H1 or DIV based on whether we are on the home page or not (for SEO)
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_logo' ) ) {

	function st_logo() {
		$heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
		if (of_get_option('use_logo_image')) {
			$class="graphic";
		} else {
			$class="text";
		}
		$st_logo  = '<'.$heading_tag.' id="site-title" class="'.$class.'"><a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'">'.get_bloginfo('name').'</a></'.$heading_tag.'>'. "\n";
		$st_logo .= '<span class="site-desc '.$class.'">'.get_bloginfo('description').'</span>'. "\n";
		echo apply_filters ( 'st_logo', $st_logo);
	}
	add_action('st_header','st_logo', 3);

} // endif


/*-----------------------------------------------------------------------------------*/
// Example of child theme logo replacement override
/*-----------------------------------------------------------------------------------*/


//	function my_custom_logo() {
//		$st_logo = '<img src="http://placehold.it/320x150/000/FFF" alt="Logo"/>';
//		return $st_logo;
//	}
//
//	add_filter('st_logo','my_custom_logo');



/*-----------------------------------------------------------------------------------*/
/* Output CSS for Graphic Logo
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'logostyle' ) ) {

function logostyle() {
	if (of_get_option('use_logo_image')) {
		echo '<style type="text/css">#header #site-title.graphic a {background-image: url('.of_get_option('header_logo').');width: '.of_get_option('logo_width').'px;height: '.of_get_option('logo_height').'px;}</style>';
	}
}
add_action('wp_head', 'logostyle');

} //endif


/*-----------------------------------------------------------------------------------*/
/* Closes the #header markup
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_header_close' ) ) {

	function st_header_close() {
		echo "</div>"."\n";
		echo "</div>"."\n";
		echo "<!--/#header-->"."\n";
	}
	add_action('st_header','st_header_close', 4);

} //endif


/*-----------------------------------------------------------------------------------*/
/* Hook to add custom content immediately after #header
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_below_header' ) ) {

	function st_below_header() {
		do_action('st_below_header');
	}

} //endif

// End Header Functions

/*-----------------------------------------------------------------------------------*/
/* Navigation (menu)
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_navbar' ) ) {

	function st_navbar() {
		echo '<div id="navigation" class="row sixteen columns">';
		wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary'));
		echo '</div><!--/#navigation-->';
	}

} //endif


/*-----------------------------------------------------------------------------------*/
/* Before Content - st_before_content($columns);
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'st_before_content' ) ) {
	global $post;
	function st_before_content($columns) {
	// Specify the number of columns in conditional statements
	// See http://codex.wordpress.org/Conditional_Tags for a full list
	//
	// If necessary, you can pass $columns as a variable in your template files:
	// st_before_content('six');
	//
	// Set the default
	if (is_page() && !is_active_sidebar('secondary-widget-area')) {
		$columns = 'sixteen';
	} elseif (is_single() && !is_active_sidebar('primary-widget-area')) {
		$columns = 'sixteen';
	}
	if (empty($columns)) {

		$columns = 'eleven';

	} else {
	// Check the function for a returned variable

		$columns = $columns;
	}

	// Example of further conditionals:
	// (be sure to add the excess of 16 to st_before_sidebar as well)

	if (is_page_template('onecolumn-page.php')) {

		$columns = 'sixteen';

	}

	// Apply the markup
	echo "<a name=\"top\" id=\"top\"></a>";
	echo "<div id=\"content\" class=\"$columns columns\">";
	}

}


/*-----------------------------------------------------------------------------------*/
/* After Content
/*-----------------------------------------------------------------------------------*/


if (! function_exists('st_after_content'))  {
    function st_after_content() {
    	echo "\t\t</div><!-- /.columns (#content) -->\n";
    }
}


/*-----------------------------------------------------------------------------------*/
/* Before Sidebar - do_action('st_before_sidebar')
/*-----------------------------------------------------------------------------------*/


if ( !function_exists( 'before_sidebar' ) ) {

	function before_sidebar($columns) {
	// You can specify the number of columns in conditional statements
	// See http://codex.wordpress.org/Conditional_Tags for a full list
	//
	// If necessary, you can also pass $columns as a variable in your template files:
	// do_action('st_before_sidebar','six');
	//
	if (empty($columns)) {
	// Set the default
	$columns = 'five';
	} else {
	// Check the function for a returned variable
	$columns = $columns;
	}
	// Example of further conditionals:
	// (be sure to add the excess of 16 to st_before_content as well)
	// if (is_page() || is_single()) {
	// $columns = 'five';
	// } else {
	// $columns = 'four';
	// }
	// Apply the markup
	echo '<div id="sidebar" class="'.$columns.' columns" role="complementary">';
	}
} //endif
// create our hook
add_action( 'st_before_sidebar', 'before_sidebar');


/*-----------------------------------------------------------------------------------*/
/* After Sidebar
/*-----------------------------------------------------------------------------------*/

if ( !function_exists( 'after_sidebar' ) ) {
	function after_sidebar() {
	// Additional Content could be added here
	   echo '</div><!-- #sidebar -->';
	}
} //endif
add_action( 'st_after_sidebar', 'after_sidebar');



/*-----------------------------------------------------------------------------------*/
/* Before Footer
/*-----------------------------------------------------------------------------------*/

if (!function_exists('st_before_footer'))  {
    function st_before_footer() {
			$footerwidgets = is_active_sidebar('first-footer-widget-area') + is_active_sidebar('second-footer-widget-area') + is_active_sidebar('third-footer-widget-area') + is_active_sidebar('fourth-footer-widget-area');
			$class = ($footerwidgets == '0' ? 'noborder' : 'normal');
			echo '<div class="clear"></div><div id="footer" class="'.$class.' sixteen columns">';
    }
}

if ( !function_exists( 'st_footer' ) ) {


/*-----------------------------------------------------------------------------------*/
/* Footer
/*-----------------------------------------------------------------------------------*/

add_action('wp_footer', 'st_footer');
	do_action('st_footer');
	function st_footer() {
		//loads sidebar-footer.php
		get_sidebar( 'footer' );
		// prints site credits
		echo '<div id="credits">';
		echo of_get_option('footer_text');
		echo '<br /><a class="themeauthor" href="http://www.simplethemes.com" title="Simple WordPress Themes">WordPress Themes</a></div>';
}
}


/*-----------------------------------------------------------------------------------*/
/* After Footer
/*-----------------------------------------------------------------------------------*/

if (!function_exists('st_after_footer'))  {

    function st_after_footer() {
			echo "</div><!--/#footer-->"."\n";
			echo "</div><!--/#wrap.container-->"."\n";
			// Google Analytics
			if (of_get_option('footer_scripts') <> "" ) {
				echo '<script type="text/javascript">'.stripslashes(of_get_option('footer_scripts')).'</script>';
			}
    }
}


/*-----------------------------------------------------------------------------------*/
/* Enable Shortcodes in excerpts and widgets
/*-----------------------------------------------------------------------------------*/


add_filter('widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');


if (!function_exists('get_image_path'))  {

function get_image_path() {
	global $post;
	$id = get_post_thumbnail_id();
	// check to see if NextGen Gallery is present
	if(stripos($id,'ngg-') !== false && class_exists('nggdb')){
	$nggImage = nggdb::find_image(str_replace('ngg-','',$id));
	$thumbnail = array(
	$nggImage->imageURL,
	$nggImage->width,
	$nggImage->height
	);
	// otherwise, just get the wp thumbnail
	} else {
	$thumbnail = wp_get_attachment_image_src($id,'full', true);
	}
	$theimage = $thumbnail[0];
	return $theimage;
}

}


/*-----------------------------------------------------------------------------------*/
/* Override default filter for 'textarea' sanitization.
/*-----------------------------------------------------------------------------------*/


add_action('admin_init','optionscheck_change_santiziation', 100);

function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'st_custom_sanitize_textarea' );
}

function st_custom_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
      "src" => array(),
      "type" => array(),
      "allowfullscreen" => array(),
      "allowscriptaccess" => array(),
      "height" => array(),
          "width" => array()
      );
    	$custom_allowedtags["script"] = array();
    	$custom_allowedtags["a"] = array('href' => array(),'title' => array());
    	$custom_allowedtags["img"] = array('src' => array(),'title' => array(),'alt' => array());
    	$custom_allowedtags["br"] = array();
    	$custom_allowedtags["em"] = array();
    	$custom_allowedtags["strong"] = array();
      $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      $output = wp_kses( $input, $custom_allowedtags);
    return $output;
        $of_custom_allowedtags = array_merge($of_custom_allowedtags, $allowedtags);
        $output = wp_kses( $input, $of_custom_allowedtags);
    return $output;
}