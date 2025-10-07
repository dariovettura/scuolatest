<?php
// Copyright 1999-2024. WebPros International GmbH. All rights reserved.

namespace Webpros\WptkWpPlugin\WpToolkit\Common;

class CliHelper
{
    public static function is_cli()
    {
        return php_sapi_name() === 'cli';
    }
}
