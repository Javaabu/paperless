<?php

namespace Javaabu\Paperless\Support\Notifications\Markdown\Tables;

class MarkdownRenderer
{
    public int $maxlength = 1000;

    private array $data = [];

    private array $header = [];

    private array $len = [];

    private array $align = [];

    /**
     * @param  array|null  $headers    The header array [key => label, ...]
     * @param  array       $rows       Content
     * @param  bool|array  $alignment  Alignment optios [key => L|R|C, ...]
     */
    public function __construct(array $headers = null, array $rows = [], bool|array $alignment = false)
    {
        if ($headers) {
            $this->header = $headers;
        } elseif ($rows) {
            foreach ($rows[0] as $key => $value)
                $this->header[$key] = $key;
        }

        foreach ($this->header as $key => $label) {
            $this->len[$key] = strlen($label);
        }

        if (is_array($alignment))
            $this->setAlgin($alignment);

        $this->addData($rows);
    }

    /**
     * Overwrite the alignment array
     *
     * @param  array  $align  Alignment optios [key => L|R|C, ...]
     */
    public function setAlgin(array $align): void
    {
        $this->align = $align;
    }

    /**
     * Add data to the table
     *
     * @param  array  $content  Content
     */
    public function addData(array $content): static
    {
        foreach ($content as &$row) {
            foreach ($this->header as $key => $value) {
                if (! isset($row[$key])) {
                    $row[$key] = '-';
                } elseif (strlen($row[$key]) > $this->maxlength) {
                    $this->len[$key] = $this->maxlength;
                    $row[$key] = substr($row[$key], 0, $this->maxlength - 3) . '...';
                } elseif (strlen($row[$key]) > $this->len[$key]) {
                    $this->len[$key] = strlen($row[$key]);
                }
            }
        }

        $this->data = $this->data + $content;
        return $this;
    }

    /**
     * Add a delimiter
     *
     * @return string
     */
    private function renderDelimiter(): string
    {
        $res = '|';
        foreach ($this->len as $key => $l)
            $res .= (isset($this->align[$key]) && ($this->align[$key] == 'C' || $this->align[$key] == 'L') ? ':' : ' ')
                . str_repeat('-', $l)
                . (isset($this->align[$key]) && ($this->align[$key] == 'C' || $this->align[$key] == 'R') ? ':' : ' ')
                . '|';
        return $res . "\r\n";
    }

    /**
     * Render a single row
     *
     * @param  array  $row
     * @return string
     */
    private function renderRow(array $row): string
    {
        $res = '|';
        foreach ($this->len as $key => $l) {
            $res .= ' ' . $row[$key] . ($l > strlen($row[$key]) ? str_repeat(' ', $l - strlen($row[$key])) : '') . ' |';
        }

        return $res . "\r\n";
    }

    /**
     * Render the table
     *
     * @param  array  $content  Additional table content
     * @return string
     */
    public function render(array $content = []): string
    {
        $this->addData($content);

        $res = $this->renderRow($this->header)
            . $this->renderDelimiter();
        foreach ($this->data as $row)
            $res .= $this->renderRow($row);

        return $res;
    }
}
