<?php

namespace kollex\Import;

class CsvFileReader implements ReaderInterface
{
    /**
     * @var array
     */
    public $headers = [];
    /**
     * @var false|resource
     */
    protected $fh;

    /**
     * @var array|null
     */
    protected $items;

    /** @var string[] */
    protected $errors = [];

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param string $path
     * @param array $options = [
     *     'delimiter' => ',',
     *     'enclosure' => '"',
     *     'escape' => '\\',
     *     'longestLine' => 0
     * ]
     */
    public function __construct(string $path, array $options = [])
    {
        if (!strlen($path)) {
            throw new \InvalidArgumentException("Please provide a path");
        }
        $this->path = $path;
        $this->options = $options + [
                'delimiter' => ',',
                'enclosure' => '"',
                'escape' => '\\',
                'longestLine' => 0
            ];
        //$this->dataPath = $options['dataPath'] ?? '';
    }

    public function open(): ReaderInterface
    {
        $items = [];
        $this->fh = fopen($this->path, 'r');
        $this->headers = array_map('trim', $this->readRecord());
        do {
            $record = $this->readRecord();
            if (count($record) == count($this->headers)) {
                $items[] = array_combine($this->headers, $record);
            }
        } while (is_resource($this->fh));
        $this->items = array_values(array_filter($items));
        return $this;

    }
    
    public function readRecord(): array
    {
        $length = $this->options['longestLine'];
        $delimiter = $this->options['delimiter'];
        $enclosure = $this->options['enclosure'];
        $escape = $this->options['escape'];
        $items = fgetcsv($this->fh, $length, $delimiter, $enclosure, $escape);
        if (false === $items) {
            fclose($this->fh);
            return [];
        } elseif (null === $items) {
            return [];
        } elseif (is_array($items) && count($items) == 1 && null == $items[0]) {
            return  [];
        }
        return $items;
        
    }

    public function iterator(): iterable
    {
        foreach ($this->items as $item) {
            yield $item;
        }
    }

    public function getAllItems(): ?array
    {
        return $this->items;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function isErroneous(): bool
    {
        return $this->items == null || count($this->errors) > 0;
    }
}
