<?php

declare(strict_types=1);

namespace Qwizi\Core\Admin;

use \Form as MybbForm;
use \FormContainer;

class Form
{
    public 
        $formInstance,
        $containerInstance;
    private
        $link,
        $method,
        $title, 
        $rows = [],
        $buttonMsg;

    const 
        INPUT_TEXT = 'text',
        INPUT_TEXTAREA = 'textarea',
        INPUT_NUMERIC = 'numeric',
        INPUT_PASSWORD = 'password',
        INPUT_FILE = 'file',
        INPUT_RADIO = 'radio',
        INPUT_CHECKBOX = 'checkbox',
        INPUT_SELECTBOX = 'selectbox',
        INPUT_FORUMSELECT = 'forumselect',
        INPUT_GROUPSELECT = 'groupselect',
        INPUT_PREFIXSELECT = 'prefixselect';

    public function __construct(string $link, string $method, string $title, string $buttonMsg='Save')
    {
        $this->link = $link;
        $this->method = $method;
        $this->title = $title;
        $this->formInstance = new MybbForm($link, $method);
        $this->containerInstance = new FormContainer($title);
        $this->buttonMsg = $buttonMsg;
    }

    public function getButtonMsg()
    {
        return $this->buttonMsg;
    }

    public function getRows()
    {
        return $this->rows;
    }

    private function generateInput(array $input, string $name)
    {
        if (array_key_exists('type', $input))
        {
            switch($input['type']) {
                case self::INPUT_TEXT:
                    return $this->formInstance->generate_text_box($name, $input['value'], $input['options']);
                break;
                case self::INPUT_NUMERIC:
                    return $this->formInstance->generate_numeric_field($name, $input['value'], $input['options']);
                break;
                case self::INPUT_PASSWORD:
                    return $this->formInstance->generate_password_box($name, $input['value'], $input['options']);
                break;
                case self::INPUT_FILE:
                    return $this->formInstance->generate_file_upload_box($name, $input['options']);
                break;
                case self::INPUT_TEXTAREA:
                    return $this->formInstance->generate_text_area($name, $input['value'], $input['options']);
                break;
                case self::INPUT_RADIO:
                    return $this->formInstance->generate_radio_button($name, $input['value'], $input['label'], $input['options']);
                break;
                case self::INPUT_CHECKBOX:
                    return $this->formInstance->generate_check_box($name, $input['value'],
                    $input['label'], $input['options']);
                break;
                case self::INPUT_SELECTBOX:
                    return $this->formInstance->generate_select_box($name, $input['option_list'], $input['selected'], $input['options']);
                break;
                case self::INPUT_FORUMSELECT:
                    return $this->formInstance->generate_forum_select($name, $input['selected'], $input['options'], $input['is_first']);
                break;
                case self::INPUT_GROUPSELECT:
                    return $this->formInstance->generate_group_select($name, $input['selected'], $input['options']);
                break;
                case self::INPUT_PREFIXSELECT:
                    return $this->formInstance->generate_prefix_select($name, $input['selected'], $input['options']);
                break;
                default:
                    return $this->formInstance->generate_text_box($name, $input['value'], $input['options']);
                break;
            }
        }
    }

    public function addRow(string $name, string $title, string $description, array $input=['type' => self::INPUT_TEXT, 'value' => '', 'options' => []])
    {
        $this->rows[] = [
            'name' => $name,
            'title' => $title,
            'description' => $description,
            'input_type' => $this->generateInput($input, $name),
        ];

        return $this;
    }

    public function row(string $name, string $title, string $description, array $input=['type' => self::INPUT_TEXT, 'value' => '', 'options' => []])
    {
        return $this->addRow($name, $title, $description, $input);
    }
}