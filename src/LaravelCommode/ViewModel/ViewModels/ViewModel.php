<?php

namespace LaravelCommode\ViewModel\ViewModels;

use LaravelCommode\ViewModel\Interfaces\IValidatableViewModel;

use Illuminate\Validation\Validator;
use LaravelCommode\ViewModel\ViewModels;

/**
 * Class ViewModel
 * @package LaravelCommode\ViewModel
 */
abstract class ViewModel extends ViewModels\BaseViewModel implements IValidatableViewModel
{
    /**
     * @var \LaravelCommode\ValidationLocator\Validators\Validator|null
     */
    protected $commodeValidator;

    /**
     * @param array $attributes
     * @return mixed
     */
    abstract protected function getBaseModel(array $attributes = []);

    /**
     * @param array $data
     * @param bool $isNew
     * @return \LaravelCommode\ValidationLocator\Validators\Validator
     */
    abstract protected function getValidationObject(array $data = [], $isNew = true);

    /**
     * Returns LaravelCommode validator instance
     *
     * @param bool $isNew
     * @return \LaravelCommode\ValidationLocator\Validators\Validator|null
     */
    public function extractValidator($isNew = true)
    {
        if ($this->commodeValidator === null) {
            $this->commodeValidator = $this->getValidationObject($this->toArray(), $isNew);
        }

        return $this->commodeValidator;
    }

    /**
     * Returns Laravel's native validator
     * @return Validator|null
     */
    public function getValidator()
    {
        return $this->extractValidator()->getValidator();
    }

    public function isValid($isNew = true)
    {
        return $this->extractValidator($isNew)->passes();
    }

    public function toModel()
    {
        $model = $this->getBaseModel($this->toArray());
        return $model;
    }
}
