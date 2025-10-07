<?php
// Copyright 1999-2024. WebPros International GmbH. All rights reserved.

namespace Webpros\WptkWpPlugin\WpToolkit\Common;

class WordPressHelper
{
    public static function upsert_option($option, $value)
    {
        if (!add_option($option, $value)) {
            update_option($option, $value);
        }
    }
}
