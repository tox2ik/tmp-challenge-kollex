<?php

namespace kollex\Import;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class JsonFileReaderTest extends TestCase
{

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    public $fs;

    public function setUp(): void
    {
        $this->fs = vfsStream::setup(
            'root',
            666,
            [
                'json' => [
                    'valid.json' => '{"data":[]}',
                    'nested.json' => '{"message": { "data": [1, 2, 3] }}',
                    'flat.json' => '[4, 4, 4] ',
                    'invalid.json' => '{',
                    'invalid2.json' => '',
                    'nodata.json' => '{ "a": 123 }'

                ]
            ]
        );
    }

    public function testMustProvidePath()
    {
        $this->expectException(\InvalidArgumentException::class);
        new JsonFileReader('');
    }

    public function testMustProvideDataPath()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/valid.json', ['dataPath' => 'data']);
        $this->assertEquals([], $reader->open()->getAllItems());
    }

    public function testFlatArray()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/flat.json');
        $this->assertEquals([4, 4, 4], $reader->open()->getAllItems());
    }


    public function testCanExtractNested()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/nested.json', ['dataPath' => 'message.data']);
        $this->assertEquals([1, 2, 3], $reader->open()->getAllItems());
    }

    public function testIterator()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/nested.json', ['dataPath' => 'message.data']);
        $iterator = $reader->open()->iterator();
        foreach ([1, 2, 3] as $item) {
            $this->assertEquals($item, $iterator->current());
            $iterator->next();
        }
    }

    public function testNullItemsIfNotOpen()
    {
        $reader = new JsonFileReader('/file');
        $this->assertEquals(null, $reader->getAllItems());
    }

    public function testBrokenInput()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/invalid.json');
        $this->assertEquals(null, $reader->open()->getAllItems());
        $this->assertEquals(true, $reader->isErroneous());
    }

    public function testBrokenInput2()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/invalid2.json');
        $this->assertEquals(null, $reader->open()->getAllItems());
        $this->assertEquals(true, $reader->isErroneous());
    }

    public function testParseErrors()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/invalid.json');
        $reader->open();
        $this->assertEquals(['Syntax error'], $reader->errors());
        $this->assertEquals(true, $reader->isErroneous());
    }

    public function testNoDataItems()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/nodata.json');
        $reader->open();
        $this->assertEquals(true, $reader->isErroneous());
    }

    public function testWrongDataPath()
    {
        $reader = new JsonFileReader($this->fs->url() . '/json/nodata.json', ['dataPath' => 'not.in.document']);
        $reader->open();
        $this->assertEquals(['Specified data path is not in the json document.'], $reader->errors());
    }
}
