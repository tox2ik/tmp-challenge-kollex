<?php

namespace kollex\Import;

use kollex\Logging\Reportable;

interface ReaderInterface extends Reportable
{
    /***
     * @return self Open the target (stream) and read all items.
     */
    public function open(): self;

    /***
     * @return iterable work with one item at a time
     */
    public function iterator(): iterable;

    /***
     * @return array|null retrieve all items
     */
    public function getAllItems(): ?array;

    /**
     * @return string[]
     */
    public function errors(): array;

    public function isErroneous(): bool;
}
