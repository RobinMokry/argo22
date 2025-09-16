<?php
if (! defined('ABSPATH')) exit;

add_action('acf/init', function () {

    if (! function_exists('acf_add_local_field_group')) return;

    // 1) TEAM MEMBER fields
    acf_add_local_field_group([
        'key' => 'group_tm_fields',
        'title' => 'Team Member Fields',
        'fields' => [
            [
                'key' => 'field_tm_name',
                'label' => 'Name',
                'name' => 'tm_name',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_tm_position',
                'label' => 'Position',
                'name' => 'tm_position',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_tm_desc',
                'label' => 'Description',
                'name' => 'tm_description',
                'type' => 'wysiwyg',
                'tabs' => 'all',
                'media_upload' => 0,
            ],
            [
                'key' => 'field_tm_avatar',
                'label' => 'Profile Image',
                'name' => 'tm_avatar',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ],
            [
                'key' => 'field_tm_phone',
                'label' => 'Phone Number',
                'name' => 'tm_phone',
                'type' => 'text',
            ],
            [
                'key' => 'field_tm_email',
                'label' => 'E-mail',
                'name' => 'tm_email',
                'type' => 'email',
                'required' => 1,
            ],
        ],
        'location' => [[
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'team_member',
            ],
        ]],
    ]);

    // 2) POST Reviewer (allows linking a Team Member as reviewer to a Post)
    acf_add_local_field_group([
        'key' => 'group_post_reviewer',
        'title' => 'Reviewer',
        'fields' => [
            [
                'key' => 'field_post_reviewer',
                'label' => 'Reviewer',
                'name' => 'reviewer',
                'type' => 'post_object',
                'post_type' => ['team_member'],
                'return_format' => 'id',
                'multiple' => 0,
                'ui' => 1,
            ],
        ],
        'location' => [[
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post',
            ],
        ]],
    ]);

    // 3) BLOCK controls – Team Member Grid 
    acf_add_local_field_group([
        'key' => 'group_block_tm_grid',
        'title' => 'Team Member Grid Settings',
        'fields' => [
            [
                'key' => 'field_grid_columns',
                'label' => 'Number of Columns',
                'name' => 'columns',
                'type' => 'select',
                'choices' => [
                    2 => '2',
                    3 => '3',
                    4 => '4',
                ],
                'allow_null' => 1, 
            ],
            [
                'key' => 'field_grid_show_position',
                'label' => 'Display Position',
                'name' => 'display_position',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 1, 
            ],
        ],
        'location' => [[
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/team-member-grid',
            ],
        ]],
    ]);

    // 4) BLOCK controls – Team Member Detail
    acf_add_local_field_group([
        'key' => 'group_block_tm_detail',
        'title' => 'Team Member Detail Settings',
        'fields' => [
            [
                'key' => 'field_tm_detail_select',
                'label' => 'Team Member',
                'name' => 'team_member_select',
                'type' => 'post_object',
                'post_type' => ['team_member'],
                'return_format' => 'id',
                'multiple' => 0,
                'ui' => 1,
            ],
        ],
        'location' => [[
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/team-member-detail',
            ],
        ]],
    ]);
});
