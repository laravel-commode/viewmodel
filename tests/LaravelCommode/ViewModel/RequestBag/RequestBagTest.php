<?php

namespace LaravelCommode\ViewModel\RequestBag;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class RequestBagTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RequestBag
     */
    private $testInstance;

    /**
     * @var mixed[]
     */
    private $fakeData = [];

    protected function generateFakeData()
    {
        return $this->fakeData = ['name' => uniqid(), 'name1' => uniqid()];
    }

    protected function setUp()
    {
        $this->testInstance = new RequestBag();

        parent::setUp();
    }

    public function testFill()
    {
        $this->testInstance->fill($this->generateFakeData());

        $this->assertSame($this->fakeData['name'], $this->testInstance->name);
        $this->assertTrue(isset($this->testInstance->name1));
        $this->assertSame($this->fakeData, $this->testInstance->getAll());
        $this->assertSame($this->fakeData, $this->testInstance->toArray());

        $this->assertTrue($this->testInstance->has('name'));
        $this->assertFalse($this->testInstance->has('name2'));
    }

    public function testIterations()
    {
        $this->testInstance->fill($this->generateFakeData());

        foreach ($this->testInstance as $key => $value) {
            $this->assertArrayHasKey($key, $this->fakeData);
            $this->assertSame($this->fakeData[$key], $value);
        }
    }

    public function testOffsets()
    {
        $this->testInstance->fill($this->generateFakeData());

        $this->assertTrue(isset($this->testInstance['name']));
        $this->assertTrue(array_key_exists('name', $this->testInstance));

        $this->assertSame($this->fakeData['name'], $this->testInstance['name']);

        $this->testInstance['name2'] = uniqid();
        $this->testInstance[] = uniqid();

        $this->assertTrue(isset($this->testInstance['name2']));

        unset($this->testInstance['name2']);

        $this->assertFalse(isset($this->testInstance['name2']));
    }


    protected function tearDown()
    {
        parent::tearDown();
    }
}
