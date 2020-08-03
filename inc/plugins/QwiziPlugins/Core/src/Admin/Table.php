<?php

declare(strict_types=1);

namespace Qwizi\Core\Admin;

use \Table as MybbTable;

class Table
{
    protected 
        $title,
        $headers = [],
        $cells = [],
        $emptyMsg = 'No records',
        $emptyColspan = 4;
    public $instance;

    public function __construct(string $title, string $emptyMsg='No records', int $emptyColspan=4)
    {
        $this->instance = new MybbTable;
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

    public function addCells(array $cells) {
        array_push($this->cells, $cells);
    }

    public function cells(array $cells) {
        $this->addCells($cells);
    }

    public function getCells()
    {
        return $this->cells;
    }
}