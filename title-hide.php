<?php
/**
 * Plugin Name: Title Hide
 * Description: The Title Hide WordPress Plugin can be used to completely remove/disable or hide the page and post titles using your WordPress admin panel.
 * Version: 1.0.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Harpreet Kumar
 * Contributors: hkharpreetkumar1, ak0ashokkumar
 * Author URI: https://profiles.wordpress.org/hkharpreetkumar1/
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly    

// Add meta box for hiding page title
function tihi_title_hide_meta_box() {
    add_meta_box(
        'tihi_title_hide_meta_box_post',
        'Hide Post Title',
        'tihi_render_title_hide_meta_box',
        'post', // Add to posts
        'side', // Show on the side of the editor
        'high' // Priority
    );
    
    add_meta_box(
        'tihi_title_hide_meta_box_page',
        'Hide Page Title',
        'tihi_render_title_hide_meta_box',
        'page', // Add to pages
        'side', // Show on the side of the editor
        'high' // Priority
    );
}

// Render meta box content
function tihi_render_title_hide_meta_box($post) {
    $hide_title = get_post_meta($post->ID, 'tihi_title_hide', true);
    ?>
    <label for="tihi_title_hide">
        <input type="checkbox" name="tihi_title_hide" id="tihi_title_hide" <?php checked($hide_title, 'on'); ?> />
        Hide the title on this page
    </label>
    <?php
}

// Save meta box data
function tihi_save_title_hide_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['tihi_title_hide'])) {
        update_post_meta($post_id, 'tihi_title_hide', 'on');
    } else {
        delete_post_meta($post_id, 'tihi_title_hide');
    }
}

// Hook to add meta box
add_action('add_meta_boxes', 'tihi_title_hide_meta_box');
// Hook to save meta box data
add_action('save_post', 'tihi_save_title_hide_meta_box');
// Function to hide the title
function title_hide() {
    // Check if it's a single page
    if (is_singular()) {
        // Get the current post ID
        $post_id = get_queried_object_id();
        // Check if the title should be hidden
        $hide_title = get_post_meta($post_id, 'tihi_title_hide', true);
        if ($hide_title === 'on') {
            // Hide the title using CSS
            echo '<style>.entry-title { display: none; }</style>';
        }
    }
}

// Hook to the wp_head action
add_action('wp_head', 'title_hide');
?>
