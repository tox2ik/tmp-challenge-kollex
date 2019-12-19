<?php

namespace kollex\Entity;

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
}
