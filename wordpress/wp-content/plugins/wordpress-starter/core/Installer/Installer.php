<?php
namespace SiteGround_Central\Installer;

/**
 * Installer functions and main initialization class.
 */
class Installer {

	/**
	 * Removes item from instalation queue.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $id The item id.
	 */
	public static function remove_from_queue( $id ) {
		$queue = get_option( 'siteground_wizard_installation_queue', array() );

		if ( empty( $queue ) ) {
			return;
		}

		$key = array_search( $id, array_column( $queue, 'id' ) );

		if ( empty( $key ) ) {
			return;
		}

		unset( $queue[ $key ] );

		update_option( 'siteground_wizard_installation_queue', array_values( $queue ) );
	}

	/**
	 * Complete the installation
	 *
	 * @since  1.0.0
	 *
	 * @param  object $request Request data.
	 */
	public function complete( $request ) {
		// Get the errors.
		$errors = get_option( 'siteground_wizard_installation_errors', array() );

		// Update the status.
		$callback = ! \is_multisite() ? 'update_option' : 'update_site_option';

		call_user_func(
			$callback,
			'siteground_wizard_installation_status',
			array(
				'status' => 'completed',
				'errors' => $errors,
			)
		);

		// Reset the errors.
		delete_option( 'siteground_wizard_installation_errors' );

		$this->configure_other_plugins();

		// Skip Oceanwp theme redirect.
		$nonce     = wp_create_nonce( 'oceanwp-theme_skip_activation' );
		$admin_url = admin_url( 'admin.php?fs_action=oceanwp-theme_skip_activation&page=oceanwp-panel&_wpnonce=' . $nonce );
		$response  = wp_remote_get( $admin_url );
		wp_send_json_success();
	}

	/**
	 * Install plugin from the custom dashboard.
	 *
	 * @since  1.0.0
	 */
	public static function install_from_dashboard( $activate = true ) {
		if ( ! wp_verify_nonce( $_GET['nonce'], $_GET['plugin'] ) ) {
			die( __( 'Security check', 'siteground-wizard' ) );
		}

		// Execute the installation command.
		exec(
			sprintf(
				'wp plugin install %s %s',
				escapeshellarg( $_GET['plugin'] ),
				true === $activate ? '--activate' : ''
			),
			$output,
			$status
		);

		wp_clean_plugins_cache();

		// Check for errors.
		if ( ! empty( $status ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Install plugin from the custom dashboard.
	 *
	 * @since  1.0.0
	 */
	public static function activate_from_dashboard() {
		if ( ! wp_verify_nonce( $_GET['nonce'], $_GET['plugin'] ) ) {
			die( __( 'Security check', 'siteground-wizard' ) );
		}

		// Execute the installation command.
		exec(
			sprintf(
				'wp plugin activate %s',
				escapeshellarg( $_GET['plugin'] )
			),
			$output,
			$status
		);

		wp_clean_plugins_cache();

		// Check for errors.
		if ( ! empty( $status ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Install plugins/themes method
	 *
	 * @since  1.0.0
	 *
	 * @param  object $request Request data.
	 */
	public function install( $request ) {

		// Get the current errors if any.
		$errors = get_option( 'siteground_wizard_installation_errors', array() );

		// Remove the item from the queue.
		$this->remove_from_queue( $request['id'] );

		add_filter( 'woocommerce_create_pages', function() {
			return array();
		} );

		// Remove theme install from EDD version of the starter.
		if (
			1 === intval( get_option( 'sg_wp_starter_edd' ) ) &&
			'theme' === $request['type']
		) {
			wp_send_json_success();
		}

		// Execute the installation command.
		exec(
			sprintf(
				'wp %s install %s --activate --skip-packages',
				escapeshellarg( $request['type'] ),
				! empty( $request['download_url'] ) ? escapeshellarg( $request['download_url'] ) : escapeshellarg( $request['slug'] )
			),
			$output,
			$status
		);

		// Check for errors.
		if ( ! empty( $status ) ) {
			$errors[] = sprintf( 'Cannot install %1$s: %2$s', $request['type'], $request['slug'] );
			// Add the error.
			update_option( 'siteground_wizard_installation_errors', $errors );
			wp_send_json_error();
		}

		// Add the option for the specific theme to the database, so we are sure that it is installed trough the Wizard or Recommended page.
		if (
			'theme' === $request['type'] &&
			'astra' === $request['slug']

		) {
			update_option( 'siteground_wizard_installed_astra_theme', 1 );
		}

		wp_send_json_success();
	}

	/**
	 * Configure the options for other plugins.
	 *
	 * @since  1.0.0
	 */
	public function configure_other_plugins() {
		$options = array(
			'enable_cache',
			'autoflush_cache',
			'optimize_html',
			'optimize_javascript',
			'optimize_javascript_async',
			'optimize_css',
			'combine_css',
			'combine_google_fonts',
			'disable_emojis',
			'lazyload_images',
		);

		foreach ( $options as $option ) {
			update_option( 'siteground_optimizer_' . $option, 1 );
		}

		update_option( 'siteground_optimizer_excluded_lazy_load_media_types', array( 'lazyload_shortcodes' ) );

		$transients = array(
			'fs_plugin_foogallery_activated',
			'fs_theme_oceanwp_activated',
			'fs_plugin_ocean-posts-slider_activated',
			'fs_plugin_the-events-calendar_activated',
		);

		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}

		// Remove the AIOSEO redirect.
		delete_option( '_aioseo_cache_activation_redirect' );
		delete_option( '_aioseo_cache_expiration_activation_redirect' );
		update_option( 'themeisle_blocks_settings_redirect', 0 );
		update_option( 'aioseo_activation_redirect', true );

		// Remove Optin Monster transient for wizard and add skip option.
		update_option( 'optin_monster_api_activation_redirect_disabled', true );
		delete_transient( 'optin_monster_api_activation_redirect' );

		// Remove MonsterInsights transient for wizard.
		delete_transient( '_monsterinsights_activation_redirect' );

		// Remove Calendar redirect.
		delete_option( 'mec_activation_redirect' );

		// Flushing caches after modifying options.
		wp_cache_flush();

	}
}
