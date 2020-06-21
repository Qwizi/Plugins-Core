<?php
declare(strict_types=1);

namespace Qwizi\Core;

class AdminModule
{
    public $page;
    public $db;
    public $mybb;
    public $lang;
    public $module;
    public $moduleLink;
    public $subTabs = [];
    public $action;
    public $actions = [];
    public $method;

    public function __construct($mybb, $db, $page, $lang) 
    {
        $this->mybb = $mybb;
        $this->db = $db;
        $this->page = $page;
        $this->lang = $lang;
        $this->action = $mybb->input['action'];
    }

    public function registerModule(string $module)
    {
        $this->module = \htmlspecialchars_uni($module);
        $this->module_link = 'index.php?module='.$module;
    }

    public function getModule(): string 
    {
        return $this->module;
    }

    public function addSubTab(string $name, string $title, string $link, string $description) {
        $data = [$name, $title, $link, $description];

        foreach ($data as &$value)
            \htmlspecialchars_uni($value);

        $subLink = '';

        if ($data[2] == '') {
            $subLink = $this->module_link;
        } else {
            $subLink = $this->module_link.'&amp;'.$data[2];
        }
        
        $this->subTabs[$data[0]] = [
            'title' => $data[1],
            'link' => $subLink,
            'description' => $data[3]
        ];
        return $this;
    }

    public function getSubTab(string $name)
    {
        return [$name, $this->subTabs[$name]];
    }

    public function addAction(object $instance)
    {
        $this->actions[] = ['instance' => $instance ];
        return $this;
    }

    public function handleActions() 
    {
        if (!empty($this->actions)) {
            foreach ($this->actions as $action) {
                $actionInstance = $action['instance'];

                if ($this->action == $actionInstance->action)
                    $actionInstance->handle();
            }
        }
    }
}