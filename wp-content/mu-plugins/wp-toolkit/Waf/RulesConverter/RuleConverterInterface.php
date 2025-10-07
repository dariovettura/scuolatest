<?php
// Copyright 1999-2024. WebPros International GmbH. All rights reserved.

namespace Webpros\WptkWpPlugin\WpToolkit\Waf\RulesConverter;

interface RuleConverterInterface
{
    /**
     * @return array
     */
    public static function convert(array $rule);
}
