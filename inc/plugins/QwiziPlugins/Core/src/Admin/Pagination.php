<?php

declare(strict_types=1);

namespace Qwizi\Core\Admin;

class Pagination
{
    private
        $perPageNum,
        $numRequest,
        $pageInput,
        $link,
        $query,
        $start;

    public function __construct($perPageNum, $pageInput, $link)
    {
        $this->perPageNum = $perPageNum;
        if ($pageInput < 1) $pageInput = 1;
        $pageInput = intval($pageInput);
        $this->pageInput = $pageInput;
        $this->link = $link;
    }

    public function setQuery($query) {
        $this->query = $query;
    }

    public function setNumRequest($numRequest)
    {
        $this->numRequest = $numRequest;
    }

    public function getPerPageNum()
    {
        return $this->perPageNum;
    }

    public function getNumRequest()
    {
        return $this->numRequest;
    }

    public function getPageInput()
    {
        return $this->pageInput;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function countPages()
    {
        if(isset($this->pageInput)) {
            if ($this->pageInput > 0) {
                $current_page = $this->pageInput;
                $this->start = ($current_page - 1) * $this->perPageNum;
                $pages = $this->numRequest / $this->perPageNum;
                $pages = ceil($pages);
                if ($current_page > $pages) {
                    $this->start = 0;
                    $current_page = 1;
                }
            } else {
                $this->start = 0;
            }
        }
    }
}