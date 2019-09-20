<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}

class AdminTable extends WP_List_Table
{
    public $item;

    public $total;

    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'wp_list_like_count',
            'plural' => 'wp_list_like_counts',
            'ajax' => false,
        ));
    }

    public function get_columns()
    {
        return $columns = array(
            'posts_total' => __('Posts Total Likes'),
            'custom_posts_total' => __('Custom Posts Total Likes'),
            'pages_total' => __('Pages Total Likes'),
            'total_likes' => __('Total Likes'),
        );
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'posts_total':
            case 'custom_posts_total':
            case 'pages_total':
            case 'total_likes':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        $per_page = $this->get_items_per_page('likes_per_page', 1);
        $current_page = $this->get_pagenum();

        $this->set_pagination_args([
            'per_page' => $per_page
          ]);

        $posts_total = QueryContent::getPostsLikes()->LIKES ?
            QueryContent::getPostsLikes()->LIKES : null;
        $custom_posts_total = isset(QueryContent::getCustomPostsLikes()->LIKES) ?
            QueryContent::getCustomPostsLikes()->LIKES : null;
        $pages_total = isset(QueryContent::getPagesLikes()->LIKES) ?
            QueryContent::getPagesLikes()->LIKES : null;
        $total_likes = $posts_total+$custom_posts_total+$pages_total;

        $data[] = array(
            'posts_total' => $posts_total,
            'custom_posts_total' => $custom_posts_total,
            'pages_total' => $pages_total,
            'total_likes' => $total_likes
        );

        $this->items = $data;

        return $this->items;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
          'posts_total' => array('Posts Total Likes', true),
          'custom_posts_total' => array('Custom Posts Total Likes', true),
          'pages_total' => array('Pages Total Likes', true),
          'total_likes' => array('Total', true),
        );

        return $sortable_columns;
    }

    public function getCustomPostsLikes()
    {
        global $wpdb;
        $pref = $wpdb->prefix;

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
        global $wpdb;
        $pref = $wpdb->prefix;

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
        global $wpdb;
        $pref = $wpdb->prefix;

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
