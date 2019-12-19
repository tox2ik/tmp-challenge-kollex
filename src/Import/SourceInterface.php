<?php

namespace kollex\Import;

use kollex\Dataprovider\Assortment\DataProvider;
use kollex\Dataprovider\Assortment\Product;

interface SourceInterface extends DataProvider
{

    /**
     * @return Product[]
     */
    public function importAll(): iterable;
}
