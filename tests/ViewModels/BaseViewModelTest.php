<?php
    namespace ViewModels;

    use Illuminate\Support\Collection;
    use LaravelCommode\ViewModel\Interfaces\IViewModel;

    class BaseViewModelTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @param array $keys
         * @param bool $fill
         * @param bool $withCollection
         * @return \PHPUnit_Framework_MockObject_MockObject|\LaravelCommode\ViewModel\ViewModels\BaseViewModel
         */
        protected function getModelMock($keys = [], $fill = false, $withCollection = false)
        {
            $baseViewModel = $this->getMockBuilder('LaravelCommode\ViewModel\ViewModels\BaseViewModel');
            $baseViewModel->key1 = 1;
            $abstractMock = $baseViewModel->getMockForAbstractClass();



            if (count($keys))
            {
                $allowAttributes = new \ReflectionClass($abstractMock);
                $allowAttributesAttr = $allowAttributes->getProperty('attributes');

                $allowAttributesAttr->setAccessible(true);
                $allowAttributesAttr->setValue($abstractMock, $keys);

                if ($fill)
                {
                    foreach($keys as $item => $keyName)
                    {
                        $abstractMock->{$keyName} = 'value'.($item+1);
                    }

                    if ($withCollection)
                    {
                        $abstractMock->collection = new Collection();
                    }
                }
            }

            return $abstractMock;

        }

        public function testGetAttributes()
        {
            $attributes = [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3'
            ];

            $baseViewModel = $this->getModelMock($attributes = array_keys($attributes));
            $this->assertSame($attributes, $baseViewModel->getAttributeList());
        }

        public function testFill()
        {
            $attributesExistent = [
                'key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3',
                'collection' => [
                    (object)['key1' => 'hey', 'key2' => 'lol'],
                    (object)['key1' => 'hey!', 'key2' => 'not lol']
                ]
            ];

            $attributesInput = array_merge($attributesExistent, [ 'key4' => 'value4' ]);

            $baseViewModel = $this->getModelMock($attributes = array_keys($attributesExistent), true, true);

            $baseViewModel->fill($attributesInput);

            $this->assertSame($attributes, $baseViewModel->getAttributeList());
            $this->assertSameSize($attributes, $baseViewModel->getAttributeList());
            $this->assertNotSame($attributesInput, $baseViewModel->toArray());

            $this->assertSame($baseViewModel->collection->toArray(), $attributesExistent['collection']);

            $baseViewModel = $this->getModelMock();



            $this->assertNotSame($attributesInput, $baseViewModel->toArray());

        }

        public function testIsCreatingAndStates()
        {
            $baseViewModel = $this->getModelMock();

            $this->assertSame(IViewModel::StateCreate, $baseViewModel->getState());
            $this->assertTrue($baseViewModel->isCreating());

            $baseViewModel->setState(IViewModel::StateUpdate);

            $this->assertFalse($baseViewModel->isCreating());

            try {
                $baseViewModel->setState('some state');
            } catch(\Exception $e) {
                $this->assertSame("Unexpected state: 'some state'", $e->getMessage());
            }
        }

        public function testToModel()
        {
            $attributes = [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3'
            ];

            $baseViewModel = $this->getModelMock(array_keys($attributes), true);

            $this->assertSame($attributes, $baseViewModel->toModel());
        }

        public function testToJsonToArray()
        {
            $attributes = [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => 'value3'
            ];

            $baseViewModel = $this->getModelMock(array_keys($attributes), true);

            $this->assertSame($attributes, $baseViewModel->toArray());
            $this->assertSame(json_encode($baseViewModel->toArray()), $baseViewModel->toJson());
        }
    } 