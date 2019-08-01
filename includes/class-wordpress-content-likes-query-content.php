<?php

class QueryContent
{
    public static function getPostsLikes()
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

    public static function getCustomPostsLikes()
    {
        global $wpdb;
        $pref = $wpdb->prefix;

        $args = array(
            'public' => true,
            '_builtin' => false,
         );

        $output = 'names';
        $operator = 'and';

        $post_types = get_post_types($args, $output, $operator);

        $custom_posts = implode("','", $post_types);
        $custom_posts = "'".$custom_posts."'";

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

    public static function getPagesLikes()
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
}
