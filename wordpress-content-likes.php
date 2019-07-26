<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.gulosolutions.com
 * @since             1.0.0
 * @package           Wordpress_Content_Likes
 *
 * @wordpress-plugin
 * Plugin Name:       WP Content Likes
 * Plugin URI:        https://www.gulosolutions.com/?utm_source=wp-admin&utm_medium=wp-plugin&utm_campaign=wp-content-likes
 * Description:       Track likes for different types of WP content.
 * Version:           1.0.13
 * Author:            Gulo Solutions, LLC
 * Author URI:        www.gulosolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-content-likes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WORDPRESS_CONTENT_LIKES_VERSION', '1.0.13');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-content-likes-activator.php
 */
function activate_wordpress_content_likes()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes-activator.php';
    Wordpress_Content_Likes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-content-likes-deactivator.php
 */
function deactivate_wordpress_content_likes()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes-deactivator.php';
    Wordpress_Content_Likes_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wordpress_content_likes');
register_deactivation_hook(__FILE__, 'deactivate_wordpress_content_likes');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes.php';
require plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes-admin-settings.php';
require plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes-admin-widget.php';
require plugin_dir_path(__FILE__) . 'includes/class-wordpress-content-likes-admin-table.php';
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordpress_content_likes()
{
    $plugin_name = get_plugin_data(__FILE__, $markup = true, $translate = true)['Name'];

    // load settings
    if (is_admin() && !is_null($plugin_name)) {
        $my_settings_page = new Wordpress_Content_Likes_Admin_Settings($plugin_name);
    }

    $plugin = new Wordpress_Content_Likes();
    $plugin->run();
}
run_wordpress_content_likes();
