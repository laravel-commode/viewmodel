<?php namespace LaravelCommode\ViewModel;

    use LaravelCommode\ViewModel\BaseViewModel;
    use LaravelCommode\ViewModel\Interfaces\IValidatableViewModel;

    use Illuminate\Validation\Validator;

    /**
     * Class ViewModel
     * @package Dubpub\ViewModel
     */
    abstract class ViewModel extends BaseViewModel implements IValidatableViewModel
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

    }