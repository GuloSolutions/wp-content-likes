=== Plugin Name ===
Contributors: radboris, zwilson, fsimmons
Donate link: www.gulosolutions.com
Tags: likes, kpi, analytics, user activity 
Requires at least: 3.0.1
Tested up to: 5.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==

Track likes for WP published content in posts, pages, custom posts. 

== Installation ==

1. How to install: 

  -- Upload the zipped to the `/wp-content/plugins/` directory
  -- Install through `composer require`
  
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ===

* Add a short code to a post, pag or custom post .

`[wordpress-content-likes_like_button]`

== Changelog ==

1.0.0

* Record likes for post, pages, custom posts and display highest count for each category in widget and in a custom column in dashboard for each content type

