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
	<h2><?=esc_html(get_admin_page_title());?></h2>
	<form action="options.php" method="post" class="<?php echo $this->plugin_name; ?>_form">
    <!-- <label for="target_domain">Domain associated with keys below :</label>
    <select name="target_domain" id="target_domain" class="">
        <option value="domaine 1">domaine 1</option>
        <option value="domaine 2">domaine 2</option>
        <option value="domaine 3">domaine 3</option>
    </select>
    <button>Add a domain : </button><input type="text" name="newdomain" id="newdomain" class="form_input_dom"/> -->
    <?php
// (sections are registered for "$this->plugin_name", each field is registered to a specific section)
/*
 * output security fields for the registered setting
 */
settings_fields($this->plugin_name);
/*
 * output setting sections and their fields
 */
do_settings_sections($this->plugin_name);
/*
 * output save settings button
 */
submit_button('Save Settings');
?>
    </form>
</div>
