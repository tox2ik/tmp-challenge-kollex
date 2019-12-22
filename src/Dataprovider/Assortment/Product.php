<?php

namespace kollex\Dataprovider\Assortment;

interface Product
{
    public function defineValidations(): array;
    public function identify(): string;
}
