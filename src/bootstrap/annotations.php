<?php

use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/../../vendor/autoload.php';

$yaml = Yaml::parseFile(__DIR__ . '/../../swagger.yaml');


foreach ($yaml['definitions']['Product']['properties'] as $prop => $def) {
    $enum = $def['enum'] ?? null;
    $enums = $enum ? "ENUM: " . join(', ', $enum) : '';

    if (isset($def['format']) && $def['format'] == 'float') {
        $def['type'] = 'float';
        unset($def['format']);
    }

    echo "
/**
 * {$def['description']}
 *
 * Example:
 * {$def['example']}
 *
 * @ORM\Column(type=\"{$def['type']}\") 
 * @var {$def['type']}
 *
 * $enums
 */
 protected \$$prop;
";

    unset($def['description']);
    unset($def['type']);
    unset($def['example']);
    unset($def['enum']);

    if ($def) {
        print_r($def);
        exit;
    }
}
