<?php

declare(strict_types=1);

namespace Qwizi\Core;

use \DirectoryIterator;

class Template
{
    public static function getTemplates(string $path)
    {
        $templates = [];
        $path .= '/templates/';

        foreach (new DirectoryIterator($path) as $file) {
            if ($file->isDot() || $file->getExtension() !== 'html' || $file->getFilename() === 'index.html') continue;
            $templateName = pathinfo($file->getFilename())['filename'];
            $templateContent = \file_get_contents($file->getPathname());
            $templates[$templateName] = $templateContent;
        }

        return $templates;
    }
    public static function eval($variable, $templateName, bool $many=False)
    {
        global $templates;
        if($many) eval("\$variable .= \"" . $templates->get($templateName) . "\";");
        eval("\$variable = \"" . $templates->get($templateName) . "\";");
    }
}