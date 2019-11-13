<?php

class Wordpress_Content_Likes_Table_Activator
{
    const TABLE_NAME='content_likesdata';

    public static function activate()
    {
        global $wpdb;
        global $wp_content_likes_db_version;

        $wp_content_likes_db_version = '1.2';
        $table_name = $wpdb->prefix.'content_likesdata';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9),
			post_hash varchar(128) DEFAULT '',
            vote_cookie tinyint(1) DEFAULT 0,
            ip_addr varchar(60) DEFAULT 0,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');

        dbDelta($sql);

        add_option('wp_content_likes_db_version', $wp_content_likes_db_version);
    }

    public static function install_table_data()
    {
        global $wpdb;

        $sql_migrate = $sql_drop = '';

        $table_name = $wpdb->prefix.self::TABLE_NAME;
        $old_table_name=$wpdb->prefix.'wp_content_likes';
        $sql_check = "
            SELECT count(*) as found
            FROM information_schema.TABLES
                WHERE (TABLE_SCHEMA = '$wpdb->dbname') AND (TABLE_NAME = '$old_table_name');";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $res = $wpdb->get_results($sql_check);

        if (intval($res) >= 1) {
            $sql_migrate = "INSERT INTO $table_name SELECT * FROM $old_table_name;";
            $sql_drop = "DROP TABLE $old_table_name;";
        }

        dbDelta($sql_migrate);

        dbDelta($sql_migrate);
    }
}
