<?php

namespace kollex\DataProvider\Assortment;

interface DataProvider
{
    /**
     * @return Product[]
     */
    public function getProducts(): array;
}
