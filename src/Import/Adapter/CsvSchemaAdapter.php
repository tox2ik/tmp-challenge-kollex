<?php

namespace kollex\Import\Adapter;

use kollex\DataProvider\Assortment\Product;

class CsvSchemaAdapter implements SchemaAdapterInterface
{
    public function convert($item = null): Product
    {
    }

    public function decode($item): array
    {
        // TODO: Implement decode() method.
    }
}
