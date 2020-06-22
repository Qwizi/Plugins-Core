<?php

declare(strict_types=1);

namespace Qwizi\Core;

class AdminTable
{
    protected $title;
    protected $headers = [];
    protected $cells = [];
    protected $emptyMsg = 'No records';
    protected $emptyColspan = 4;
    public $instance;

    public function __construct($instance, string $title, string $emptyMsg='No records', int $emptyColspan=4)
    {
        $this->instance = $instance;
        $this->title = $title;
        $this->emptyMsg = $emptyMsg;
        $this->emptyColspan = $emptyColspan;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getEmptyMsg()
    {
        return $this->emptyMsg;
    }

    public function getEmptyColspan()
    {
        return $this->emptyColspan;
    }

    public function addHeader(string $name, array $options=[]) {
        $this->headers[] = ['name' => $name, 'options' => $options];

        return $this;
    }

    public function header(string $name, array $options=[])
    {
        return $this->addHeader($name, $options);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function addCell(string $name, array $options=[]) {
        $this->cells[] = ['name' => $name, 'options' => $options];

        return $this;
    }

    public function cell(string $name, array $options=[])
    {
        return $this->addCell($name, $options);
    }

    public function getCells()
    {
        return $this->cells;
    }
}