<?php
declare(strict_types=1);

namespace Qwizi\Core;

class Ajax
{
    const HTTP_STATUS_OK = 200;
    const HTTP_STATUS_CREATED = 201;
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNAUTHORIZED = 401;
    const HTTP_STATUS_NOT_FOUND = 404;
    const HTTP_STATUS_REQUEST_NOT_ALLOWED = 405;

    static function setJsonContentType($charset) {
        header("Content-type: application/json; charset={$charset}");
    }

    static function response(array $data, int $status): void {
        echo json_encode($data);
        http_response_code($status);
        exit;
    }

    static function error(string $msg, int $status): void {
        self::response(['error' => $msg], $status);
    }

    static function pagination($count, $perpage, $page) {
        if ($count < $perpage) {
            return null;
        }

        $page = (int)$page;
        
        $pages = ceil($count / $perpage);

        if(!$mybb->settings['maxmultipagelinks'])
        {
            $mybb->settings['maxmultipagelinks'] = 5;
        }

        $from = $page-floor($mybb->settings['maxmultipagelinks']/2);
        $to = $page+floor($mybb->settings['maxmultipagelinks']/2);

        if($from <= 0)
        {
            $from = 1;
            $to = $from+$mybb->settings['maxmultipagelinks']-1;
        }
    
        if($to > $pages)
        {
            $to = $pages;
            $from = $pages-$mybb->settings['maxmultipagelinks']+1;
            if($from <= 0)
            {
                $from = 1;
            }
        }
    
        if($to == 0)
        {
            $to = $pages;
        }
        $next = null;

        if($page < $pages)
        {
            $next = $page+1;
        }

        return $next;
    }
}