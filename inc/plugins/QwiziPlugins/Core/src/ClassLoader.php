<?php
declare(strict_types=1);

namespace Qwizi\Core;

use \Qwizi\Core\Version;

/**
 * @author euant
 */

final class ClassLoader
{
    /**
     * @var self
     */
    private static $instance;
    /**
     * @var array
     */
    private $nameSpacePrefixes;
    private function __construct()
    {
        $this->nameSpacePrefixes = [];
    }
    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    public function registerNamespace(string $nameSpacePrefix, string $basePath)
    {
        if (substr($nameSpacePrefix, -1) !== '\\') {
            $nameSpacePrefix .= '\\';
        }
        if (substr($basePath, -1) !== '/') {
            $basePath .= '/';
        }
        $this->nameSpacePrefixes[$nameSpacePrefix] = $basePath;
        return $this;
    }
    public function resolve(string $class): void
    {
        foreach ($this->nameSpacePrefixes as $prefix => $basePath) {
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                continue;
            }
            $relative = substr($class, $len);
            $file = $basePath . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require $file;
                break;
            }
        }
    }
    public function register(): void
    {
        spl_autoload_register([$this, 'resolve']);

        global $plugins;
        $plugins->add_hook('admin_config_plugins_begin', [new Version(), 'check']);
    }

    /*public function checkVersion()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.github.com/repos/Qwizi/Plugins-Core/releases/latest',
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36"'
        ]);

        $response = json_decode(curl_exec($curl), true);
        
        curl_close($curl);

        if (Version::VERSION != $response['tag_name']) {
            \flash_message('Używasz przestarzalej wersji Qwizi Plugins Core. <a href="https://github.com/Qwizi/Plugins-Core/releases/latest">Pobierz najnowsza wersje</a>', 'error');
        }
    }*/
}