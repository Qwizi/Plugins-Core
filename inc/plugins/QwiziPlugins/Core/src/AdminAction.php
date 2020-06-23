<?php
declare(strict_types=1);

namespace Qwizi\Core;

abstract class AdminAction
{
    public $adminModule;
    private $action = '';
    private $method;
    private $errors = [];
    private $tables = [];
    private $forms = [];
    private $paginations = [];
    protected $headerTitle = '';
    protected $tab;
    

    public function __construct($adminModule)
    {
        $this->adminModule = $adminModule;
        $this->method = $adminModule->mybb->request_method;
    }

    abstract function get();
    abstract function post();

    public function getTab()
    {
        return $this->tab;
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

    private function generateTables()
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
                        $table->instance->construct_cell($cell['name'], $cell['options']);
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

    private function generateForms()
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

    private function generatePaginations()
    {
        if (!empty($this->paginations)) {
            foreach($this->paginations as $pagination) {
                if ($pagination->getNumRequest() > $pagination->getPerPageNum()) {
                    echo \draw_admin_pagination($pagination->getPageInput(), $pagination->getPerPageNum(), $pagination->getNumRequest(), $pagination->getLink());
                }
            }
        }
    }

    private function outputErrors()
    {
        if ($this->getErrors()) $this->adminModule->page->output_inline_error($this->getErrors());
    }

    public function handle() {
        if ($this->method == 'post') $this->post();

        $this->adminModule->page->output_header($this->headerTitle);
        $this->adminModule->page->add_breadcrumb_item($this->headerTitle);
        $this->adminModule->page->output_nav_tabs($this->adminModule->subTabs, $this->getTab());

        $this->outputErrors();
        $this->get();
        $this->generateTables();
        $this->generateForms();
        $this->generatePaginations();
         
        $this->adminModule->page->output_footer();    
    }
}