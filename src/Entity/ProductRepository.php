<?php

namespace kollex\Entity;

use kollex\DataProvider\Assortment\Product;

class ProductRepository
{

    /**
     * @var ProductMapper
     */
    protected $mapper;

    public function __construct(ProductMapper $param)
    {
        $this->mapper = $param;
    }

    public function saveMany(array $items): void
    {
        $this->mapper->saveMany($items);
    }

    /**
     * @return Product[]
     */
    public function findAll(): array
    {
        return $this->mapper->retrieveAll();
    }
}
