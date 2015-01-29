<?php
    namespace ViewModels;

    class ViewModelTest extends \PHPUnit_Framework_TestCase
    {
        protected function mockValidator($allowFail = false)
        {
            $mock = \Mockery::mock('Illuminate\Validation\Validator');

            if ($allowFail)
            {
                $mock->shouldReceive('fails')->zeroOrMoreTimes()->andReturn(false);
            }

            return $mock;
        }

        protected function mockCommodeValidator($allowFail = false, $validator)
        {
            $mock = \Mockery::mock('LaravelCommode\ValidationLocator\Validators\Validator');

            if ($allowFail)
            {
                $mock->shouldReceive('fails')->zeroOrMoreTimes()->andReturn($allowFail);
                $mock->shouldReceive('passes')->zeroOrMoreTimes()->andReturn($allowFail);
                $mock->shouldReceive('getValidator')->once()->andReturn($validator);
            } else {
                $mock->shouldReceive('passes')->zeroOrMoreTimes()->andReturn($allowFail);
            }


            return $mock;
        }

        protected function tearDown()
        {
            \Mockery::close();
            parent::tearDown();
        }

        protected function mockEloquent()
        {
            return \Mockery::mock('Illuminate\Database\Eloquent\Model');
        }

        protected function getViewModelMock($allowValidatorFail = false)
        {
            $mockBuilder = $this->getMockBuilder('LaravelCommode\ViewModel\ViewModels\ViewModel');

            $viewModelMock = $mockBuilder->getMockForAbstractClass();

            $validator = $this->mockValidator($allowValidatorFail);

            $viewModelMock->expects($this->any())->method('getValidationObject')->will(
                $this->returnValue($this->mockCommodeValidator($allowValidatorFail, $validator))
            );

            $viewModelMock->expects($this->any())->method('getBaseModel')->will(
                $this->returnValue($this->mockEloquent())
            );

            return $viewModelMock;
        }

        public function testToModel()
        {
            $viewModel = $this->getViewModelMock();

            $this->assertSame(
                'Illuminate\Database\Eloquent\Model', get_parent_class($viewModel->toModel())
            );

            $vmReflection = new \ReflectionClass($viewModel);
            $refMethod = $vmReflection->getMethod('getBaseModel');

            $refMethod->setAccessible(true);

            $this->assertSame($viewModel->toModel(), $refMethod->invokeArgs($viewModel, []));

            $this->assertSame(
                get_parent_class($refMethod->invokeArgs($viewModel, [])),
                'Illuminate\Database\Eloquent\Model'
            );
        }

        public function testExtractValidatorAndGetValidator()
        {
            $viewModel = $this->getViewModelMock(true);

            $this->assertSame(
                'Illuminate\Validation\Validator', get_parent_class($viewModel->getValidator())
            );

            $vmReflection = new \ReflectionClass($viewModel);
            $getValidationObject = $vmReflection->getMethod('getValidationObject');
            $getValidationObject->setAccessible(true);


            $extractValidator = $vmReflection->getMethod('extractValidator');
            $extractValidator->setAccessible(true);

            $this->assertSame(
                $extractValidator->invokeArgs($viewModel, []), $getValidationObject->invokeArgs($viewModel, [])
            );

            $this->assertTrue($viewModel->isValid());
        }
    } 