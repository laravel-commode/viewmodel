<?php

namespace LaravelCommode\ViewModel;

use Illuminate\Http\Request;
use LaravelCommode\ValidationLocator\ValidationLocatorServiceProvider;
use LaravelCommode\ViewModel\RequestBag\RequestBag;
use LaravelCommode\ViewModel\TestSubjects\FileViewModel;
use LaravelCommode\ViewModel\TestSubjects\ViewModel;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ViewModelServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|Mock
     */
    private $applicationMock;

    /**
     * @var ViewModelServiceProvider
     */
    private $testInstance;

    /**
     * @var Request|Mock
     */
    private $requestMock;

    protected function setUp()
    {
        $this->applicationMock = $this->getMock(
            'Illuminate\Foundation\Application',
            ['singleton', 'make', 'resolving']
        );

        $this->requestMock = $this->getMock(Request::class);

        $this->testInstance = new ViewModelServiceProvider($this->applicationMock);

        parent::setUp();
    }

    public function testLaunching()
    {
        $this->testInstance->launching();
    }

    public function testUses()
    {
        $reflectionMethod = new \ReflectionMethod($this->testInstance, 'uses');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(
            [ValidationLocatorServiceProvider::class],
            $reflectionMethod->invoke($this->testInstance)
        );

        $reflectionMethod->setAccessible(false);
    }

    public function testRegister()
    {
        $this->applicationMock->expects($this->any())->method('make')
            ->will($this->returnCallback(function ($toMake) {
                switch ($toMake)
                {
                    case 'request':
                        return $this->requestMock;
                }
            }));

        $this->applicationMock->expects($this->atLeastOnce())->method('resolving')
            ->with($this->callback(function ($callable) {
                $callable(new ViewModel());
                $callable(new RequestBag());
                $callable(new FileViewModel());
                return $callable instanceof \Closure;
            }));

        $this->requestMock->expects($this->atLeastOnce())->method('only')
            ->will($this->returnValue([]));

        $this->requestMock->expects($this->atLeastOnce())->method('all')
            ->will($this->returnValue([]));

        $this->testInstance->registering();
    }

    protected function tearDown()
    {
        unset($this->testInstance, $this->requestMock, $this->applicationMock);
        parent::tearDown();
    }
}
