<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/admin
 * @author     Your Name <email@example.com>
 */
class WQRecaptcha_Admin
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

    /**
     * The settings section.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $section_recaptcha = 'recaptcha';
    private $section_domain = 'domains';

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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wqrecaptcha-admin.css', array(), $this->version, 'all');

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
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wqrecaptcha-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * register admin menu
     */
    public function options_page()
    {

        add_options_page(
            __('WQ recaptcha', 'webqam'),
            __('WQ recaptcha', 'webqam'),
            'manage_options',
            $this->plugin_name,
            function () {
                $this->options_page_html();
            }
        );
    }

    /**
     * register plugin settings
     */
    public function register_plugin_settings()
    {
        
        // section domain
        add_settings_section(
            $this->section_domain,
            'Domains',
            function () {
                $this->section_callback_domain();
            },
            $this->plugin_name
        );

        add_settings_field(
            'domain', 
            'Domain', 
            function () {
                $this->field_callback_domain();
            }, 
            $this->plugin_name, 
            $this->section_domain, 
            array( 'domain', __('Explanation for domain', 'ani_plugin')) 
        );

        register_setting($this->plugin_name, 'domain');

        
        // section site key / secret key
        add_settings_section(
            $this->section_recaptcha,
            'Site key / Secret key',
            function () {
                $this->section_callback_keys();
            },
            $this->plugin_name
        );

        add_settings_field(
            'sitekey', 
            'Site Key', 
            function () {
                $this->field_callback_sitekey();
            }, 
            $this->plugin_name, 
            $this->section_recaptcha, 
            array( 'sitekey', __('Explanation for site key', 'ani_plugin')) 
        );

        add_settings_field(
            'secretkey', 
            'Secret Key', 
            function () {
                $this->field_callback_secretkey();
            }, 
            $this->plugin_name, 
            $this->section_recaptcha, 
            array( 'secretkey', __('Explanation for secret key', 'ani_plugin')) 
        );

        register_setting($this->plugin_name, 'sitekey');
        register_setting($this->plugin_name, 'secretkey');

    }

    private function section_callback_domain(){
        echo 'section dmain';
    }

    private function field_callback_domain(){
        ?>
        <p>Current domain :
        <span class="beware">
        <?php
            $urlparts = parse_url(home_url());
            echo $urlparts['host'];
        ?>
        </span>
    </p>
    <p>
        <label for="target_domain">Domain associated with keys below :</label>
        <select name="target_domain" id="target_domain" class="">
            <option value="domaine 1">domaine 1</option>
            <option value="domaine 2">domaine 2</option>
            <option value="domaine 3">domaine 3</option>
        </select>
    </p>
    <button>Add a domain : </button>
    <input name="<?php echo $this->plugin_name; ?>_domain" id="domain" placeholder="add a domain" class="form_input_key" value="<?php echo esc_attr( get_option( $this->plugin_name.'_domain') ); ?>"/>
    <?php
    }

    private function section_callback_keys(){
        echo 'section keys';
    }

    private function field_callback_sitekey(){
        ?>
        <input name="<?php echo $this->plugin_name; ?>_sitekey" id="sitekey" placeholder="site key" class="form_input_key" value="<?php echo esc_attr( get_option( $this->plugin_name.'_sitekey') ); ?>"/>
        <?php
    }
    private function field_callback_secretkey(){
        ?>
        <input name="<?php echo $this->plugin_name; ?>_secretkey" id="secretkey" placeholder="secret key" class="form_input_key" value="<?php echo esc_attr( get_option( $this->plugin_name.'_secretkey') ); ?>"/>
        <?php
    }

    /**
     * add admin page
     */
    private function options_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wprecaptcha-admin-display.php';
    }

}
