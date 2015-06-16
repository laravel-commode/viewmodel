<?php

namespace LaravelCommode\ViewModel\ViewModels;

use LaravelCommode\ValidationLocator\TestSubject\TestSubject;
use LaravelCommode\ValidationLocator\Validators\Validator;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ViewModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ViewModel|Mock
     */
    private $testInstance;

    /**
     * @var \stdClass
     */
    private $testModel;

    /**
     * @var Validator|Mock
     */
    private $testValidator;

    protected function setUp()
    {
        $this->testInstance = $this->getMockForAbstractClass(ViewModel::class);
        $this->testModel = new \stdClass();
        $this->testValidator = $this->getMock(
            TestSubject::class,
            ['getValidator', 'passes'],
            [],
            '',
            false
        );
        parent::setUp();
    }

    protected function generateFakeData()
    {
        return [
            'value' =>  uniqid(),
            'value1'=>  uniqid()
        ];
    }

    public function testFill()
    {
        $fakeData = $this->generateFakeData();

        $reflectionProperty = new \ReflectionProperty($this->testInstance, 'attributes');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->testInstance, array_keys($fakeData));
        $reflectionProperty->setAccessible(false);

        foreach ($fakeData as $key => $value) {
            $this->testInstance->{$key} = null;
        }

        $this->testInstance->fill($fakeData);
    }

    public function testValidator()
    {
        $this->testInstance->expects($this->any())->method('getValidationObject')
            ->will($this->returnValue($this->testValidator));

        $this->testValidator->expects($this->any())->method('getValidator')
            ->will($this->returnValue($value = uniqid()));
        $this->testValidator->expects($this->any())->method('passes')
            ->will($this->returnValue($valuePasses = uniqid()));

        $this->assertSame($this->testValidator, $this->testInstance->extractValidator());
        $this->assertSame($value, $this->testInstance->getValidator());
        $this->assertSame($valuePasses, $this->testInstance->isValid());

    }

    public function testToModel()
    {
        $this->testInstance->expects($this->any())->method('getBaseModel')
            ->will($this->returnValue($this->testModel));

        $this->assertSame($this->testModel, $this->testInstance->toModel());

    }

    protected function tearDown()
    {
        unset($this->testInstance, $this->testValidator, $this->testModel);
        parent::tearDown();
    }
}
