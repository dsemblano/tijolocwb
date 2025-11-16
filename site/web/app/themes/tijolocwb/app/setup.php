<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    register_nav_menus([
        'footer_navigation' => __('Footer Navigation', 'sage'),
    ]);

    register_nav_menus([
        'primary-menu' => __('Primary Menu', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable wide alignment support.
     * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/#wide-alignment
     */
    // add_theme_support('align-wide');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});

// Remove dashicons in frontend for unauthenticated users
add_action( 'wp_enqueue_scripts', function () {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
});

// Menu overlay
add_action ('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'menu-toggle-script',
        [],
        '1.0',
        true
    );
});

// 1. Remove '/category/' from the permastruct
add_action('init', function() {
    global $wp_rewrite;
    $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
});

// 2. Generate custom rewrite rules for each category (and redirect old URLs)
add_filter('category_rewrite_rules', function($category_rewrite) {
    $category_rewrite = [];
    $categories = get_categories(['hide_empty' => false]);

    foreach ($categories as $category) {
        // Build full slug (including parents)
        $slug = $category->slug;
        if ($category->parent) {
            $slug = get_category_parents($category->parent, false, '/', true) . $slug;
        }

        // Feeds
        $category_rewrite["({$slug})/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$"] = 
            "index.php?category_name=\$matches[1]&feed=\$matches[2]";

        // Pagination
        $category_rewrite["({$slug})/page/?([0-9]{1,})/?$"] =
            "index.php?category_name=\$matches[1]&paged=\$matches[2]";

        // Category archive
        $category_rewrite["({$slug})/?$"] =
            "index.php?category_name=\$matches[1]";
    }

    // Redirect from old /category/... URLs
    $old_base = trim(get_option('category_base') ?: 'category', '/');
    $category_rewrite["{$old_base}/(.*)$"] =
        'index.php?category_redirect=$matches[1]';

    return $category_rewrite;
});

// 3. Allow our redirect query var
add_filter('query_vars', function($vars) {
    $vars[] = 'category_redirect';
    return $vars;
});

// 4. Perform the 301 redirect from /category/... to new URL
add_filter('request', function($query_vars) {
    if (!empty($query_vars['category_redirect'])) {
        wp_redirect(
            home_url(user_trailingslashit($query_vars['category_redirect'])),
            301
        );
        exit;
    }
    return $query_vars;
});

// 5. Strip '/category/' from all generated category links
add_filter('category_link', function($link, $term_id) {
    return str_replace('/category/', '/', $link);
}, 10, 2);

/**
 * Verifies that a given file path is under the directories that WordPress
 * manages for user contents.
 *
 * Returns false if the file at the given path does not exist yet.
 *
 * @param string $path A file path.
 * @return bool True if the path is under the content directories,
 *              false otherwise.
 */
function wpcf7_is_file_path_in_content_dir( $path ) {
	if ( $real_path = realpath( $path ) ) {
		$path = $real_path;
	} else {
		return false;
	}

	if ( 0 === strpos( $path, realpath( WP_CONTENT_DIR ) ) ) {
		return true;
	}

	if ( defined( 'UPLOADS' )
	and 0 === strpos( $path, realpath( ABSPATH . UPLOADS ) ) ) {
		return true;
	}

	return false;
}