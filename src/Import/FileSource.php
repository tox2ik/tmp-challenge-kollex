<?php

namespace kollex\Import;

use kollex\DataProvider\Assortment\Product;
use kollex\Import\Adapter\SchemaAdapterInterface;
use kollex\Logging\Reportable;
use RuntimeException;

class FileSource implements SourceInterface, Reportable
{
    /**
     * @var array
     */
    public $runtimeEx = [];
    /**
     * @var array
     */
    public $errors = [];
    /**
     * @var array
     */
    public $warnings = [];

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var SchemaAdapterInterface
     */
    private $adapter;
    /**
     * @var ProductValidator
     */
    private $validator;

    public function __construct(
        ProductValidator $validator,
        SchemaAdapterInterface $adapter,
        ReaderInterface $reader
    ) {
        $this->reader = $reader;
        $this->adapter = $adapter;
        $this->validator = $validator;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->importAll();
    }

    /**
     * @return Product[]
     */
    public function importAll(): array
    {
        $products = [];

        try {
            $this->reader->open();
        } catch (RuntimeException $re) {
            $this->runtimeEx[] = $re;
        }
        $iterator = $this->reader->iterator();

        foreach ($iterator as $i => $item) {
            if (null === $item) {
                $this->warnings[] = sprintf("The reader generated a null item, index:%d", $i);
                continue;
            }
            try {
                $properties = $this->adapter->decode($item);
                $product = $this->adapter->convert($properties);
                $errors = $this->validator->validate($product);
                if (count($errors) == 0) {
                    $products[] = $product;
                } else {
                    array_splice($this->errors, count($this->errors), 0, $errors);
                }
            } catch (RuntimeException $re) {
                $this->runtimeEx[] = $re;
            }
        }
        return $products;
    }

    public function generateReport()
    {
        return array_merge($this->errors(), $this->warnings);
    }

    public function errors(): array
    {
        $text = [];
        foreach ($this->runtimeEx as $runtimeEx) {
            $text[] = $runtimeEx->getMessage();
        }
        return array_merge($text, $this->errors);
    }

    public function isErroneous(): bool
    {
        return count($this->runtimeEx) + count($this->errors()) >= 1;
    }
}
