<?php
/**
 * Register scripts in development and production.
 */
namespace AIBOT\Scripts;

use AIBOT\Asset_Loader;

function setup() {
	add_action( 'init', __NAMESPACE__ . '\\register_translations' );
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets' );
}

function register_translations() {
	wp_set_script_translations( 'ai-bot', 'ai-bot', plugin_dir_path( __FILE__ ) . 'languages' );
}

/**
 * Enqueue editor assets based on the generated `asset-manifest.json` file.
 */
function enqueue_block_editor_assets() {
	$plugin_path  = trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	$plugin_url   = trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) );
	$dev_manifest = $plugin_path . 'build/asset-manifest.json';

	$opts = [
		'handle' => 'ai-bot',
		'scripts' => [
			'wp-blocks',
			'wp-data',
			'wp-edit-post',
			'wp-element',
			'wp-i18n',
			'wp-plugins',
		],
	];

	$loaded_dev_assets = Asset_Loader\enqueue_assets( $dev_manifest, $opts );

	if ( ! $loaded_dev_assets ) {
		// Production mode. Manually enqueue script bundles.
		if ( file_exists( $plugin_path . 'build/editor.js' ) ) {
			$plugin_data = get_option( 'ai_bot_settings_option_name' );
			$plugin_data['settings_page'] = admin_url( 'options-general.php?page=aibot-settings' );

			wp_enqueue_script(
				$opts['handle'],
				$plugin_url . 'build/editor.js',
				$opts['scripts'],
				filemtime( $plugin_path . 'build/editor.js' ),
				true
			);
			wp_localize_script( $opts['handle'], 'aibot', $plugin_data );
		}
		// TODO: Error if file is not found.

		if ( file_exists( $plugin_path . 'build/editor.css' ) ) {
			wp_enqueue_style(
				$opts['handle'],
				$plugin_url . 'build/editor.css',
				null,
				filemtime( $plugin_path . 'build/editor.css' )
			);
		}
	}
}
