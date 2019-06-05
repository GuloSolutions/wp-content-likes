<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.gulosolutions.com
 * @since      1.0.0
 *
 * @package    Wordpress_Content_Likes
 * @subpackage Wordpress_Content_Likes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wordpress_Content_Likes
 * @subpackage Wordpress_Content_Likes/admin
 * @author     Gulo Solutions <rad@gulosolutions.com>
 */
class Wordpress_Content_Likes_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        global $post;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wordpress_Content_Likes_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wordpress_Content_Likes_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wordpress-content-likes-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wordpress_Content_Likes_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wordpress_Content_Likes_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wordpress-content-likes-admin.js', array( 'jquery' ), $this->version, false);
    }

    public function wordpress_content_likes_widget()
    {
        wp_add_dashboard_widget(
                 'wordpress_likes_dashboard_widget',         // Widget slug.
                 'WordPress Content Likes Widget',         // Title.
                 'wordpress_content_likes_dashboard_widget_function' // Display function.
        );

        function wordpress_content_likes_dashboard_widget_function()
        {
            global $wpdb;
            $pref = $wpdb->prefix;
            $pages=$posts=$custom_posts='';
            $content1=$content2=$content3='';

            if (isset(get_option('wp_content_likes_option_name')['track_posts']) && get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
                if (get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
                    $query = "SELECT post_id, meta_value AS LIKES, POST_TITLE from {$pref}postmeta
                        LEFT JOIN {$pref}posts  on {$pref}posts.ID = {$pref}postmeta.post_id
                            where  meta_value = (
                                select MAX(meta_value) from  {$pref}postmeta where meta_key = 'likes'
                            )
                        and meta_key = 'likes'
                        and {$pref}posts.post_type = 'post'";

                    $the_max_posts = $wpdb->get_row($query);

                    if ($the_max_posts && isset($the_max_posts->LIKES) && isset($the_max_posts->POST_TITLE)) {
                        $content1 = sprintf("<p>The highest rated blog --  %s -- has %d likes </p>", $the_max_posts->POST_TITLE, $the_max_posts->LIKES);
                        echo $content1;
                    }
                }
            }

            if (isset(get_option('wp_content_likes_option_name')['track_pages'])) {
                if (get_option('wp_content_likes_option_name')['track_pages'] == 'on') {
                    $query = "SELECT post_id, meta_value AS LIKES, POST_TITLE from {$pref}postmeta
                        LEFT JOIN {$pref}posts  on {$pref}posts.ID = {$pref}postmeta.post_id
                            where  meta_value = (
                                select MAX(meta_value) from  {$pref}postmeta where meta_key = 'likes'
                            )
                        and meta_key = 'likes'
                        and {$pref}posts.post_type = 'page'";

                    $the_max_pages = $wpdb->get_row($query);

                    if ($the_max_pages && isset($the_max_pages->LIKES) && isset($the_max_pages->POST_TITLE)) {
                        $content2 = sprintf("<p>The highest rated page %s has %d likes </p>", $the_max_pages->POST_TITLE, $the_max->LIKES);
                        echo $content2;
                    }
                }
            }

            if (isset(get_option('wp_content_likes_option_name')['track_custom_posts'])) {
                if (get_option('wp_content_likes_option_name')['track_custom_posts'] == 'on') {
                    $args = array(
                       'public'   => true,
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

                    if ($the_max && isset($the_max->LIKES) && isset($the_max->POST_TITLE)) {
                        $content3 = sprintf("<p>The highest rated custom post -- %s -- has %d likes </p>", $the_max->POST_TITLE, $the_max->LIKES);
                        echo $content3;
                    }
                }
            }
        }
    }

    public function wpdocs_register_meta_boxes()
    {
        if (isset(get_option('wp_content_likes_option_name')['track_posts']) && get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
            if (get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
                add_meta_box("post-like-meta-box", __('Likes for this post', 'textdomain'), 'wpdocs_my_display_callback', 'post', 'side', 'high', null);
                function wpdocs_my_display_callback($post)
                {
                    $num_likes = get_post_meta($post->ID, 'likes', true);
                    $content = '<div>' . $num_likes . '</div>';
                    echo $content;
                }
            }
        }
    }

    public function wpdocs_register_meta_boxes_pages()
    {
        if (isset(get_option('wp_content_likes_option_name')['track_pages']) && get_option('wp_content_likes_option_name')['track_pages'] == 'on') {
            if (get_option('wp_content_likes_option_name')['track_pages'] == 'on') {
                add_meta_box('page-like-meta-box', __('Likes for this page', 'textdomain'), 'wpdocs_my_display_callback_page', 'page', 'side', 'high', null);
                function wpdocs_my_display_callback_page($page)
                {
                    $num_likes = get_post_meta($page->ID, 'likes', true);
                    $content = '<div>'. $num_likes . '</div>';
                    echo $content;
                }
            }
        }
    }

    public function wpdocs_register_meta_boxes_custom_post()
    {
        if (isset(get_option('wp_content_likes_option_name')['track_custom_posts'])) {
            if (get_option('wp_content_likes_option_name')['track_custom_posts'] == 'on') {

                // Global object containing current admin page
                global $pagenow;
                $args = array(
                       'public'   => true,
                       '_builtin' => false
                    );

                $post_types = get_post_types($args);

                foreach ($post_types as $post_type) {
                    add_meta_box('custom-post-likes-meta-box', __('Likes for this custom post type', 'textdomain'), 'wpdocs_my_display_callback_custom_post', $post_type, 'side', 'high', null);
                }

                function wpdocs_my_display_callback_custom_post()
                {
                    $custom_post_id = sanitize_text_field($_GET['post']);
                    $num_likes = get_post_meta($custom_post_id, 'likes', true);
                    $content = '<div>' . $num_likes . '</div>';
                    echo $content;
                }
            }
        }
    }

    public function likes_filter_posts_columns($columns)
    {
        $columns['likes'] = __('Likes');
        return $columns;
    }

    public function likes_filter_pages_columns($columns)
    {
        $columns['likes'] = __('Likes');
        return $columns;
    }

    public function wordpress_content_likes_custom_column()
    {
        add_action('manage_posts_custom_column', 'likes_custom_column', 10, 2);

        function likes_custom_column($column, $post_id)
        {
            if ('likes' == $column) {
                if (get_post_meta($post_id, 'likes', true)) {
                    echo get_post_meta($post_id, 'likes', true);
                }
            }
        }
    }

    public function likes_pages_custom_column()
    {
        add_action('manage_pages_custom_column', 'pagelikes__custom_column', 10, 2);

        function pagelikes__custom_column($column, $post_id)
        {
            if ('likes' == $column) {
                if (get_post_meta($post_id, 'likes', true)) {
                    echo get_post_meta($post_id, 'likes', true);
                }
            }
        }
    }

    public function get_the_posts()
    {
        return get_post_types(array('post_type', 'post'));
    }
}
