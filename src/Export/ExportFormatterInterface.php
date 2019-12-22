<?php

namespace kollex\kollex\Export;

use kollex\Entity\ProductEntity;

interface ExportFormatterInterface
{
    public function setItem(ProductEntity $item): self;

    public function serialize(): string;
}
