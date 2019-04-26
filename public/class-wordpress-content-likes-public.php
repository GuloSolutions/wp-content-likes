<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.gulosolutions.com
 * @since      1.0.0
 *
 * @package    Wordpress_Content_Likes
 * @subpackage Wordpress_Content_Likes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wordpress_Content_Likes
 * @subpackage Wordpress_Content_Likes/public
 * @author     Gulo Solutions <rad@gulosolutions.com>
 */
class Wordpress_Content_Likes_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->register_like_shortcode();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wordpress-content-likes-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name. '-jquery', plugin_dir_url(__FILE__) . 'js/jquery.js', array( ), $this->version);
        wp_enqueue_script($this->plugin_name.'content_likes', plugin_dir_url(__FILE__) . 'js/wordpress-content-likes-public.js', array( $this->plugin_name. '-jquery' ), $this->version, false);
        wp_localize_script($this->plugin_name.'content_likes', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public function register_like_shortcode()
    {
        add_shortcode($this->plugin_name . '_like_button', array($this, 'print_like_button'));
    }

    public function print_like_button()
    {
    	return _s_like_button();
    }

    public function _s_likebtn__handler()
    {
        $postid = $_POST['postid'];

        error_log(print_r("before postid", true));

        // $cookie = $_POST['voted'];
        // $clickvote = $_POST['vote'];
        // $newvote = $_POST['newvote'];
        // $blog = '_s_like_post_'.$postid;
        // $vote = substr($cookie, 25).$postid;
        // $result = get_post_meta($postid, 'likes');
        // $old_vote = get_option($vote);
        // error_log(print_r("before result", true), 3, '/tmp/error.log');
        // error_log(print_r($result, true), 3, '/tmp/error.log');

        // if (!$result[0]) {
        //     update_post_meta($id, 'likes', 1);
        //     echo json_encode(1);
        //     wp_die();
        // }

        // if (filter_var($result[0], FILTER_VALIDATE_INT) !== false && $old_vote == 2) {
        //     $result++;
        // } elseif (filter_var($result[0], FILTER_VALIDATE_INT) !== false && $old_vote == 1) {
        //     $result--;
        // }

        // $cookie = substr($cookie, 25);
        // $cookie = $cookie.$postid;

        // update_option($blog, (string)$result);
        // update_option($vote, $newvote);

        // echo json_encode($result[0]);

        // update_post_meta($id, 'likes', $result);

        wp_die();
    }
}
