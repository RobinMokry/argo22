<?php
if (! defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (function_exists('acf_register_block_type')) {

        // Block 1: Team Member Detail
        acf_register_block_type([
            'name'            => 'team-member-detail',
            'title'           => __('Team Member Detail', 'argo22'),
            'description'     => __('Displays a single team member profile.', 'argo22'),
            'render_callback' => 'argo22_render_block_team_member_detail',
            'category'        => 'widgets',
            'icon'            => 'admin-users',
            'keywords'        => ['team', 'member', 'detail'],
            'supports'        => ['align' => true, 'mode' => true],
        ]);

        // Block 2: Team Member Grid
        acf_register_block_type([
            'name'            => 'team-member-grid',
            'title'           => __('Team Member Grid', 'argo22'),
            'description'     => __('Displays multiple team members in a grid.', 'argo22'),
            'render_callback' => 'argo22_render_block_team_member_grid',
            'category'        => 'widgets',
            'icon'            => 'grid-view',
            'keywords'        => ['team', 'members', 'grid'],
            'supports'        => ['align' => true, 'mode' => true],
        ]);
    }
});

// ---------- RENDER CALLBACKS ----------

function argo22_render_block_team_member_detail($block, $content = '', $is_preview = false, $post_id = 0)
{
    $ctx_id = get_the_ID();
    $tm_id = (get_post_type($ctx_id) === 'team_member') ? $ctx_id : 0;

    // Use selected member from block settings if provided
    $selected = get_field('team_member_select');
    if ($selected && is_numeric($selected)) {
        $tm_id = (int) $selected;
    }

    // Validate team member exists
    if (! $tm_id || ! get_post($tm_id) || get_post_type($tm_id) !== 'team_member') {
        if ($is_preview) {
            echo '<div class="tm-preview-notice"><p>Please select a Team Member in this block\'s settings, or insert this block within a Team Member post.</p></div>';
        }
        return;
    }

    // Get and validate fields with fallbacks
    $name  = get_field('tm_name', $tm_id) ?: get_the_title($tm_id) ?: 'Unknown Member';
    $pos   = get_field('tm_position', $tm_id);
    $desc  = get_field('tm_description', $tm_id);
    $img   = get_field('tm_avatar', $tm_id);
    $phone = get_field('tm_phone', $tm_id);
    $email = get_field('tm_email', $tm_id);

    // Validate image data
    $img_url = '';
    $img_alt = esc_attr($name);
    if ($img && is_array($img) && !empty($img['url'])) {
        $img_url = esc_url($img['url']);
        $img_alt = !empty($img['alt']) ? esc_attr($img['alt']) : $img_alt;
    }

    // Query latest 5 Posts that reference this Team Member as Reviewer
    $q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'meta_query'     => [[
            'key'   => 'reviewer',
            'value' => $tm_id,
            'compare' => '=',
        ]],
        'no_found_rows'  => true,
    ]);

?>
    <article class="tm-detail max-w-xs space-y-5 py-6" role="article" aria-labelledby="tm-name-<?= $tm_id ?>">
        <?php if ($img_url) { ?>
            <div class="w-32 h-32 overflow-hidden rounded-full" role="img" aria-label="<?= $img_alt ?>">
                <img src="<?= $img_url ?>" alt="<?= $img_alt ?>" class="w-full !h-full object-cover" loading="lazy" />
            </div>
        <?php } ?>

        <header class="tm-name-pos space-y-1">
            <?php $is_single_tm = (get_post_type($ctx_id) === 'team_member'); ?>
            <?php if ($is_single_tm) { ?>
                <h1 id="tm-name-<?= $tm_id ?>" class="text-2xl font-semibold leading-tight"><?= esc_html($name) ?></h1>
            <?php } else { ?>
                <h2 id="tm-name-<?= $tm_id ?>" class="text-xl font-semibold leading-tight"><?= esc_html($name) ?></h2>
            <?php } ?>
            <?php if ($pos) { ?><p class="text-gray-600" role="text"><?= esc_html($pos) ?></p><?php } ?>
        </header>

        <?php if ($desc) { ?>
            <div class="tm-description" role="text">
                <?= apply_filters('the_content', $desc) ?>
            </div>
        <?php } ?>

        <?php if ($phone || $email) { ?>
            <section class="tm-contacts" aria-label="Contact information">
                <ul class="space-y-1">
                    <?php if ($phone) { ?>
                        <li>
                            <a class="inline-flex items-center gap-2" href="tel:<?= esc_attr(preg_replace('/\s+/', '', $phone)) ?>" aria-label="Call <?= esc_attr($name) ?> at <?= esc_attr($phone) ?>">
                                <img class="w-4 h-4" src="<?= esc_url(THEME_URI . '/assets/img/phone.svg') ?>" alt="" role="presentation" width="16" height="16" />
                                <span><?= esc_html($phone) ?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($email) { ?>
                        <li>
                            <a class="inline-flex items-center gap-2" href="mailto:<?= esc_attr($email) ?>" aria-label="Email <?= esc_attr($name) ?> at <?= esc_attr($email) ?>">
                                <img class="w-4 h-4" src="<?= esc_url(THEME_URI . '/assets/img/envelope.svg') ?>" alt="" role="presentation" width="16" height="16" />
                                <span><?= esc_html($email) ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </section>
        <?php } ?>

        <?php if ($q->have_posts()) { ?>
            <div class="tm-reviewer-posts mt-4">
                <h4 class="font-medium mb-2">Recent posts reviewed:</h4>
                <ul class="list-disc space-y-1 pl-5">
                    <?php while ($q->have_posts()) {
                        $q->the_post(); ?>
                        <li><a href="<?= esc_url(get_permalink()) ?>"><?= esc_html(get_the_title()) ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php } ?>
    </article>
<?php
}

function argo22_render_block_team_member_grid($block, $content = '', $is_preview = false, $post_id = 0)
{
    $columns = (int) get_field('columns') ?: 0; // no default value
    $show_position = (bool) get_field('display_position');
    $cols = $columns ? $columns : 3; // fallback for simple layout without utility classes

    $tm = new WP_Query([
        'post_type' => 'team_member',
        'posts_per_page' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'no_found_rows' => true,
    ]);

    if (! $tm->have_posts()) {
        echo '<p>No team members yet.</p>';
        return;
    }

    $item_width = floor(100 / max(1, $cols));
?>
    <div class="tm-grid container mx-auto">
        <?php
        $cols_class = 'grid-cols-3';
        if ($cols === 2) $cols_class = 'grid-cols-2';
        if ($cols === 4) $cols_class = 'grid-cols-4';
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:<?= esc_attr($cols_class) ?> gap-6 py-6">
            <?php while ($tm->have_posts()) {
                $tm->the_post();
                $id = get_the_ID();
                $name = get_field('tm_name', $id) ?: get_the_title($id);
                $pos  = get_field('tm_position', $id);
                $img  = get_field('tm_avatar', $id) ? get_field('tm_avatar', $id)['url'] : esc_url(THEME_URI . '/assets/img/user.svg');
                $phone = get_field('tm_phone', $id);
            ?>
                <div class="tm-card min-w-[200px]">
                    <div class="border border-gray-200 p-5 rounded-lg h-full ">
                        <div class="w-24 h-24 overflow-hidden rounded-full mb-2 bg-gray-100 flex items-center justify-center">
                            <?php if ($img) { ?>
                                <img class="w-full !h-full object-cover" src="<?= esc_url($img) ?>" alt="<?= esc_attr($name) ?>" />
                            <?php } ?>
                        </div>
                        <div class="tm-card-body space-y-3">
                            <div class="tm-name-pos space-y-1 mt-3">
                                <strong class="block font-medium"><a href="<?= esc_url(get_permalink($id)) ?>"><?= esc_html($name) ?></a></strong>
                                <?php if ($show_position && $pos) { ?><div class="tm-card-pos text-gray-600"><?= esc_html($pos) ?></div><?php } ?>
                            </div>
                            <?php if ($phone) { ?>
                                <a class="tm-card-phone inline-flex items-center gap-2" href="tel:<?= esc_attr(preg_replace('/\s+/', '', $phone)) ?>">
                                    <img src="<?= esc_url(THEME_URI . '/assets/img/phone.svg') ?>" alt="Phone" width="16" height="16" />
                                    <?= esc_html($phone) ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php wp_reset_postdata();
}
?>