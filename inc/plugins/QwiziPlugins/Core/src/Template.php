<?php

declare(strict_types=1);

namespace Qwizi\Core;

class Template
{
    public static function eval($variable, $templateName, bool $many=False)
    {
        global $templates;
        if($many) eval("\$variable .= \"" . $templates->get($templateName) . "\";");
        eval("\$variable = \"" . $templates->get($templateName) . "\";");
    }
}