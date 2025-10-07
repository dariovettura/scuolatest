<?php
/*
 * Plugin Name:       WP Toolkit Worker Plugin
 * Plugin URI:        https://www.plesk.com/wp-toolkit/
 * Description:       WP Toolkit worker plugin is installed by WP Toolkit or WP Guardian to provide functionality that can only work within WordPress itself
 * Version:           6.5.2-8712
 * Requires at least: 4.9
 * Requires PHP:      5.6
 */

// Copyright 1999-2024. WebPros International GmbH. All rights reserved.

namespace Webpros\WptkWpPlugin;

/* Do not access this file directly */
if (!defined('ABSPATH')) {
    header('Content-Type: text/plain');
    die(<<<MSG
 __________
< Go away >
 ----------
        \   ^__^
         \  (oo)\_______
            (__)\       )\/\
                ||----w |
                ||     ||
MSG
);
}

use Webpros\WptkWpPlugin\WpToolkit\Login\LoginBootstrap;

require_once __DIR__ . '/wp-toolkit/vendor/autoload.php';
require_once __DIR__ . '/wp-toolkit/Waf/waf-bootstrap.php';
LoginBootstrap::init();
