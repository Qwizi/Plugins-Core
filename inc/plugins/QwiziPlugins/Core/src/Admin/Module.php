<?php
declare(strict_types=1);

namespace Qwizi\Core\Admin;

class Module
{
    public $module;
    public $module_link;
    public $tabs = [];
    public $actions = [];

    public function __construct(string $module) {
        $this->module = \htmlspecialchars_uni($module);
        $this->module_link = \sprintf("index.php?module=%s", $module);
    }

    public function getModule(): string 
    {
        return $this->module;
    }

    public function getLink(): string
    {
        return $this->module_link;
    }

    private function formatTag($tag) {
        return sprintf("%s_%s", $tag, $this->module);
    }

    public function setActiveTab($tag) {
        $tag = $this->formatTag($tag);
        $this->activeTab = $tag;
        $this->action = $this->getTab($tag)['action'];
    }

    public function getTab(string $tag): array
    {
        $tag = $this->formatTag($tag);
        return $this->tabs[$tag];
    }

    private function generateActionWithAdditionals($action, $additionals) {
        if ($action !== '') {
            $action = sprintf("%s&amp;", $action);
            if (!empty($additionals)) {
                foreach($additionals as $key => $value) {
                    $action .= sprintf("%s=%s", $key, $value);
                }
            }
        }
        print_r($action);
        return $action;
    }

    private function addTab(string $tag, string $title, string $description, string $action, array $additionals=[]) {
        $tag = \htmlspecialchars_uni($tag);
        $title = \htmlspecialchars_uni($title);
        $description = \htmlspecialchars_uni($description);
        $action = \htmlspecialchars_uni($action);
        $action = $this->generateActionWithAdditionals($action, $additionals);

        $link = '';
        if ($action === '') {
            $link = $this->module_link;
        } else {
            $link = sprintf("%s&amp;action=%s", $this->module_link, $action);
        }

        $this->tabs[$tag] = [
            'title' => $title,
            'link' => $link,
            'description' => $description,
        ];
        return $this;
    }

    public function addAction(object $instance)
    {
        $this->actions[] = ['instance' => $instance ];
        if ($instance->getOnActiveOnly()) {
            global $mybb;
            if ($mybb->input['action'] === $instance->getAction()) {
                $this->addTab(
                    $instance->getTag(), 
                    $instance->getTitle(),
                    $instance->getDescription(),
                    $instance->getAction(),
                    $instance->getAdditionals()
                );
            }
        } else {
            $this->addTab(
                $instance->getTag(), 
                $instance->getTitle(),
                $instance->getDescription(),
                $instance->getAction(),
                $instance->getAdditionals()
            );
        }
        return $this;
    }

    public function handleActions() 
    {
        global $mybb, $page;
        if (!empty($this->actions)) {
            foreach ($this->actions as $action) {
                $actionInstance = $action['instance'];
                $page->add_breadcrumb_item('Commands');

                if ($mybb->input['action'] == $actionInstance->getAction()) {

                    if ($mybb->request_method == 'post') {
                        $actionInstance->post(); 
                    }
                    
                    $page->output_header('Commands');
                    $page->add_breadcrumb_item('Commands');
                    $page->output_nav_tabs($this->tabs, $actionInstance->getTag());
                    
                    $actionInstance->outputErrors();

                    $actionInstance->get();
                    $actionInstance->generate(); 
                    $page->output_footer();   
                }
            }
        }
    }
}                                                                                                       