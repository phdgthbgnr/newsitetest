<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    wq-recaptcha
 * @subpackage wq-recaptcha/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
	<h1><?=esc_html(get_admin_page_title());?></h1>
	<br/>
	<p>
	<a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google recaptcha v3</a>
	</p>
	<form action="options.php" method="post" class="<?php echo $this->plugin_name; ?>_form">
		<?php
        /*
        * output security fields for the registered setting
        */
        // settings_fields('domains');
        do_settings_sections($this->plugin_name);
        // output setting sections and their fields
        ?>
        
        <?php
        // (sections are registered for "$this->plugin_name", each field is registered to a specific section)
        // output save settings button
        submit_button('Save Settings');
        ?>
    </form>
</div>
