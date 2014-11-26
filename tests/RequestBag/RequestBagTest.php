<?php namespace RequestBag;

    use LaravelCommode\ViewModel\RequestBag\RequestBag;

    class RequestBagTest extends \PHPUnit_Framework_TestCase
    {
        public function testFill()
        {
            $requestBag = new RequestBag();

            $testData = ['key1' => 'value1', 'key2' => 'value2'];

            $this->assertNotSameSize($requestBag->getAll(), $testData);

            $requestBag->fill($testData);

            $this->assertSame($requestBag->getAll(), $testData);
        }

        public function testHas()
        {
            $requestBag = new RequestBag();
            $testData = ['key1' => 'value1', 'key2' => 'value2'];

            $requestBag->fill($testData);

            $this->assertFalse($requestBag->has('key3'));
            $this->assertTrue($requestBag->has('key2'));
        }

        public function testOffsetExists()
        {
            $requestBag = new RequestBag();
            $testData = ['key1' => 'value1', 'key2' => 'value2'];

            $requestBag->fill($testData);

            $this->assertSame(isset($requestBag['key2']), $requestBag->offsetExists('key2'));
        }

        public function testOffsetGet()
        {
            $requestBag = new RequestBag();
            $testData = ['key1' => 'value1', 'key2' => 'value2'];

            $requestBag->fill($testData);

            $this->assertSame($requestBag['key2'], $requestBag->offsetGet('key2'));
        }

        public function testOffsetSet()
        {
            $requestBag = new RequestBag();

            $requestBag['key'] = 'value';
            $requestBag->offsetSet('key1', 'value1');

            $this->assertSame($requestBag['key'], 'value');
            $this->assertSame($requestBag['key1'], 'value1');
        }

        public function testUnset()
        {
            $requestBag = new RequestBag();

            $requestBag['key'] = 'value';
            $requestBag['key1'] = 'value1';

            $this->assertSame($requestBag['key'], 'value');
            $this->assertSame($requestBag['key1'], 'value1');

            unset($requestBag['key']);
            $requestBag->offsetUnset('key1');

            $this->assertFalse(isset($requestBag['key']));
            $this->assertFalse($requestBag->offsetExists('key1'));
        }

        public function testCurrent()
        {
            $requestBag = new RequestBag();

            $requestBag[] = 'value';
            $requestBag[] = 'value1';

            foreach($requestBag as $value)
            {
                $this->assertSame($requestBag->current(), $value);
            }
        }

        public function testNext()
        {
            $requestBag = new RequestBag();

            $requestBag[] = 'value';
            $requestBag[] = 'value1';

            $this->assertSame('value', $requestBag->current());

            $requestBag->next();

            $this->assertSame('value1', $requestBag->current());
        }

        public function testKeyAndValid()
        {
            $requestBag = new RequestBag();

            $this->assertFalse($requestBag->valid());

            $requestBag[] = 'value';
            $requestBag[] = 'value1';

            foreach($requestBag as $key => $value)
            {
                $this->assertSame($key, $requestBag->key());
                $this->assertTrue($requestBag->valid());
            }
        }

        public function testToArray()
        {
            $requestBag = new RequestBag();
            $testData = ['key1' => 'value1', 'key2' => 'value2'];

            $requestBag->fill($testData);


            $this->assertSame($requestBag->toArray(), $testData);
        }
    } 