<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Filter to remove the plugin credit notice added to the source.
 *
 */
add_filter('rank_math/frontend/remove_credit_notice', '__return_true');

// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

//removing WP version
remove_action('wp_head', 'wp_generator');

// removing WP version from RSS
function remove_wp_version_rss()
{
    return '';
}
add_filter('the_generator', 'remove_wp_version_rss');
