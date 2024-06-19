<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://kitxune.com
 * @since      1.0.0
 *
 * @package    Kitxune_Inmotek_Connector
 * @subpackage Kitxune_Inmotek_Connector/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Kitxune_Inmotek_Connector
 * @subpackage Kitxune_Inmotek_Connector/includes
 * @author     Kitxune Studio S.L <web@kitxune.com>
 */
class Kitxune_Inmotek_Connector_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kitxune-inmotek-connector',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
