<?php

namespace kollex\Import;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class JsonFileReader implements ReaderInterface
{
    /**
     * path to the array of items within the json file. Typically just 'data'
     * @var string
     */
    protected $dataPath;

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
     * JsonFileReader constructor.
     * @param string $path
     * @param array $options = [
     *     'dataPath' => '/data/patt.txt'
     * ]
     */
    public function __construct(string $path, array $options = [])
    {
        if (!strlen($path)) {
            throw new \InvalidArgumentException("Please provide a path");
        }
        $this->path = $path;
        $this->dataPath = $options['dataPath'] ?? '';
        $this->options = $options;
    }

    public function open(): ReaderInterface
    {
        $fileContents = file_get_contents($this->path);
        $json = $this->jsonDecode($fileContents, $this->errors);

        $items = $json;
        if (strlen($this->dataPath)) {
            try {
                $items = (new PropertyAccessor())->getValue($items, $this->dataPath);
            } catch (NoSuchPropertyException $wrongProp) {
                $this->errors[] = 'Specified data path is not in the json document.';
            }
        }
        $this->items = is_array($items) ? $items : null;
        return $this;
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

    /**
     * @param string $json
     * @param array $errors populated with errors if detected
     * @param bool $assoc [optional] return as array/dictionary
     * @param int $depth [optional] User specified recursion depth.
     * @param int $options See json_decode (JSON_BIGINT_AS_STRING)
     *
     * @return array|object|null
     */
    protected function jsonDecode(
        $json,
        &$errors = [],
        $assoc = false,
        $depth = 512,
        $options = 0
    ) {
        $result = json_decode($json, $assoc, $depth, $options);
        if (!empty($json) && $result === null && json_last_error() != JSON_ERROR_NONE) {
            //error_log( sprintf( '%s: Error while decoding; %s', __FUNCTION__, json_last_error_msg()));
            $errors[] = json_last_error_msg();
        }
        return $result;
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
