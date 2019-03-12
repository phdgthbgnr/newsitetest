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
    private $section;

    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->section = $plugin_name . '_settings';

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
     * pre update options
     *
     * @since    1.0.0
     *
     * TODO : A passer en objet à sérialiser
     *
     */
    public function update_options_settings($new_value, $old_value, $option_name)
    {
        //
        $raw_options = get_option('wqrecaptcha');
        if (!empty($raw_options)) {
            try {
                $arr_options = unserialize($raw_options);
            } catch (Exception $e) {
                die('erreur');
                $arr_options = array('currentdomain' => '', 'domains' => array());
            }
        } else {
            $arr_options = array('currentdomain' => '', 'domains' => array());
        }

        if ($option_name == 'newdomain' && !empty($new_value)) {
            $arr_options['currentdomain'] = $new_value;
            if (!key_exists($new_value, $arr_options['domains'])) {
                $arr_options['domains'][$new_value] = array('sitekey' => '', 'secretkey' => '');
            }
        }

        if ($option_name == 'currentdomain' && !empty($new_value)) {
            $arr_options['currentdomain'] = $new_value;
            // if (!key_exists($new_value, $arr_options['domains'])) {
            //     $arr_options['domains'][$new_value] = array('sitekey' => '', 'secretkey' => '');
            // }
        }

        if ($option_name == 'sitekey' && !empty($arr_options['currentdomain'])) {
            $curdom = $arr_options['currentdomain'];
            $arr_options['domains'][$curdom]['sitekey'] = $new_value;
        }

        if ($option_name == 'secretkey' && !empty($arr_options['currentdomain'])) {
            $curdom = $arr_options['currentdomain'];
            $arr_options['domains'][$curdom]['secretkey'] = $new_value;
        }

        update_option('wqrecaptcha', serialize($arr_options));

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
            array($this, 'options_page_html')
            // function () {
            //     $this->options_page_html();
            // }
        );
    }

    /**
     * register plugin settings
     */
    public function register_plugin_settings()
    {
        // default settings
        $fields = array(
            array(
                'uid' => 'currentdomain',
                'label' => '',
                'section' => $this->section,
                'type' => 'hidden',
                'options' => false,
                'placeholder' => '',
                'helper' => 'Does this help?',
                'supplemental' => 'secret key google recaptcha v3',
                'default' => '',
            ),
            array(
                'uid' => 'domains',
                'label' => 'Sélectionner un domaine',
                'section' => $this->section,
                'type' => 'select',
                'options' => array(''),
                'placeholder' => 'Domaines enregistres',
                'helper' => 'Does this help?',
                'supplemental' => 'bla bla',
                'default' => '',
            ),
            array(
                'uid' => 'newdomain',
                'label' => 'Nouveau domaine',
                'section' => $this->section,
                'type' => 'text',
                'options' => false,
                'placeholder' => 'ajouter un domaine',
                'helper' => 'Does this help?',
                'supplemental' => 'New domain for google recaptcha v3',
                'default' => '',
            ),
            array(
                'uid' => 'sitekey',
                'label' => 'Site Key',
                'section' => $this->section,
                'type' => 'text',
                'options' => false,
                'placeholder' => 'site key',
                'helper' => 'Does this help?',
                'supplemental' => 'site key google recaptcha v3',
                'default' => '',
            ),
            array(
                'uid' => 'secretkey',
                'label' => 'Secret Key',
                'section' => $this->section,
                'type' => 'text',
                'options' => false,
                'placeholder' => 'secret key',
                'helper' => 'Does this help?',
                'supplemental' => 'secret key google recaptcha v3',
                'default' => '',
            ),

        );
        // add_settings_section($this->section, 'Add a pair site / secret key', function () {$this->section_callback();}, $this->plugin_name);
        add_settings_section($this->section, 'Add a pair site / secret key', array($this, 'section_callback'), $this->plugin_name);

        $args = array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'validate_callback' => 'validate',
            'default' => null,
        );
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), $this->plugin_name, $field['section'], $field);
            /*
             * les champs ne sont pas directement enregistrés en base
             * interception avec update_options_settings
             * -> class-WQRecaptcha > define_admin_hook
             */
            register_setting($this->plugin_name, $field['uid'], $args);
        }

    }

    public function section_callback()
    {
        $urlparts = parse_url(home_url());
        echo '<a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google recaptcha v3</a>';
        echo '<p>Current domain : <span class="beware">' . $urlparts['host'] . '</span></p>';
    }

    public function field_callback($arguments)
    {
        // default values
        $value = '';

        // saved values
        $raw_options = get_option('wqrecaptcha');
        $arr_options = unserialize($raw_options);

        switch ($arguments['type']) {

            case 'text':
                if ($arguments['uid'] == 'sitekey' || $arguments['uid'] == 'secretkey') {
                    if (!empty($raw_options)) {
                        if (isset($arr_options['currentdomain']) && isset($arr_options['domains'])) {
                            $currentdom = $arr_options['currentdomain'];
                            $value = $arr_options['domains'][$currentdom][$arguments['uid']];
                        }
                    }
                }
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="form_input_key" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
                break;

            case 'select':
                $options_markup = '';
                if (!empty($raw_options)) {
                    if (isset($arr_options['currentdomain']) && isset($arr_options['domains'])) {
                        $value = $arr_options['currentdomain'];
                        foreach ($arr_options['domains'] as $key => $val) {
                            $options_markup .= sprintf('<option value="%s" %s data-site="%s" data-secret="%s">%s</option>', $key, selected($value, $key, false), $val['sitekey'], $val['secretkey'], $key);
                        }
                    }
                }
                printf('<select name="%1$s" id="%1$s" class="form_input_key">%2$s</select>', $arguments['uid'], $options_markup);
                break;

            case 'hidden':
                if (!empty($raw_options)) {
                    if (isset($arr_options['currentdomain']) && isset($arr_options['domains'])) {
                        $value = $arr_options['currentdomain'];
                        printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="form_input_key" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
                    }
                }

                break;
        }
    }

    /**
     * add admin page
     */
    public function options_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wprecaptcha-admin-display.php';
    }

}
