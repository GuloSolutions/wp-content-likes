=== WordPress Content Likes WordPress Plugin ===
Contributors: radboris, zwilson, fsimmons
Donate link: www.gulosolutions.com
Tags: likes, kpi, analytics, user activity 
Requires at least: 3.0.1
Tested up to: 5.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Track likes for WP published content in posts, pages, custom posts. 

== Installation ==

1. How to install: 

  -- Upload the zipped to the `/wp-content/plugins/` directory
  -- Install through `composer require`
  
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Choose what to track under Settings
4. Add the shortcode (`[wordpress-content-likes_like_button]`) to the block editor for a the post, plage or plugin
5. Alternatively, display the like button but `<?php echo do_shortcode([wordpress-content-likes_like_button]); ?>`


== Frequently Asked Questions ===

How 
* Add a short code to a post, pag or custom post.

`[wordpress-content-likes_like_button]`

== Changelog ==

1.0.0

* Record likes for post, pages, custom posts and display highest count for each category in widget and in a custom column in dashboard for each content type

