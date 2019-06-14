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
     * The status of tracked posts.
     *
     * @since    1.0.0
     * @access   private
     * @var      boolean    $version    The current state of the plugin .
     */
    private $tracking_posts;


    /**
     * The status of tracked pages.
     *
     * @since    1.0.0
     * @access   private
     * @var      boolean    $version    The current state of the plugin .
     */
    private $tracking_pages;

    /**
     * The status of tracked pages.
     *
     * @since    1.0.0
     * @access   private
     * @var      boolean    $version    The current state of the plugin .
     */
    private $tracking_custom_posts;

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
        $this->check_tracking_on();
        $this->_s_add_settings_link();
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

        wp_enqueue_script($this->plugin_name.'content_delete_likes', plugin_dir_url(__FILE__) . '/js/wordpress-content-likes-admin.js', array( 'jquery' ), $this->version, false);
        wp_localize_script($this->plugin_name.'content_delete_likes', 'ajax_wp_content_likes', ['ajaxurl' => admin_url('admin-ajax.php')]);
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

            if ($this->tracking_posts) {
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

            if ($this->tracking_pages) {
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

            if ($this->tracking_custom_posts) {
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

    public function wpdocs_register_meta_boxes()
    {
        if ($this->tracking_posts) {
            add_meta_box("post-like-meta-box", __('Likes for this post', 'textdomain'), 'wpdocs_my_display_callback', 'post', 'side', 'high', null);
            function wpdocs_my_display_callback($post)
            {
                $num_likes = get_post_meta($post->ID, 'likes', true);
                $content = '<div>' . $num_likes . '</div>';
                echo $content;
            }
        }
    }

    public function wpdocs_register_meta_boxes_pages()
    {
        if ($this->tracking_pages) {
            add_meta_box('page-like-meta-box', __('Likes for this page', 'textdomain'), 'wpdocs_my_display_callback_page', 'page', 'side', 'high', null);
            function wpdocs_my_display_callback_page($page)
            {
                $num_likes = get_post_meta($page->ID, 'likes', true);
                $content = '<div>'. $num_likes . '</div>';
                echo $content;
            }
        }
    }

    public function wpdocs_register_meta_boxes_custom_post()
    {
        if ($this->tracking_custom_posts) {
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
        if ($this->tracking_posts) {
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
    }

    public function likes_pages_custom_column()
    {
        if ($this->tracking_pages) {
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
    }

    public function get_the_posts()
    {
        return get_post_types(array('post_type', 'post'));
    }

    public function check_tracking_on()
    {
        if (isset(get_option('wp_content_likes_option_name')['track_posts']) && get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
            if (get_option('wp_content_likes_option_name')['track_posts'] == 'on') {
                $this->tracking_posts = true;
            }
        }

        if (isset(get_option('wp_content_likes_option_name')['track_pages']) && get_option('wp_content_likes_option_name')['track_pages'] != 'on') {
            if (get_option('wp_content_likes_option_name')['track_pages'] != 'on') {
                $this->tracking_pages = true;
            }
        }

        if (isset(get_option('wp_content_likes_option_name')['track_custom_posts']) && get_option('wp_content_likes_option_name')['track_custom_posts'] == 'on') {
            if (get_option('wp_content_likes_option_name')['track_custom_posts'] == 'on') {
                $this->tracking_custom_posts = true;
            }
        }
    }

    public function _s_delete_button_handler()
    {
        global $wpdb;

        if (isset($_POST['delete_button_id'])) {
            
            delete_post_meta_by_key('likes');
            delete_option( 'wp_content_likes_option_name');
                    
            $ip_related_options = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'user_likes%'");
            foreach ($ip_related_options as $option) {
                delete_option($option->option_name);
            }
    
            wp_die();
        }
    }

    public function _s_add_settings_link() {
        add_filter( "plugin_action_links", 'plugin_add_settings_link' );
        function plugin_add_settings_link( $links ) {
            $settings_link = 
                '<a href="' . admin_url( 'options-general.php?page=wp_content_likes' ) . '">Settings</a>';
            array_push( $links, $settings_link );
            return $links;
        }
    }
}  


  