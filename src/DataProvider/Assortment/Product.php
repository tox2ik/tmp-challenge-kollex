<?php

namespace kollex\DataProvider\Assortment;

interface Product
{
    public function defineValidations(): array;
    public function identify(): string;
}
