<?php

namespace kollex\Import;

use kollex\Dataprovider\Assortment\Product;
use kollex\Import\Adapter\SchemaAdapterInterface;

class FileSource implements SourceInterface
{
    /**
     * @var array
     */
    public $runtimeEx = [];

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var SchemaAdapterInterface
     */
    private $adapter;

    public function __construct(ReaderInterface $reader, SchemaAdapterInterface $adapter)
    {
        $this->reader = $reader;
        $this->adapter = $adapter;
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
        } catch (\RuntimeException $re) {
            $this->runtimeEx[] = $re;
        }
        $iterator = $this->reader->iterator();
        foreach ($iterator as $item) {
            try {
                $product = $this->adapter->convert($item);

                $products[] = $product;
            } catch (\RuntimeException $re) {
                $this->runtimeEx[]= $re;
            }
        }
        return $products;
    }
}
