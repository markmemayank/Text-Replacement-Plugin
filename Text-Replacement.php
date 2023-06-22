<?php
/*
Plugin Name: Text Replacement Plugin
Description: Replaces old strings with new strings throughout the website.
Version: 1.0
Author: Mayank Kumar
*/

// Create a menu item in the WordPress dashboard
function text_replacement_plugin_menu() {
    add_options_page(
        'Text Replacement Plugin',
        'Text Replacement',
        'manage_options',
        'text-replacement-plugin',
        'text_replacement_plugin_options_page'
    );
}
add_action('admin_menu', 'text_replacement_plugin_menu');

// Display the options page in the WordPress dashboard
function text_replacement_plugin_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    // Save the new string when the form is submitted
    if (isset($_POST['submit'])) {
        $old_string = sanitize_text_field($_POST['old_string']);
        $new_string = sanitize_text_field($_POST['new_string']);
        update_option('text_replacement_old_string', $old_string);
        update_option('text_replacement_new_string', $new_string);
        echo '<div class="notice notice-success"><p>Strings saved successfully!</p></div>';
    }
    
    // Retrieve the saved strings
    $old_string = get_option('text_replacement_old_string');
    $new_string = get_option('text_replacement_new_string');
    
    // Display the options form
    ?>
    <div class="wrap">
        <h1>Text Replacement Plugin</h1>
        <form method="post" action="">
            <label for="old_string">Old String:</label>
            <input type="text" id="old_string" name="old_string" value="<?php echo esc_attr($old_string); ?>" required><br><br>
            <label for="new_string">New String:</label>
            <input type="text" id="new_string" name="new_string" value="<?php echo esc_attr($new_string); ?>" required><br><br>
            <input type="submit" name="submit" value="Save">
        </form>
    </div>
    <?php
}

// Perform text replacement
function replace_text($text) {
    $old_string = get_option('text_replacement_old_string');
    $new_string = get_option('text_replacement_new_string');
    
    $text = str_replace($old_string, $new_string, $text);
    return $text;
}
add_filter('the_content', 'replace_text');
