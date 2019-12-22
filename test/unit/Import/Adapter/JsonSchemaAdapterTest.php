<?php

namespace kollex\Import\Adapter;

use PHPUnit\Framework\TestCase;

class JsonSchemaAdapterTest extends TestCase
{
    public function testDecode_UnknownUnit_NoNotice()
    {
        $fields = (new JsonSchemaAdapter)->decode(['PACKAGE' => 'bundle', 'VESSEL' => 'container']);
        $this->assertEquals(
            [
                'baseProductPackaging' => 'container',
                'packaging' => 'bundle',

            ],
            array_intersect_key($fields, ['packaging' => true, 'baseProductPackaging' => true])
        );
    }


    public function testDecode_UnknownParameter_NoNotice()
    {
        $fields = (new JsonSchemaAdapter)->decode(['RANDOM_PARAMETER' => 'bundle', 'UNK-UNK' => 'container']);
        $this->assertEquals(['LT'], array_values(array_filter($fields)));
    }
}
