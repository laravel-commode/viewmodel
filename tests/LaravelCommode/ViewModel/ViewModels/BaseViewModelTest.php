<?php

namespace LaravelCommode\ViewModel\ViewModels;

use Illuminate\Support\Collection;
use LaravelCommode\ViewModel\Interfaces\IViewModel;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class BaseViewModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BaseViewModel|Mock
     */
    private $testInstance;

    /**
     * @return Mock|BaseViewModel
     */
    private function rebuildTestInstance()
    {
        unset($this->testInstance);
        return $this->testInstance = $this->getMockForAbstractClass(BaseViewModel::class);
    }

    private function generateFakeData()
    {
        return [
            'name'  => uniqid(),
            'name1' => uniqid(),
            'name2' => [['id' => 'a'], ['id' => 'b'], ['id' => 'c']]
        ];
    }

    protected function setUp()
    {
        $this->rebuildTestInstance();
        parent::setUp();
    }

    public function testDataFills()
    {
        $fake = $this->generateFakeData();

        $instance = $this->rebuildTestInstance();

        $property = new \ReflectionProperty($instance, 'attributes');
        $property->setAccessible(true);
        $property->setValue($instance, ['name', 'name1', 'name2']);
        $property->setAccessible(false);

        foreach ($fake as $key => $value) {
            $instance->{$key} = $key === 'name2' ? new Collection() : null;
        }

        $instance->fill($fake);

        foreach ($fake as $key => $value) {
            if ($key !== 'name2') {
                $this->assertSame($fake[$key], $this->testInstance->{$key});
            } else {
                $this->assertTrue($this->testInstance->name2->count() === 3);
            }
        }

        $this->assertSame($fake, $this->testInstance->toArray());
        $this->assertSame($fake, $this->testInstance->toModel());
        $this->assertSame(json_encode($fake), $this->testInstance->toJson());
    }

    public function testStates()
    {
        $instance = $this->rebuildTestInstance();

        $this->assertTrue($instance->isCreating());
        $this->assertTrue($instance->getState() === IViewModel::STATE_CREATE);

        $instance->setState(IViewModel::STATE_UPDATE);
        $this->assertFalse($instance->isCreating());
        $this->assertFalse($instance->getState() === IViewModel::STATE_CREATE);

        try {
            $instance->setState($state = uniqid());
        } catch (\UnexpectedValueException $e) {
            $this->assertSame('Unexpected state: \''.$state.'\'', $e->getMessage());
        }
    }

    protected function tearDown()
    {
        unset($this->testInstance);
        parent::tearDown();
    }
}
