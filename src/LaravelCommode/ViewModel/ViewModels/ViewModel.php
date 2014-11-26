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
        protected $_validator = null;

        /**
         * @param array $attributes
         * @return mixed
         */
        abstract protected function getBaseModel($attributes = array());

        /**
         * @param array $data
         * @param bool $isNew
         * @return Validator
         */
        abstract protected function getValidationObject($data = [], $isNew = true);


        protected function extractValidator($isNew = true)
        {
            if (is_null($this->_validator))
            {
                $this->_validator = $this->getValidationObject($this->toArray(), $isNew);
            }

            return $this->_validator;
        }

        public function getValidator()
        {
            return $this->extractValidator();
        }

        public function isValid($isNew = true)
        {
            return !$this->extractValidator($isNew)->fails();
        }

        public function toModel()
        {
            $model = $this->getBaseModel($this->toArray());
            $model->exists = !$this->isCreating();

            return $model;
        }

    }