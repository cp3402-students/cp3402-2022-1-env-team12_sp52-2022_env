<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );
function add_elementor_widget_categories( $elements_manager ) {
	$elements_manager->add_category(
		'the-events-calendar-templates',
		[
			'title' => esc_html__( 'Events Shortcodes and Templates Addon', 'ect2' ),
    		'icon' => 'fa fa-header', //default icon
		]
	);
}
/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class EctElementor {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->ect_add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function ect_add_actions() {
		add_action( 'elementor/widgets/widgets_registered', array($this, 'ect_on_widgets_registered' ));

	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function ect_on_widgets_registered() {
		$this->ect_includes();
		$this->ect_register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function ect_includes() {
		require __DIR__ . '/ect-elementor-shortcode.php';
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function ect_register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new EctElementorWidget() );
	}
}

 new EctElementor();