<?php

namespace LaravelCommode\ViewModel\ViewModels;

use LaravelCommode\ViewModel\Interfaces\IViewModel;
use Illuminate\Support\Collection;

use Exception;
use ReflectionClass;
use ReflectionProperty;

/**
 * Class ViewModel
 * @package LaravelCommode\ViewModel
 */
abstract class BaseViewModel implements IViewModel
{
    protected $attributes = [];
    protected $state = IViewModel::STATE_CREATE;

    /**
     * @return array
     */
    public function getAttributeList()
    {
        if (count($this->attributes) === 0) {
            $reflection = new ReflectionClass($this);

            foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $this->attributes[] = $property->name;
            }
        }

        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes = [])
    {
        foreach ($this->getAttributeList() as $key) {
            if (array_key_exists($key, $attributes)) {
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
     * Converts ViewModel instance to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();

        foreach ($this->getAttributeList() as $attribute) {
            if ($this->{$attribute} instanceof Collection) {
                $array[$attribute] = $this->{$attribute}->toArray();
            } else {
                $array[$attribute] = $this->{$attribute};
            }
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
        return $this->toArray();
    }


    public function setState($state = IViewModel::STATE_CREATE)
    {
        switch ($state)
        {
            case IViewModel::STATE_CREATE:
            case IViewModel::STATE_UPDATE:
                $this->state = $state;
                break;
            default:
                throw new \UnexpectedValueException("Unexpected state: '{$state}'");
        }
    }

    public function getState()
    {
        return $this->state;
    }

    /**
     * Defines if object is in creating state
     * (useful for crud stuff)
     *
     * @return bool
     */
    public function isCreating()
    {
        return $this->state === IViewModel::STATE_CREATE;
    }
}
