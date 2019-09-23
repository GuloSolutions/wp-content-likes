
<?php

class CreateWPLikesTable {
	public static function wp_content_likes_install() {
		global $wpdb;
		global $wp_content_likes_db_version;
		global $wp_content_likes_db_version;

		$wp_content_likes_db_version = '1.0';

		$table_name = $wpdb->prefix . 'wp_content_likes';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			post_id mediumint(9) NOT NULL,
			likes mediumint(9),
			post_hash varchar(128) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'wp_content_likes_db_version', $wp_content_likes_db_version );
	}

	public static function wp_content_likes_install_data() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'wp_content_likes';

		$wpdb->insert(
			$table_name,
			array(

			)
		);
	}
}