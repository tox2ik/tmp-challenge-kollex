<?php

namespace kollex\Import;

use kollex\Dataprovider\Assortment\Product;
use kollex\Import\Adapter\SchemaAdapterInterface;

class FileSource implements SourceInterface
{

    /**
     * @var ReaderInterface
     */
    private $param;

    /**
     * @var SchemaAdapterInterface
     */
    private $param1;

    public function __construct(ReaderInterface $param, SchemaAdapterInterface $param1)
    {
        $this->param = $param;
        $this->param1 = $param1;
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
    }
}
