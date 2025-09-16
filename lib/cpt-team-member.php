<?php
if (! defined('ABSPATH')) exit;

add_action('init', function () {
    $labels = [
        'name'               => __('Team Members', 'argo22'),
        'singular_name'      => __('Team Member', 'argo22'),
        'add_new'            => __('Add New', 'argo22'),
        'add_new_item'       => __('Add New Team Member', 'argo22'),
        'edit_item'          => __('Edit Team Member', 'argo22'),
        'new_item'           => __('New Team Member', 'argo22'),
        'view_item'          => __('View Team Member', 'argo22'),
        'search_items'       => __('Search Team Members', 'argo22'),
        'not_found'          => __('No team members found.', 'argo22'),
        'not_found_in_trash' => __('No team members found in Trash.', 'argo22'),
        'menu_name'          => __('Team Members', 'argo22'),
    ];

    register_post_type('team_member', [
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'team'],
    ]);
});
