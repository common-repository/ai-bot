<?php
/**
 * Plugin Name: AI Bot
 * Description: Create engaging content with our WordPress AI writer. Our AI writer can help you create content for your WordPress site in seconds.
 * Version:     1.2.2
 * Author:      wpaibot.com
 * Author URI:  https://wpaibot.com
 * License:     GPL-2.0+ or Artistic License 2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 6.0
 * Requires PHP: 7.0
 * Tested up to: 6.6.2
 */

define( 'AIBOT_PLUGIN_FILE', plugin_basename(__FILE__) );

// Enqueue editor UI scripts & styles.
require_once( __DIR__ . '/inc/asset-loader.php' );
require_once( __DIR__ . '/inc/scripts.php' );
require_once( __DIR__ . '/inc/settings.php' );
AIBOT\Scripts\setup();
AIBOT\Settings\setup();

