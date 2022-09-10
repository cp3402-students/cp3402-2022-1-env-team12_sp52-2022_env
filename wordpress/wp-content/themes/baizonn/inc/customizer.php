<?php
/**
 * baizonn Theme Customizer
 *
 * @package baizonn
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function baizonn_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Custom Customizer Customizations
	 */
	
	// Setting for header background color
	$wp_customize->add_setting( 'header_bg_color', array(
		'default' => '#ffffff',
		'transport' => 'postMessage',
		'type' => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	
	// Control for headerbackground color.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_bg_color', array(
				'label' => __( 'Header background color', 'baizonn'),
				'section' => 'colors',
				'settings' => 'header_bg_color'
			)
		)
	);

	// Setting for header background color
	$wp_customize->add_setting( 'footer_bg_color', array(
		'default' => '#13b55c',
		'transport' => 'postMessage',
		'type' => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	
	// Control for headerbackground color.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'footer_bg_color', array(
				'label' => __( 'Footer background color', 'baizonn'),
				'section' => 'colors',
				'settings' => 'footer_bg_color'
			)
		)
	);
	// Create interactive color setting
	$wp_customize->add_setting( 'interactive_color' , 
		array(
			'default'			=> '#138143',
			'transport'			=> 'postMessage',
			'type'				=> 'option',
			'sanitize_callback'	=> 'sanitize_hex_color'
		)
	);
	
	// Add the controls for interactive color setting
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'interactive_color', array(
				'label'		=> __( 'Interactive color (links etc)', 'baizonn' ),
				'section'	=> 'colors',
				'settings'	=> 'interactive_color'
			)
		)
	);	
}
add_action( 'customize_register', 'baizonn_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function baizonn_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function baizonn_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function baizonn_customize_preview_js() {
	wp_enqueue_script( 'baizonn-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'baizonn_customize_preview_js' );



if ( ! function_exists( 'baizonn_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see baizonn_custom_header_setup().
	 */
	function baizonn_header_style() {
		$header_text_color = get_header_textcolor();
		$header_bg_color = get_option( 'header_bg_color' );
		$footer_bg_color = get_option( 'footer_bg_color' );
		$interactive_color= get_option( 'interactive_color' );

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) != $header_text_color ) {

			// If we get this far, we have custom styles. Let's do this.
			?>
			<style type="text/css">
			<?php
			// Has the text been hidden?
			if ( ! display_header_text() ) :
				?>
				.site-title,
				.site-description {
					position: absolute;
					clip: rect(1px, 1px, 1px, 1px);
					}
				<?php
				// If the user has set a custom color for the text use that.
			else :
				?>
				.site-title a,
				.site-description {
					color: #<?php echo esc_attr( $header_text_color ); ?>;
				}
			<?php endif; ?>
			</style>
			<?php
		}
		if ("#ffffff" != $header_bg_color) {
			?>
			<style type="text/css">
				.site-header {
					background-color: <?php echo esc_attr( $header_bg_color ); ?>;
				}
			</style>
			<?php
		}

		if ("#13b55c" != $footer_bg_color) {
			?>
			<style type="text/css">
				.site-footer {
					background-color: <?php echo esc_attr( $footer_bg_color); ?>;
				}
			</style>
			<?php
		}
		/*
		* Do we have a custom interactive color?
		*/
		if ( '#138143' != $interactive_color ) { ?>
			<style type="text/css">
				a:hover,
				a:focus, 
				a:active,
				.page-content a:focus, .page-content a:hover,
				.entry-content a:focus,
				.entry-content a:hover,
				.entry-summary a:focus,
				.entry-summary a:hover,
				.comment-content a:focus,
				.comment-content a:hover,
				.cat-links a {
					color: <?php echo esc_attr( $interactive_color ); ?>;
				}
				
				.page-content a,
				.entry-content a,
				.entry-summary a,
				.comment-content a,
				.post-navigation .post-title,
				.comment-navigation a:hover, 
				.comment-navigation a:focus,
				.posts-navigation a:hover,
				.posts-navigation a:focus,
				.post-navigation a:hover,
				.post-navigation a:focus,
				.paging-navigation a:hover,
				.paging-navigation a:focus,
				.entry-title a:hover, 
				.entry-title a:focus,
				.entry-meta a:focus, 
				.entry-meta a:hover,
				.entry-footer a:focus,
				.entry-footer a:hover,
				.reply a:hover, 
				.reply a:focus,
				.comment-form .form-submit input:hover, 
				.comment-form .form-submit input:focus,
				.widget a:hover, 
				.widget a:focus {
					border-color: <?php echo esc_attr( $interactive_color ); ?>;
				}
				
				.comment-navigation a:hover, 
				.comment-navigation a:focus,
				.posts-navigation a:hover,
				.posts-navigation a:focus,
				.post-navigation a:hover,
				.post-navigation a:focus,
				.paging-navigation a:hover,
				.paging-navigation a:focus,
				.continue-reading a:focus, 
				.continue-reading a:hover,
				.cat-links a:focus, 
				.cat-links a:hover,
				.reply a:hover, 
				.reply a:focus,
				.comment-form .form-submit input:hover, 
				.comment-form .form-submit input:focus {
					background-color: <?php echo esc_attr( $interactive_color ); ?>;
				}
				
				@media screen and (min-width: 900px) {
					.no-sidebar .post-content__wrap .entry-meta a:hover, 
					.no-sidebar .post-content__wrap .entry-meta a:focus {
						border-color: <?php echo esc_attr( $interactive_color ); ?>;
					}
				}
			</style>
		<?php
		} 		

	}
endif;