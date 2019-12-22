<?php

namespace kollex\Logging;

interface Reportable
{

    public function errors(): array;
    public function isErroneous(): bool;
}
