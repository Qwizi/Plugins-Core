<?php

declare(strict_types=1);

namespace Qwizi\Core;

use \Qwizi\Core\CheckVersion;

class Version extends CheckVersion
{
    public function __construct()
    {
        // DONT CHANGE THIS LINES!!!
        $this->pluginName = 'Qwizi Plugins Core';
        $this->version = '1.1';
        $this->apiLink = 'https://api.github.com/repos/Qwizi/Plugins-Core/releases/latest';
        $this->latestLink = 'https://github.com/Qwizi/Plugins-Core/releases/latest';
    }
}