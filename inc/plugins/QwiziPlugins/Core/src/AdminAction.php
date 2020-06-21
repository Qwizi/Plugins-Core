<?php
declare(strict_types=1);

namespace Qwizi\Core;

abstract class AdminAction
{
    public $adminModule;
    private $action = '';
    private $method;
    private $errors = [];
    protected $headerTitle = '';
    protected $tab;
    protected $tables;
    protected $forms;

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

    public function addTable(string $name, string $title, array $headers=[], array $cells=[], string $emptyMsg='No records', int $emptyColspan=4) {
        $this->tables[$name] = [
            'title' => $title, 
            'instance' => new \Table,
            'headers' => $headers,
            'cells' => $cells,
            'empty_msg' => $emptyMsg,
            'empty_colspan' => $emptyColspan
        ];
    }

    public function addForm(
        string $name, 
        string $moduleLink, 
        string $method='post',
        string $title,
        array $rows=[],
        string $buttonMsg='Save'
    )
    {
        $data = [$name, $moduleLink, $method, $title, $buttonMsg];

        foreach($data as $key => &$value) {
            \htmlspecialchars_uni($value);
        }

        $this->forms[$name] = [
            'instance' => new \Form($moduleLink, $method),
            'container_instance' => new \FormContainer($title),
            'rows' => $rows,
            'button_msg' => $buttonMsg
        ];
    }

    public function handle() {
        if ($this->method == 'post') {
            $this->post();
        }
        else {
            $this->adminModule->page->output_header($this->headerTitle);
            $this->adminModule->page->add_breadcrumb_item($this->headerTitle);
            $this->adminModule->page->output_nav_tabs($this->adminModule->subTabs, $this->getTab());

            $errors = $this->getErrors();
            if ($errors)
                $this->adminModule->page->output_inline_error($errors);
                var_dump($errors);

            $this->get();
            if (!empty($this->tables)) {
                foreach ($this->tables as $table) {
                    //var_dump($table['headers']);
                    if (!empty($table['headers'])) {
                        foreach($table['headers'] as $header) {
                            $table['instance']->construct_header($header['name'], $header['options']);
                        }
                    }

                    if (!empty($table['cells'])) {
                        foreach($table['cells'] as $cell) {
                            $table['instance']->construct_cell($cell['name'], $cell['options']);
                           
                        }
                        $table['instance']->construct_row();
                    } 
                    
                    if ($table['instance']->num_rows() == 0) {
                        $table['instance']->construct_cell($table['empty_msg'], ['colspan' => $table['empty_colspan']]);
                        $table['instance']->construct_row();
                        $no_results = true;
                    }

                    $table['instance']->output($table['title']);
                }
            }

            if (!empty($this->forms)) {
                foreach($this->forms as $form) {
                    if (!empty($form['rows'])) {
                        foreach($form['rows'] as $row) {
                            //var_dump($row['input_type']);
                            switch($row['input_type']['name']) {
                                case 'text':
                                    $input = $form['instance']->generate_text_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                                break;

                                case 'numeric':
                                    $input = $form['instance']->generate_numeric_field($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                                break;

                                case 'password':
                                    $input = $form['instance']->generate_password_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                                break;

                                case 'file':
                                    $input = $form['instance']->generate_file_upload_box($row['input_type']['name'], $row['input_type']['options']);
                                break;

                                case 'textarea':
                                    $input = $form['instance']->generate_text_area($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                                break;

                                case 'radio':
                                    $input = $form['instance']->generate_radio_button($row['input_type']['name'], $row['input_type']['default'],
                                    $row['input_type']['label'], $row['input_type']['options']);
                                break;

                                case 'checkbox':
                                    $input = $form['instance']->generate_check_box($row['input_type']['name'], $row['input_type']['default'],
                                    $row['input_type']['label'], $row['input_type']['options']);
                                break;

                                case 'selectbox':
                                    $input = $form['instance']->generate_select_box($row['input_type']['name'], $row['input_type']['option_list'],
                                    $row['input_type']['selected'], $row['input_type']['options']);
                                break;

                                case 'forumselect':
                                    $input = $form['instance']-> generate_forum_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options'], $row['input_type']['is_first']);
                                break;

                                case 'groupselect':
                                    $input = $form['instance']-> generate_group_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options']);
                                break;

                                case 'prefixselect':
                                    $input = $form['instance']-> generate_prefix_select($row['input_type']['name'], $row['input_type']['selected'], $row['input_type']['options']);
                                break;

                                default:
                                    $input = $form['instance']->generate_text_box($row['input_type']['name'], $row['input_type']['default'], $row['input_type']['options']);
                                break;
                            }
                            $form['container_instance']->output_row(
                                $row['title'],
                                $row['description'],
                                $input,
                                $row['name']
                            );
                        }
                    }
                    $form['container_instance']->end();

                    $buttons[] = $form['instance']->generate_submit_button($form['button_msg']);
                    $form['instance']->output_submit_wrapper($buttons);

                    $form['instance']->end();
                }
            }

            $this->adminModule->page->output_footer();
        }
    }
}