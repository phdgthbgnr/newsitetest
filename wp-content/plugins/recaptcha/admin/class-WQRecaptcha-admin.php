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

    private $current_domain;

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
     */    
    public function update_options_settings($new_value, $old_value, $option_name)
    {
        //
        $raw_options = get_option('wqrecaptcha');
        if(!empty($raw_options)){
            try {
                $json_options = json_decode($raw_options);
            }catch(Exception $e){
                die( 'erreur JSON');
            }
        }

        if(!isset($json_options->domains)){
            $json_options['domains'] = array();
        
        }elseif ($option_name == 'newdomain'){
            if(!isset($json_options->domains->{$new_value})){
                $json_options->domains[$new_value] = array('sitekey' => 0, 'secretkey' => 0 );
            }
        }else{

        }

        update_option('wqrecaptcha', json_encode($json_options));
        
        // 

        
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
        // add_settings_section($this->section, 'Add a pair site / secret key', function () {$this->section_callback();}, $this->plugin_name);
        add_settings_section($this->section, 'Add a pair site / secret key', array($this, 'section_callback'), $this->plugin_name);

        $fields = array(
            array(
                'uid' => 'domains',
                'label' => 'Domaines',
                'section' => $this->section,
                'type' => 'select',
                'options' => array(''),
                'placeholder' => 'Domaines enregistres',
                'helper' => 'Does this help?',
                'supplemental' => 'bla bla',
                'default' => 'maybe',
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
                'default' => '0',
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
                'default' => '0',
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
                'default' => '0',
            ),

        );

        $args = array(
            'type' => 'string', 
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
            );

        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), $this->plugin_name, $field['section'], $field);
            // enregistre les champs en base -> pas ce qu'on veut
            register_setting( $this->plugin_name, $field['uid'], $args );
        }

        // enregistrer un champs avec des objets sérialisés
        // register_setting($this->plugin_name, 'wqrecaptcha');
        
        

    }

    // public function sanitize_text_field(){
    //     die('sanitize_text_field');
    // }

    public function section_callback()
    {
        $urlparts = parse_url(home_url());
        echo '<a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google recaptcha v3</a>';
        echo '<p>Current domain : <span class="beware">' . $urlparts['host'] . '</span></p>';
    }

    public function field_callback($arguments)
    {
        $value = get_option($arguments['uid']);

        switch($arguments['type']){
            
            case 'text':
            printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="form_input_key" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
            break;
            
            case 'select':
            if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                $options_markup = '';
                foreach( $arguments['options'] as $key => $label ){
                    $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
                }
                printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
            }
            break;
        }
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
