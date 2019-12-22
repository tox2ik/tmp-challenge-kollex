<?php

namespace kollex\Import\Adapter;

use kollex\Dataprovider\Assortment\Product;

/***
 * Transform an external product specification to our Product schema.
 *
 * @package kollex\Import\Adapter
 */
interface SchemaAdapterInterface
{
    public function decode($item): array;
    public function convert($item = null): Product;
}
