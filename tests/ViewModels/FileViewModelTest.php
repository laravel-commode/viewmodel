<?php
    namespace ViewModels;

    class FileViewModelTest extends \PHPUnit_Framework_TestCase
    {
        protected function mockValidator()
        {
            $mock = \Mockery::mock('Illuminate\Validation\Validator');

            return $mock;
        }

        /**
         * @return \Mockery\MockInterface|\Symfony\Component\HttpFoundation\File\UploadedFile
         */
        protected function mockFile()
        {
            $mock = \Mockery::mock('Symfony\Component\HttpFoundation\File\UploadedFile');

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

        protected function getFileViewModelMock($keys = [], $fill = false)
        {
            $mockBuilder = $this->getMockBuilder('LaravelCommode\ViewModel\ViewModels\FileViewModel');

            $viewModelMock = $mockBuilder->getMockForAbstractClass();

            if (count($keys))
            {
                $allowAttributes = new \ReflectionClass($viewModelMock);
                $allowAttributesAttr = $allowAttributes->getProperty('attributes');

                $allowAttributesAttr->setAccessible(true);
                $allowAttributesAttr->setValue($viewModelMock, $keys);

                if ($fill)
                {
                    foreach($keys as $item => $keyName)
                    {
                        $viewModelMock->{$keyName} = 'value'.($item+1);
                    }
                }
            }

            $viewModelMock->expects($this->any())->method('getValidationObject')->will(
                $this->returnValue($this->mockValidator())
            );

            $viewModelMock->expects($this->any())->method('getBaseModel')->will(
                $this->returnValue($this->mockEloquent())
            );

            return $viewModelMock;
        }

        public function testFill()
        {
            $dataModel = ['key1' => 'value1', 'key2' => $this->mockFile()];

            $file = $this->getFileViewModelMock(array_keys($dataModel));

            $file->fill($dataModel);

            $this->assertSame(array_keys($dataModel), $file->getAttributeList());
            $this->assertNotSame($dataModel, $file->toArray());
        }
    } 