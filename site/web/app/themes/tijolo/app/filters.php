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

// New navigation menu
add_filter('sage/blade/data', function ($data) {
    $data['primary_navigation'] = \Log1x\Navi\Facades\Navi::build('primary_navigation')->toArray();
    return $data;
});

// add_filter('get_the_archive_title', function ($title) {
//     if (is_category()) {
//         $title = single_cat_title('', false);
//     } elseif (is_tag()) {
//         $title = single_tag_title('', false);
//     } elseif (is_author()) {
//         $title = get_the_author();
//     } elseif (is_post_type_archive()) {
//         $title = post_type_archive_title('', false);
//     }
//     return $title;
// });

/**
 * Carregar o CSS dos blocos do Gutenberg apenas quando forem utilizados na página.
 */
// add_filter('should_load_separate_core_block_assets', '__return_true');