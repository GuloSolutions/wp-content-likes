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

    private $postid;

    private $id;

    private $vote;


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
        wp_enqueue_script($this->plugin_name. '-jquery', plugin_dir_url(__FILE__) . 'js/jquery.js', array( ), $this->version, false);

        //if (is_singular('post')) {
        wp_enqueue_script($this->plugin_name.'content_likes', plugin_dir_url(__FILE__) . 'js/wordpress-content-likes-public.js', array( $this->plugin_name. '-jquery' ), $this->version, true);
        wp_localize_script($this->plugin_name.'content_likes', 'ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
        //}
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
        $new_vote = $old_vote = 0;
        $this->postid = $_POST['postid'];
        $clickvote = $_POST['vote'];

        $ip = $this->_s_sl_get_ip();

        $ip = 'user_likes' .$ip;

        $stored = get_post_meta($this->postid, 'likes')[0];

                error_log(print_r('this is stored', true));
                error_log(print_r($stored, true));


        $old_vote = get_option($ip);

        if ($old_vote == false) {

        	if ($stored == 0 || $stored === false){
        		$stored = 1;
        	} else {
            	$stored++;
            }

            update_post_meta($this->postid, 'likes', $stored);
            update_option($ip, 1);
            echo json_encode($stored);
            wp_die();
        }

        $result = $stored;
        $result = (int)$result;

        if (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 2) {
            $result++;
            $new_vote = 1;
        } elseif (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 1) {
            $result--;
            $new_vote = 2;
        }

        if ($result < 0){
        	$result = 0;
        }

        update_option($ip, $new_vote);
        $saved = update_post_meta($this->postid, 'likes', $result);

        if (!$saved) {
            error_log(sprintf("The save for like from ip %s was not successful"), $ip);
        }

           error_log(print_r('this is result', true));
                        error_log(print_r($result, true));
        echo json_encode($result);

        wp_die();
    }

    public function _s_get_post_id()
    {
        $like_count = get_post_meta($this->id, 'likes');
    }

    public function _s_export_liked_count()
    {
        $like_count = get_post_meta($this->id, 'likes', true);
        $ip = $this->_s_sl_get_ip();

        $ip = 'user_likes' . $ip;

        $vote_cookie = get_option($ip);

        $vote_cookie ? $vote_cookie : 0;

        error_log(print_r('like count', true));

        error_log(print_r($like_count));


        if (isset($like_count) && is_singular('post')) {
            wp_localize_script($this->plugin_name.'content_likes', 'ajax_likes', ['like_count' => $like_count , 'vote_cookie' => $vote_cookie, 'ajaxurl' => admin_url('admin-ajax.php')]);
        }
    }

    public function _s_get_id()
    {
        global $post;
        $this->id = $post->ID;
    }

    public function _s_sl_get_ip()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }
        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;
        return $ip;
    }
}
