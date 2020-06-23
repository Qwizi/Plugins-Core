<?php

declare(strict_types=1);

namespace Qwizi\Core;

class AdminForm
{
    public 
        $formInstance,
        $containerInstance;
    private 
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

    public function __construct($formInstance, $containerInstance, string $buttonMsg='Save')
    {
        $this->formInstance = $formInstance;
        $this->containerInstance = $containerInstance;
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

    private function generateInput(array $input)
    {
        if (array_key_exists('name', $input))
        {
            switch($input['name']) {
                case self::INPUT_TEXT:
                    return $this->formInstance->generate_text_box($input['name'], $input['value'], $input['options']);
                break;
                case self::INPUT_NUMERIC:
                    return $this->formInstance->generate_numeric_field($input['name'], $input['value'], $input['options']);
                break;
                case self::INPUT_PASSWORD:
                    return $this->formInstance->generate_password_box($input['name'], $input['value'], $input['options']);
                break;
                case self::INPUT_FILE:
                    return $this->formInstance->generate_file_upload_box($input['name'], $input['options']);
                break;
                case self::INPUT_TEXTAREA:
                    return $this->formInstance->generate_text_area($input['name'], $input['value'], $input['options']);
                break;
                case self::INPUT_RADIO:
                    return $this->formInstance->generate_radio_button($input['name'], $input['value'], $input['label'], $input['options']);
                break;
                case self::INPUT_CHECKBOX:
                    return $this->formInstance->generate_check_box($input['name'], $input['value'],
                    $input['label'], $input['options']);
                break;
                case self::INPUT_SELECTBOX:
                    return $this->formInstance->generate_select_box($input['name'], $input['option_list'], $input['selected'], $input['options']);
                break;
                case self::INPUT_FORUMSELECT:
                    return $this->formInstance->generate_forum_select($input['name'], $input['selected'], $input['options'], $input['is_first']);
                break;
                case self::INPUT_GROUPSELECT:
                    return $this->formInstance->generate_group_select($input['name'], $input['selected'], $input['options']);
                break;
                case self::INPUT_PREFIXSELECT:
                    return $this->formInstance->generate_prefix_select($input['name'], $input['selected'], $input['options']);
                break;
                default:
                    return $this->formInstance->generate_text_box($input['name'], $input['value'], $input['options']);
                break;
            }
        }
    }

    public function addRow(string $title, string $description, string $name, array $input=['name' => self::INPUT_FILE, 'value' => '', 'options' => []])
    {
        $this->rows[] = [
            'title' => $title,
            'description' => $description,
            'name' => $name,
            'input_type' => $this->generateInput($input),
        ];

        return $this;
    }

    public function row(string $title, string $description, string $name, array $input=['name' => self::INPUT_FILE, 'value' => '', 'options' => []])
    {
        return $this->addRow($title, $description, $name, $input);
    }
}