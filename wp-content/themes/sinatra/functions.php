<?php
/** Start the engine */
require_once( TEMPLATEPATH . '/lib/init.php' );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Sinatra Theme' );
define( 'CHILD_THEME_URL', 'http://www.joshstauffer.com/themes/sinatra' );

// Enbed content widths
$content_width = apply_filters( 'content_width', 630, 490, 760 );

// Add suport for custom background
add_custom_background();

// Add support for custom header
add_theme_support( 'genesis-custom-header', array( 'width' => 960, 'height' => 100, 'textcolor' => 'fff', 'admin_header_callback' => 'sinatra_admin_style' ) );
function sinatra_admin_style() {

	$headimg = sprintf( '.appearance_page_custom-header #headimg { background: url(%s) no-repeat; font-family: \'Francois One\', sans-serif; min-height: %spx; }', get_header_image(), HEADER_IMAGE_HEIGHT );
	$h1 = sprintf( '#headimg h1, #headimg h1 a { color: #%s; font-size: 50px; font-weight: normal; line-height: 50px; margin: 25px 0 0; text-decoration: none; }', esc_html( get_header_textcolor() ) );
	$desc = sprintf( '#headimg #desc { color: #%s; display: none; }', esc_html( get_header_textcolor() ) );

	printf( '<style type="text/css">%1$s %2$s %3$s</style>', $headimg, $h1, $desc );

}

// Unregister other site layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

// Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// Register widget areas
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> __( 'Home Slider', 'sinatra' ),
	'description'	=> __( 'This is the home slider section.', 'sinatra' ),
) );

// Reposition the Secondary Navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_header', 'genesis_do_subnav' );

// Add widget section to homepage if there are active widgets
add_action( 'genesis_before_content', 'sinatra_home_slider_section' );
function sinatra_home_slider_section() {

	if ( is_front_page() ) {
		if ( is_active_sidebar( 'home-slider' ) ) {
			echo '<div class="home-slider">';
			dynamic_sidebar( 'home-slider' );
			echo '</div><!-- end .home-slider -->';
		}
	}

}

// Reposition the post info
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_after_post_content', 'genesis_post_info', 5 );

// Swap Genesis Slider stylesheet
remove_action( 'wp_print_styles', 'genesis_slider_styles' );
add_action( 'wp_print_styles', 'sinatra_slider_styles' );
function sinatra_slider_styles() {

	wp_register_style( 'sinatra_slider_styles', CHILD_URL . '/slider-style.css' );
	wp_enqueue_style( 'sinatra_slider_styles' );

}

if ( function_exists( 'genesis_get_slider_option' ) ) {

	// Override Genesis Slider inline styles
	add_action( 'wp_head', 'sinatra_slider_head', 1 );
	function sinatra_slider_head() {

			$height = ( int ) genesis_get_slider_option( 'slideshow_height' );
			$slideNavTop = ( int ) ( ($height - 60) );

			echo '
			<style type="text/css">
				.slider-next, .slider-previous { top: ' . $slideNavTop . 'px };
			</style>';
	}
}