<?php

namespace kollex\Import;

use kollex\Entity\ProductEntity;
use kollex\Import\Adapter\SchemaAdapterInterface;
use PHPUnit\Framework\TestCase;

class FileSourceTest extends TestCase
{
    public function testImportAll_NoFile_NoProducts()
    {
        $reader = $this->createMock(ReaderInterface::class);
        $adapter = $this->createMock(SchemaAdapterInterface::class);
        $validator = $this->createMock(ProductValidator::class);
        $this->assertEquals([], (new FileSource($validator, $adapter, $reader))->importAll());
    }

    public function testGetProducts_PoorUpstreamFunctionName_MustSupport()
    {
        $source = $this->createPartialMock(FileSource::class, [ 'importAll' ]);
        $source->expects($this->once())->method('importAll')->willReturn([]);
        $this->assertEmpty($source->getProducts());
    }

    public function testImportAProduct()
    {
        $reader = $this->createMock(ReaderInterface::class);
        $adapter = $this->createMock(SchemaAdapterInterface::class);
        $validator = $this->createMock(ProductValidator::class);
        $params = ['id' => '.', 'name' => '.'];
        $reader->expects($this->once())->method('iterator')->willReturn(new \ArrayIterator([ $params ]));
        $adapter->expects($this->once())->method('decode')->willReturn([ $params ]);
        $adapter->expects($this->once())->method('convert')->willReturn(new ProductEntity($params));
        $this->assertCount(1, (new FileSource($validator, $adapter, $reader))->importAll());
    }

    public function testGenerateReport_InvalidInput_GeneratesMessages()
    {

        $reader = $this->createMock(ReaderInterface::class);
        $adapter = $this->createMock(SchemaAdapterInterface::class);
        $validator = $this->createMock(ProductValidator::class);
        $reader->expects($this->once())->method('iterator')->willReturn(new \ArrayIterator([ true ]));
        $adapter->expects($this->once())->method('decode');
        $adapter->expects($this->once())->method('convert');
        $validator->expects($this->once())->method('validate')->willReturn(['Nope']);
        $source = new FileSource($validator, $adapter, $reader);
        $source->importAll();
        $this->assertEquals([ 'Nope' ], $source->generateReport());
        $this->assertTrue($source->isErroneous());
    }
}
