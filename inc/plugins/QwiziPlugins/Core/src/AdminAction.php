<?php
declare(strict_types=1);

namespace Qwizi\Core;

use \Qwizi\Core\AdminTable;

abstract class AdminAction
{
    public $adminModule;
    private $action = '';
    private $method;
    private $errors = [];
    private $tables = [];
    private $forms = [];
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

    public function handle() {
        if ($this->method == 'post') $this->post();

        $this->adminModule->page->output_header($this->headerTitle);
        $this->adminModule->page->add_breadcrumb_item($this->headerTitle);
        $this->adminModule->page->output_nav_tabs($this->adminModule->subTabs, $this->getTab());

        $errors = $this->getErrors();
        if ($errors) {
            $this->adminModule->page->output_inline_error($errors);
            var_dump($errors);
        }

        $this->get();
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
                           
                    }
                    $table->instance->construct_row();
                } 
                    
                if ($table->instance->num_rows() == 0) {
                    $table->instance->construct_cell($table->getEmptyMsg(), ['colspan' => $table->getEmptyColspan()]);
                    $table->instance->construct_row();
                    $no_results = true;
                }

                $table->instance->output($table->getTitle());
            }
        }

        if (!empty($this->forms)) {
            foreach($this->forms as $form) {
                if (!empty($form->getRows())) {
                    foreach($form->getRows() as $row) {
                        switch($row['input_type']['name']) {
                            case 'text':
                                $input = $form->formInstance->generate_text_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                            break;

                            case 'numeric':
                                $input = $form->formInstance->generate_numeric_field($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                            break;

                            case 'password':
                                $input = $form->formInstance->generate_password_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                            break;

                            case 'file':
                                $input = $form->formInstance->generate_file_upload_box($row['input_type']['name'], $row['input_type']['options']);
                            break;

                            case 'textarea':
                                $input = $form->formInstance->generate_text_area($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                            break;

                            case 'radio':
                                $input = $form->formInstance->generate_radio_button($row['input_type']['name'], $row['input_type']['default'],
                                $row['input_type']['label'], $row['input_type']['options']);
                            break;

                            case 'checkbox':
                                $input = $form->formInstance->generate_check_box($row['input_type']['name'], $row['input_type']['default'],
                                $row['input_type']['label'], $row['input_type']['options']);
                            break;

                            case 'selectbox':
                                $input = $form->formInstance->generate_select_box($row['input_type']['name'], $row['input_type']['option_list'],
                                $row['input_type']['selected'], $row['input_type']['options']);
                            break;

                            case 'forumselect':
                                $input = $form->formInstance-> generate_forum_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options'], $row['input_type']['is_first']);
                            break;

                            case 'groupselect':
                                $input = $form->formInstance-> generate_group_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options']);
                            break;

                            case 'prefixselect':
                                $input = $form->formInstance-> generate_prefix_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options']);
                            break;

                            default:
                                $input = $form->formInstance->generate_text_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                            break;
                        }
                        $form->containerInstance->output_row(
                            $row['title'],
                            $row['description'],
                            $input,
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
        $this->adminModule->page->output_footer();    
    }
}