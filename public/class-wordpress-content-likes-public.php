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
        wp_localize_script($this->plugin_name.'content_likes', 'ajax_object', ['like_count', 'vote_cookie', 'ajaxurl' => admin_url('admin-ajax.php')]);
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
        $this->postid = $_POST['postid'];

        $cookie = $_POST['voted'];

        $clickvote = $_POST['vote'];

        $newvote = $_POST['newvote'];

        $vote = substr($cookie, 25).$this->postid;

        $stored = get_post_meta($this->postid, 'likes')[0];

        error_log(print_r("this is stored", true));
        error_log(print_r($stored, true));

        if (!$stored ) {
        	add_post_meta( $this->postid, 'likes', 1 );
        	echo json_encode($result);
        	wp_die();
        }

        $old_vote = get_option($vote);

        $result = $stored[0];
         $result = (int)$result;

        if (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 2) {
            $result[0]++;
        } elseif (filter_var($result, FILTER_VALIDATE_INT) !== false && $old_vote == 1) {
            $result[0]--;
        }

        $cookie = substr($cookie, 25);
        $cookie = $cookie.$this->postid;

        update_option($vote, $newvote);

        error_log(print_r($result, true));

        $saved = update_post_meta($this->postid, 'likes', $result);
        error_log(print_r($saved, true));
        echo json_encode($result);

        wp_die();
    }

    public function _s_get_post_id() {

    	$like_count = get_post_meta($this->id,'likes');
    	//error_log(print_r($like_count, true));

    }

    public function _s_export_liked_count()
	{
		$like_count = get_post_meta($this->id,'likes', true);
		 error_log(print_r($like_count, true));
		if (isset($like_count)) {
	        ?>
	        <script type="text/javascript">
	        // /* <![CDATA[ */
	            ajax_object.like_count = <?php echo $like_count; ?>;
	        // /* ]]> */
	        //   </script>
	        <?php
	    }

	    	if (isset($vote)) {
	        ?>
	        <script type="text/javascript">
	        // /* <![CDATA[ */
	            ajax_object.vote_cookie = <?php echo $vote_cookie; ?>;
	        // /* ]]> */
	        //   </script>
	        <?php
	    }

	}
}
