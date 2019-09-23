<?php

class Wordpress_Content_Likes_Table_Activator {

	public static function activate() {
		global $wpdb;
		global $wp_content_likes_db_version;

		$wp_content_likes_db_version = '1.0';

		$table_name = $wpdb->prefix . 'wp_content_likes';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			post_id mediumint(9) NOT NULL,
			post_hash varchar(128) NOT NULL DEFAULT '',
			liked tinyint(1) DEFAULT 0,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'wp_content_likes_db_version', $wp_content_likes_db_version );
	}

	public static function install_table_data() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'wp_content_likes';
		// $wpdb->insert(
		// 	$table_name,
		// 	array(

		// 	)
		// );
	}
}