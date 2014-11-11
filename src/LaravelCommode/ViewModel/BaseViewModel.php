<?php namespace LaravelCommode\ViewModel;

    use LaravelCommode\ViewModel\Interfaces\IViewModel;
    use Illuminate\Support\Collection;

    use Exception,
        ReflectionClass,
        ReflectionProperty;

    /**
     * Class ViewModel
     * @package Dubpub\ViewModel
     */
    abstract class BaseViewModel implements IViewModel
    {
        protected $attributes = array();
        protected $state = IViewModel::StateCreate;

        /**
         * @return array
         */
        public function getAttributeList()
        {
            if (!count($this->attributes))
            {
                $reflection = new ReflectionClass($this);

                foreach($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
                {
                    $this->attributes[] = $property->name;
                }
            }

            return $this->attributes;
        }

        /**
         * @param array $attributes
         * @return $this
         */
        public function fill($attributes = array())
        {
            foreach($this->getAttributeList() as $key)
            {
                if (isset($attributes[$key]))
                {
                    if ($this->{$key} instanceof Collection) {
                        $this->{$key} = $this->{$key}->make($attributes[$key]);
                    } else {
                        $this->{$key} = $attributes[$key];
                    }
                }
            }

            return $this;
        }

        /**
         * Defines if object is in creating state
         * (useful for crud stuff)
         *
         * @return bool
         */
        public function isCreating()
        {
            return $this->state == IViewModel::StateCreate;
        }

        /**
         * Converts ViewModel instance to array
         *
         * @return array
         */
        public function toArray()
        {
            $array = array();

            foreach($this->getAttributeList() as $attribute)
            {
                $array[$attribute] = $this->{$attribute};
            }

            return $this->onConversion($array);
        }

        /**
         * Might be useful for overriding
         *
         * @param $array
         * @return mixed
         */
        protected function onConversion($array)
        {
            return $array;
        }

        /**
         * Converts result of toArray into JSON string
         *
         * @param int $options
         * @return string
         */
        public function toJson($options = 0)
        {
            return json_encode($this->toArray(), $options);
        }

        public function toModel()
        {
            $model = $this->getBaseModel($this->toArray());
            $model->exists = !$this->isCreating();

            return $model;
        }


        public function setState($state = IViewModel::StateCreate)
        {
            if ($state !== IViewModel::StateCreate && $state !== IViewModel::StateUpdate)
            {
                throw new Exception("Unexpected state\n".print_r($state, 1));
            }

            $this->state = $state;
        }

        public function getState()
        {
            return $this->state;
        }

    }