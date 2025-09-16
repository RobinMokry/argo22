<?php
get_header();
if (have_posts()):
    while (have_posts()): the_post();
        if (is_singular('post')):
            echo '<div class="mb-4"><h1 class="text-2xl font-bold ">' . get_the_title() . '</h1></div>';
        endif;
        the_content();
    endwhile;
else:
    echo '<p>No content.</p>';
endif;
get_footer();
