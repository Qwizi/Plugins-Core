<?php

declare(strict_types=1);

namespace Qwizi\Core;

class CheckVersion
{
    protected $version;
    protected $apiLink;
    protected $latestLink;
    protected $name;
    protected $description;
    protected $author;
    protected $authorSite;
    protected $compatibility = '18*';
    protected $codename;

    public function getVersion()
    {
        return $this->version;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorSite()
    {
        return $this->authorSite;
    }

    public function getCompatibility()
    {
        return $this->compatibility;
    }

    public function getCodeName() {
        return $this->codename;
    }

    public function getPluginInfo()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'author' => $this->author,
            'authorsite' => $this->authorSite,
            'version' => $this->version,
            'compatibility' => $this->compatibility,
            'codename' => $this->codename,
        ];
    }

    public function check()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->apiLink,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36"'
        ]);

        $response = json_decode(curl_exec($curl), true);
        
        curl_close($curl);

        if ($this->version != $response['tag_name']) {
            \flash_message('Używasz przestarzalej wersji '.$this->pluginName.'. <a href="'.$this->latestLink.'">Pobierz najnowsza wersje</a>', 'error');
        }
    }
}