<?php

namespace kollex\Import\Adapter;

use kollex\Dataprovider\Assortment\Product;

class CsvSchemaAdapter implements SchemaAdapterInterface
{
    public function convert($item = null): Product
    {
    }

    public function decode($item): object
    {
        // TODO: Implement decode() method.
    }
}
