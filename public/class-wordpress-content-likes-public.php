<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @see       www.gulosolutions.com
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     Gulo Solutions <rad@gulosolutions.com>
 */
class Wordpress_Content_Likes_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the ID of this plugin
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of this plugin
     */
    private $version;

    private $postid;

    private $id;

    private $page_id;

    private $vote;

    private $user;

    /**
     * The count for each item tracked.
     *
     * @since    1.0.0
     *
     * @var int
     */
    public $like_count;

    /**
     * The ip content ID combinaiton for each item tracked.
     *
     * @since    1.0.0
     *
     * @var int
     */
    public $vote_cookie;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $plugin_name the name of the plugin
     * @param string $version     the version of this plugin
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->register_like_shortcode();
        $this->register_custom_hook();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /*
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'/css/wordpress-content-likes-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /*
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

        wp_enqueue_script($this->plugin_name.'content_likes', plugin_dir_url(__FILE__).'/js/_likesfrontend.js', array('jquery'), $this->version, true);
        wp_localize_script($this->plugin_name.'content_likes', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public function register_like_shortcode()
    {
        add_shortcode($this->plugin_name.'_like_button', array($this, 'print_like_button'));
    }

    public function register_custom_hook()
    {
        function wp_content_likes_button()
        {
            return _s_like_button();
        }
        do_action('print_like_button');
    }

    public function print_like_button()
    {
        return _s_like_button();
    }

    public function _s_likebtn__handler()
    {
        $new_vote = $old_vote = $result = $stored = 0;
        $this->user = sanitize_text_field($_POST['uniq']);
        $this->postid = sanitize_text_field($_POST['content_like_id']);
        $ip = $this->_s_sl_get_ip();
        $ip = $this->postid.$this->user.$ip;

        error_log(print_r('handler', true));


        error_log(print_r($ip, true));


        $stored = get_post_meta($this->postid, 'likes', true);

        $old_vote = get_option($ip);

        if (!$old_vote && !isset($stored)) {
            $result = 1;
            $stored = 1;
            // if key does not exist
            $update_response = update_post_meta($this->postid, 'likes', $stored);
            if (!is_numeric($update_response)) {
                add_post_meta($this->postid, 'likes', $stored);
            }
            update_option($ip, 1);
            echo json_encode($result);
            wp_die();
        }
        if (!$old_vote && $stored >= 0) {
            $stored++;
            $result = $stored;
            update_post_meta($this->postid, 'likes', $stored);
            update_option($ip, 1);
            echo json_encode($result);
            wp_die();
        }
        if (isset($stored)) {
            $result = $stored;
        }

        if (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 2) {
            $result++;
            $new_vote = 1;
        } elseif (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 1) {
            $result--;
            $new_vote = 2;
        }

        if ($result < 0) {
            $result = 0;
        }

        update_option($ip, $new_vote);
        update_post_meta($this->postid, 'likes', $result);

        echo json_encode($result);
        wp_die();
    }

    public function _s_export_liked_count()
    {
        global $post;

        if (!is_admin()) {
            $this->like_count = get_post_meta($post->ID, 'likes', true);

            $ip = $this->_s_sl_get_ip();
            $ip = $post->ID.$this->user.$ip;

            error_log(print_r('export', true));


            error_log(print_r($ip, true));

            $this->vote_cookie = get_option($ip);
            wp_localize_script($this->plugin_name.'content_likes', 'ajax_data', ['like_count' => $this->like_count, 'vote_cookie' => $this->vote_cookie, 'ajaxurl' => admin_url('admin-ajax.php')]);
        }
    }

    public function _s_sl_get_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;

        return $ip;
    }
}
