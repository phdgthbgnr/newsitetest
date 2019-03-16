<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/public
 * @author     Your Name <email@example.com>
 */
class WQRecaptcha_Public
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
     *
     * Store obect from DB
     *
     */
    private $options_settings;
    private $secretkey = '';
    public $sitekey = '';
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     *
     */

    /**
     *  shortcode
     */
    private $shortcode = 'wqrecaptchav3';

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        /**
         *
         * retrieve obect from DB
         * table : (wp)_options
         * option_name : wqrecaptcha
         */
        $raw_options = get_option('wqrecaptcha');
        if (!empty($raw_options)) {
            try {
                $this->options_settings = unserialize($raw_options);
                $this->secretkey = $this->options_settings->get_sitekey('secretkey');
                $this->sitekey = $this->options_settings->get_sitekey('sitekey');
            } catch (Exception $e) {
                die('erreur');
            }
        }

        // add_shortcode('wqrecaptchav3', array($this, 'shorcode_recaptcha'));

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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wqrecaptcha-public.css', array(), $this->version, 'all');

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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_register_script('grecaptacha', 'https://www.google.com/recaptcha/api.js?render=' . $this->sitekey, array(), null, true);
        wp_register_script('WQverifcaptcha', plugin_dir_url(__FILE__) . 'js/wqrecaptcha-public.js', array('jquery','grecaptacha'), '1.0', true);

        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, $this->shortcode)) {

            wp_enqueue_script('grecaptacha');
            wp_enqueue_script('WQverifcaptcha');
            wp_localize_script('WQverifcaptcha', 'WQverifcaptcha_ajax', array(
                // 'url' => WP_SITEURL.'/wp-cms/wp-admin/admin-ajax.php',
                'url' => admin_url('admin-ajax.php'),
                'sitekey' => $this->sitekey,
                // 'queryvars' => json_encode( $wp_query->query )
            ));
        }

    }

    public function shortcode_recaptcha($atts)
    {
        // wp_deregister_script('grecaptcha');
        return 'shortcode '.$this->sitekey;
    }

    public function register_shortcodes()
    {
        add_shortcode($this->shortcode, array($this, 'shortcode_recaptcha'));
    }

    // public function check_if_shortcode($content)
    // {
    //     if (has_shortcode($content, $this->shortcode)) {
    //         echo 'ok';

    //         // wp_enqueue_script('WQverifcaptcha', plugin_dir_url(__FILE__) . 'js/wqrecaptcha-public.js', array('jquery'), '1.0', false);
    //     }
    //     return $content;
    // }
    /**
     *
     * validate site key with secret key
     */

    public function VerifRecaptcha()
    {
        $response = $_POST['token'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
        . $this->secretkey
            . "&response=" . $response
            . "&remoteip=" . $remoteip;

        $decode = json_decode(file_get_contents($api_url), true);

        if ($decode['success'] == true) {
            echo 'success';
        } else {
            echo 'error';
        }
        wp_die();
    }

}
