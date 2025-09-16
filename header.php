<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="container mx-auto px-6">
        <header class="site-header py-6">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?= esc_url(THEME_URI . '/assets/img/logo.svg') ?>" alt="<?php bloginfo('name'); ?>">
            </a>
        </header>
        <main class="site-main">