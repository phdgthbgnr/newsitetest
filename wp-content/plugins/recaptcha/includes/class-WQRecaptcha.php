<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/includes
 * @author     Your Name <email@example.com>
 */
class WQRecaptcha
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      WQRecaptcha_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    /**
     * The object containing the options settings.
     *
     * @since    1.0.0
     * @access   protected
     * @var      object 
     */
    protected $options_settings;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WQRECAPTCHA_VERSION')) {
            $this->version = WQRECAPTCHA_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        /**
         * uniqid = 5c8fd2a564dc3
         */
        $this->plugin_name = 'wqrecaptcha_5c8fd2a564dc3';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
     * - Plugin_Name_i18n. Defines internationalization functionality.
     * - Plugin_Name_Admin. Defines all hooks for the admin area.
     * - Plugin_Name_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-WQRecaptcha-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-WQRecaptcha-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-WQRecaptcha-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-WQRecaptcha-public.php';
        $this->loader = new WQRecaptcha_Loader();
        /**
         * The class responsible for defining all options setting serialized & recorded to DB
         * occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-WQRecaptcha-options.php';

    }
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new WQRecaptcha_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new WQRecaptcha_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_menu', $plugin_admin, 'options_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_plugin_settings');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_WQ_admin_recaptcha', $plugin_admin, 'WQAdminRecaptcha');

        // interception update settings
        // -> class-WQRecaptcha-admin
        $this->loader->add_filter('pre_update_option_currentdomain', $plugin_admin, 'update_options_settings', 9, 3);
        $this->loader->add_filter('pre_update_option_domains', $plugin_admin, 'update_options_settings', 9, 3);
        $this->loader->add_filter('pre_update_option_newdomain', $plugin_admin, 'update_options_settings', 10, 3);
        $this->loader->add_filter('pre_update_option_sitekey', $plugin_admin, 'update_options_settings', 11, 3);
        $this->loader->add_filter('pre_update_option_secretkey', $plugin_admin, 'update_options_settings', 12, 3);
        $this->loader->add_filter('pre_update_option_urlapi', $plugin_admin, 'update_options_settings', 13, 3);

        //  interception loading settings
        // $this->loader->add_filter('pre_option_domains',  $plugin_admin, 'load_options_settings', 8, 3);
        // $this->loader->add_filter('pre_option_newdomain',  $plugin_admin, 'load_options_settings', 9, 3);
        // $this->loader->add_filter('pre_option_sitekey',  $plugin_admin, 'load_options_settings', 10, 3);
        // $this->loader->add_filter('pre_option_secretkey',  $plugin_admin, 'load_options_settings', 11, 3);

    }
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new WQRecaptcha_Public($this->get_plugin_name(), $this->get_version());
        /**
         * shortcode
         */
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        /**
         * css public
         */
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        /**
         * ajax validate captcha
         */
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_WQrecaptcha', $plugin_public, 'VerifRecaptcha');
        $this->loader->add_action('wp_ajax_nopriv_WQrecaptcha', $plugin_public, 'VerifRecaptcha');

        // $this->loader->add_filter('the_content', $plugin_public, 'check_if_shortcode', 10);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    WQRecaptcha_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
