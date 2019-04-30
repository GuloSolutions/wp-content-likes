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
            $content = "The highest rated blogs are: ";
            echo $content;
        }
    }

    public function wpdocs_register_meta_boxes()
    {
        add_meta_box("post-like-meta-box", __('Likes for this post', 'textdomain'), 'wpdocs_my_display_callback', 'post', 'side', 'high', null);
        function wpdocs_my_display_callback($post)
        {
            $num_likes = get_post_meta($post->ID, 'likes', true);
            $content = '<div>' . $num_likes . '</div>';
            echo $content;
        }
    }

    public function wpdocs_register_meta_boxes_pages()
    {
        add_meta_box('page-like-meta-box', __('Likes for this page', 'textdomain'), 'wpdocs_my_display_callback_page', 'page', 'side', 'high', null);
        function wpdocs_my_display_callback_page($page)
        {
            $num_likes = get_post_meta($page->ID, 'likes', true);
            $content = '<div>'. $num_likes . '</div>';
            echo $content;
        }
    }

    public function wpdocs_register_meta_boxes_custom_post()
    {
        // Global object containing current admin page
        global $pagenow;
        $custom_post_id = $_GET['post'];

        $args = array(
           'public'   => true,
           '_builtin' => false
        );

        $post_types = get_post_types($args);

        foreach ($post_types as $post_type) {
            add_meta_box('custom-post-likes-meta-box', __('Likes for this custom post type', 'textdomain'), 'wpdocs_my_display_callback_custom_post', $post_type, 'side', 'high', null);
        }

        function wpdocs_my_display_callback_custom_post($custom_post_id)
        {
            $num_likes = get_post_meta($custom_post_id, 'likes', true);
            $content = '<div>' . $num_likes . '</div>';
            echo $content;
        }
    }
}
