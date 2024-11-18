<?php

namespace App\Menu;

class CustomWalker extends \Walker_Nav_Menu // Use the global namespace for Walker_Nav_Menu
{
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="submenu hidden pl-4 space-y-2 text-base font-normal">';
    }

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        // Ensure $item->classes is an array
        $class_names = is_array($item->classes) ? $item->classes : explode(' ', $item->classes);
        $output .= '<li class="' . esc_attr(join(' ', $class_names)) . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="flex items-center justify-between">';
        $output .= esc_html($item->title);

        // Ensure we are checking the class names array with in_array()
        if (in_array('menu-item-has-children', $class_names)) {
            $output .= '<span class="ml-2 text-sm arrow">↓</span>'; // Arrow icon
        }
        $output .= '</a>';
    }
}
