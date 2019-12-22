<?php

namespace kollex\Import;

use kollex\DataProvider\Assortment\DataProvider;
use kollex\DataProvider\Assortment\Product;

interface SourceInterface extends DataProvider
{

    /**
     * @return Product[]
     */
    public function importAll(): iterable;
}
