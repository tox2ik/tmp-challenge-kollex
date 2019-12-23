<?php

namespace kollex\Export;

use kollex\Entity\ProductEntity;

interface ExportFormatterInterface
{
    public function setItem(ProductEntity $item): self;

    /***
     * @return string render current item non-abiquously.
     */
    public function serialize(): string;
}
