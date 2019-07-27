<?php

if (! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class AdminTable extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular'=> 'wp_list_text_link',
            'plural' => 'wp_list_test_links',
            'ajax'   => false
        ));
    }

    public function get_columns()
    {
        return $columns= array(
            'col_link_posts_total'=>__('Posts Total Likes'),
            'col_link_custom_posts_total'=>__('Custom Posts Total Likes'),
            'col_link_pages_total'=>__('Pages Total Likes'),
        );
    }

    public function get_total_counts()
    {
    }

    public function prepare_items()
    {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;
        $this->_column_headers = $this->get_column_info();
    }

    public function getCustomPostsLikes()
    {
        $query = "SELECT post_id, meta_value AS LIKES, POST_TITLE from {$pref}postmeta
            LEFT JOIN {$pref}posts  on {$pref}posts.ID = {$pref}postmeta.post_id
                where  meta_value = (
                    select MAX(meta_value) from  {$pref}postmeta where meta_key = 'likes'
                )
            and meta_key = 'likes'
            and {$pref}posts.post_type IN ({$custom_posts})";

        $the_max = $wpdb->get_row($query);
        return $the_max;
    }
    public function getPostsLikes()
    {
        $query = "SELECT post_id, meta_value AS LIKES, POST_TITLE from {$pref}postmeta
            LEFT JOIN {$pref}posts  on {$pref}posts.ID = {$pref}postmeta.post_id
                where  meta_value = (
                    select MAX(meta_value) from  {$pref}postmeta where meta_key = 'likes'
                )
            and meta_key = 'likes'
            and {$pref}posts.post_type = 'post'";

        $the_max_posts = $wpdb->get_row($query);
        return $the_max_posts;
    }
    public function getPagesLikes()
    {
        $query = "SELECT post_id, meta_value AS LIKES, POST_TITLE from {$pref}postmeta
            LEFT JOIN {$pref}posts  on {$pref}posts.ID = {$pref}postmeta.post_id
                where  meta_value = (
                    select MAX(meta_value) from  {$pref}postmeta where meta_key = 'likes'
                )
            and meta_key = 'likes'
            and {$pref}posts.post_type = 'page'";

        $the_max_pages = $wpdb->get_row($query);

        return $the_max_pages;
    }

    public function no_items()
    {
        _e('No data avaliable.', 'sp');
    }
}
