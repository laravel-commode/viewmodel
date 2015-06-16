<?php

namespace LaravelCommode\ViewModel\TestSubjects;

class ViewModel extends \LaravelCommode\ViewModel\ViewModels\ViewModel
{

    /**
     * @param array $attributes
     * @return mixed
     */
    protected function getBaseModel(array $attributes = [])
    {
        return $this->fill($attributes);
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @return \LaravelCommode\ValidationLocator\Validators\Validator
     */
    protected function getValidationObject(array $data = [], $isNew = true)
    {
        return null;
    }
}
