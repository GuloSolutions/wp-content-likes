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
        $this->_s_export_liked_count();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__).'/css/wordpress-content-likes-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
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
        global $wpdb;
        $new_vote = $old_vote = $result = $stored = 0;
        $sql = '';

        $this->user = sanitize_text_field($_POST['uniq']);
        $this->postid = sanitize_text_field($_POST['content_like_id']);

        $sql = "SELECT id, liked, post_id, post_hash FROM {$wpdb->prefix}wp_content_likes WHERE post_hash='{$this->user}'" ;
        $result = $wpdb->get_row($sql);

        if ($result->liked){
            $wpdb->query( $wpdb->prepare(
                "
                UPDATE {$wpdb->prefix}wp_content_likes
                SET LIKED = %d
                WHERE ID = %d
                ",
                    0, $result->id
            ));
        } else {
            $wpdb->query( $wpdb->prepare(
                "
                UPDATE {$wpdb->prefix}wp_content_likes
                SET LIKED = %d
                WHERE ID = %d
                ",
                    1, $result->id
            ));
        }

        $updated = "SELECT SUM(liked) as TOTAL FROM {$wpdb->prefix}wp_content_likes WHERE post_id={$result->post_id}";
        $total = $wpdb->get_row($updated);

        if (isset($total->TOTAL)) {
            echo json_encode($total->TOTAL);
            wp_die();
        } else {
            echo json_encode(0);
            wp_die();
        }
    }

    public function _s_export_liked_count()
    {
        global $post;
        global $wpdb;

        if (!is_admin()) {

            $sum = "SELECT liked as VOTED, SUM(liked) as TOTAL FROM {$wpdb->prefix}wp_content_likes WHERE post_id='{$post->ID}'";

            $search = $wpdb->get_row($sum);

            $this->like_count = $search->TOTAL;

            $this->vote_cookie = $search->VOTED;

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
