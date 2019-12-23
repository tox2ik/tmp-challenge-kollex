<?php


namespace kollex\Export;


use kollex\Entity\ProductEntity;

/**
 * @property ProductEntity current
 */
class JsonExporter implements ExportFormatterInterface
{

    public function setItem(ProductEntity $item): ExportFormatterInterface
    {
        $this->current = $item;
        return $this;
    }

    public function serialize(): string
    {
        return $this->current->serialize();
    }
}
