<?php

namespace Javaabu\Paperless\Support\Notifications\Markdown\Tables;

class Table
{
    public static function joinAndWrap($data)
    {
        return str(collect($data)->join('|'))->prepend('|')->append('|');
    }

    public static function makeRows($data): array
    {
        $rows = [];

        foreach ($data as $row) {
            $rows[] = static::joinAndWrap($row);
        }

        return $rows;
    }
}
