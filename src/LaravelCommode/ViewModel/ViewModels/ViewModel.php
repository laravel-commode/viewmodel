<?php namespace LaravelCommode\ViewModel\ViewModels;

    use LaravelCommode\ViewModel\Interfaces\IConvertibleViewModel;
    use LaravelCommode\ViewModel\ViewModels\BaseViewModel;
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
        protected $_validator = null;

        /**
         * @param array $attributes
         * @return mixed
         */
        abstract protected function getBaseModel($attributes = array());

        /**
         * @param array $data
         * @param bool $isNew
         * @return \LaravelCommode\ValidationLocator\Validators\Validator
         */
        abstract protected function getValidationObject($data = [], $isNew = true);

        /**
         * Returns LaravelCommode validator instance
         *
         * @param bool $isNew
         * @return \LaravelCommode\ValidationLocator\Validators\Validator|null
         */
        public function extractValidator($isNew = true)
        {
            if (is_null($this->_validator))
            {
                $this->_validator = $this->getValidationObject($this->toArray(), $isNew);
            }

            return $this->_validator;
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
            $model->exists = !$this->isCreating();

            return $model;
        }

    }