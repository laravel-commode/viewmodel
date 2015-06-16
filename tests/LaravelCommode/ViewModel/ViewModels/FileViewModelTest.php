<?php

namespace LaravelCommode\ViewModel\ViewModels;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileViewModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FileViewModel|Mock
     */
    private $testInstance;

    /**
     * @var UploadedFile|Mock
     */
    private $fileMock;

    protected function setUp()
    {
        $this->testInstance = $this->getMockForAbstractClass(FileViewModel::class);
        $this->fileMock = $this->getMockForAbstractClass(UploadedFile::class, [__FILE__, basename(__FILE__)], '', true);
        parent::setUp();
    }

    public function testFill()
    {
        $attributes = ['file' => $this->fileMock];

        $property = new \ReflectionProperty($this->testInstance, 'attributes');
        $property->setAccessible(true);
        $property->setValue($this->testInstance, ['file']);
        $property->setAccessible(false);

        $this->testInstance->fill($attributes);
    }

    protected function tearDown()
    {
        unset($this->testInstance, $this->fileMock);
        parent::tearDown();
    }
}
