<?php

namespace kollex\Import\Adapter;

use kollex\Dataprovider\Assortment\Product;

interface SchemaAdapterInterface
{
    public function convert($item = null): Product;
}
