<?php

namespace kollex\kollex\Export;

use kollex\Entity\Product;

interface ExportFormatterInterface
{
    public function setItem(Product $item): self;

    public function serialize(): string;
}
