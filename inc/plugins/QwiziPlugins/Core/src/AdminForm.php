<?php

declare(strict_types=1);

namespace Qwizi\Core;

class AdminForm
{
    public $formInstance;
    public $containerInstance;
    private $rows = [];
    private $buttonMsg;

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

    public function addRow(string $title, string $description, string $name, array $inputType=['name' => 'text'], array $options=[])
    {
        $this->rows[] = [
            'title' => $title,
            'description' => $description,
            'name' => $name,
            'input_type' => $inputType,
            'options' => $options
        ];

        return $this;
    }

    public function row(string $title, string $description, string $name, array $inputType=['name' => 'text'], array $options=[])
    {
        return $this->addRow($title, $description, $name, $inputType, $options);
    }
}