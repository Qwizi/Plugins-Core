<?php
declare(strict_types=1);

namespace Qwizi\Core\Admin;

abstract class Action
{
    protected $tag;
    protected $title;
    protected $description;
    protected $action;
    protected $module_link;
    private $errors = [];
    private $tables = [];
    private $forms = [];
    private $paginations = [];


    abstract function get();
    abstract function post();

    public function __construct($module_link) {
        $this->module_link = $module_link;
    }

    public function setTab($tag, $title, $description, $action) {
        $this->tag = $tag;
        $this->title = $title;
        $this->description = $description;
        $this->action = $action;
    }

    public function getTag() {
        return $this->tag;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getAction() {
        return $this->action;
    }

    public function setError(string $errContent)
    {
        $this->errors[] = $errContent;
    }

    public function getErrors() 
    {
        return $this->errors;
    }

    public function getTables()
    {
        return $this->tables;
    }

    public function addTable($table)
    {
        $this->tables[] = $table;
    }

    public function getForms()
    {
        return $this->forms;
    }

    public function addForm($form)
    {
        $this->forms[] = $form;
    }

    public function getPaginations()
    {
        return $this->paginations;
    }

    public function addPagination($pagination)
    {
        $this->paginations[] = $pagination;
    }

    public function generateTables()
    {
        if (!empty($this->tables)) {
            foreach ($this->tables as $table) {
                    //var_dump($table['headers']);
                if (!empty($table->getHeaders())) {
                    foreach($table->getHeaders() as $header) {
                        $table->instance->construct_header($header['name'], $header['options']);
                    }
                }

                if (!empty($table->getCells())) {
                    foreach($table->getCells() as $cell) {
                        foreach($cell as $c) {
                            $table->instance->construct_cell($c[0], $c[1]);
                        }
                        
                        $table->instance->construct_row();
                    }
                } 
                    
                if ($table->instance->num_rows() == 0) {
                    $table->instance->construct_cell($table->getEmptyMsg(), ['colspan' => $table->getEmptyColspan()]);
                    $table->instance->construct_row();
                    $no_results = true;
                }

                $table->instance->output($table->getTitle());
            }
        }
    }

    public function generateForms()
    {
        if (!empty($this->forms)) {
            foreach($this->forms as $form) {
                if (!empty($form->getRows())) {
                    foreach($form->getRows() as $row) {
                        $form->containerInstance->output_row(
                            $row['title'],
                            $row['description'],
                            $row['input_type'],
                            $row['name']
                        );
                    }
                }
                $form->containerInstance->end();

                $buttons[] = $form->formInstance->generate_submit_button($form->getButtonMsg());
                $form->formInstance->output_submit_wrapper($buttons);

                $form->formInstance->end();
            }
        }
    }

    public function generatePaginations()
    {
        if (!empty($this->paginations)) {
            foreach($this->paginations as $pagination) {
                if ($pagination->getNumRequest() > $pagination->getPerPageNum()) {
                    echo \draw_admin_pagination($pagination->getPageInput(), $pagination->getPerPageNum(), $pagination->getNumRequest(), $pagination->getLink());
                }
            }
        }
    }

    public function outputErrors()
    {
        global $page;
        if ($this->getErrors()) $page->output_inline_error($this->getErrors());
    }

    public function generate() {
        $this->generateTables();
        $this->generateForms();
        $this->generatePaginations();
    }

    /*public function handle() {
        global $mybb;
        if ($mybb->request_method == 'post') $this->post();

        global $page;

        $page->output_header($this->headerTitle);
        $page->add_breadcrumb_item($this->headerTitle);
        $page->output_nav_tabs($this->adminModule->getTabs(), $this->activeTab);

        $this->outputErrors();
        $this->get();
        $this->generate();
         
        $page->output_footer();    
    }*/
}