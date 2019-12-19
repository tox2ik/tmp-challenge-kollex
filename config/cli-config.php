<?php

require_once __DIR__ . '/../src/bootstrap/orm.php';
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(initOrm());
