<?php

// All custom functions here

// function caturl($catname) {
//     $category = get_category_by_slug(sanitize_title($catname));
//     $cat_name = get_category_by_slug($catname)->cat_name;

//     if ($category) {
//         // Get the category URL from the category object
//         $category_url = get_term_link($category);
//         // Return the HTML link tag with the category URL and name
//         return '<a href="' . $category_url . '">' . $cat_name . '</a>';
//     } else {
//         return '';
//     }
// }