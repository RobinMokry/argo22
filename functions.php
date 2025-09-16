<?php
if (! defined('ABSPATH')) exit;

// Global theme URI constant (stylesheet directory for child theme compatibility)
if (! defined('THEME_URI')) {
    define('THEME_URI', get_stylesheet_directory_uri());
}

// Theme supports
add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
});


// Autoload our includes
require_once __DIR__ . '/lib/cpt-team-member.php';
require_once __DIR__ . '/lib/acf-fields.php';
require_once __DIR__ . '/lib/blocks.php';

// Enqueue frontend styles/scripts
add_action('wp_enqueue_scripts', function () {
    // Enqueue Inter font from Google Fonts
    wp_enqueue_style('inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null);
    
    // Use compiled CSS if available, fallback to CDN for development
    $css_path = get_template_directory() . '/assets/css/theme.css';
    if (file_exists($css_path) && filesize($css_path) > 1000) {
        wp_enqueue_style('argo22-theme', THEME_URI . '/assets/css/theme.css', ['inter-font'], filemtime($css_path));
    } else {
        // Development fallback
        wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);
    }
});

// Enqueue Gutenberg editor styles/scripts
add_action('enqueue_block_editor_assets', function () {
    // Enqueue Inter font for editor
    wp_enqueue_style('inter-font-editor', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', [], null);
    
    $css_path = get_template_directory() . '/assets/css/theme.css';
    if (file_exists($css_path) && filesize($css_path) > 1000) {
        wp_enqueue_style('argo22-theme-editor', THEME_URI . '/assets/css/theme.css', ['inter-font-editor'], filemtime($css_path));
    } else {
        // Development fallback
        wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);
    }
});

// Admin notice if ACF block API is not available
add_action('admin_notices', function () {
    if (! function_exists('acf_register_block_type')) {
        echo '<div class="notice notice-error"><p><strong>ACF blocks are not available:</strong> It looks like Advanced Custom Fields PRO is not installed or active. Custom blocks will not appear in the editor.</p></div>';
    }
});

