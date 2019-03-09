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
 * @package    WQRecaptcha
 * @subpackage WQRecaptcha/includes
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
 * @package    WQRecaptcha
 * @subpackage WQRecaptcha/includes
 * @author     Your Name <email@example.com>
 */

class WQRecaptcha
{

    public function init()
    {

        // if (!is_admin() or !current_user_can("manage_options")) {
        //     return;
        // }

        $this->registerAdminMenu();

    }

/**
 * Register admin menu.
 */
    private function registerAdminMenu()
    {

        add_action('admin_menu', function () {
            add_management_page(
                __('WQ recaptcha', 'webqam'),
                __('WQ recaptcha', 'webqam'),
                'manage_options',
                'WQRecaptcha',
                function () {
                    $this->WQRecaptcha_options_page_html();
                }
            );
        });

        // add styles
        add_action('admin_print_styles', function () {
            wp_enqueue_style('styleWQRecaptcha', plugins_url('../css/WQRecaptcha.css', __FILE__));
        });

        add_shortcode('recaptchav3', function () {
            $this->WQRecaptcha_shortcode();
        });

    }

/**
 * add page menu.
 */
    public function WQRecaptcha_options_page_html()
    {

        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
    <div class="wrap">
        <h1><?=esc_html(get_admin_page_title());?></h1>
        <br/>
        <p>
        <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google recaptcha v3</a>
        </p>
        <br/>
        <p>Domain : <?php $urlparts = parse_url(home_url());
        echo $urlparts['host'];?> </p>
        <form action="options.php" method="post" class="WQRecaptcha_form">
        <?php
        // output security fields for the registered setting "wporg_options"
        settings_fields('wqrecaptacha_options');
        // output setting sections and their fields
        ?>
            <p>
                <h3>Secret Key</h3>
                <input name="secretkey" id="secretkey" placeholder="secret key" class="form_input"/>
            </p>
            <p>
                <h3>Site Key</h3>
                <input name="sitekey" id="sitekey" placeholder="site key" class="form_input"/>
            </p>
            <?php
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections('wqrecaptacha');
        // output save settings button
        submit_button('Save Settings');
        ?>
        </form>
    </div>
    <?php
}

/**
 *
 * add recaptcha files
 */
    private function WQRecaptcha_shortcode()
    {
        //
    }

}