<?php
/**
 * Plugin Name: Gosign - Text with Image Block
 * Plugin URI: https://www.gosign.de/
 * Description: Gosign - Text with Image Block — is a Gutenberg plugin created by Gosign. This plugin contains Text with Image Block that shows image with different aligment options in text.
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/team/
 * Version: 2.0.1
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl.txt
 *
 * @package Gosign
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
