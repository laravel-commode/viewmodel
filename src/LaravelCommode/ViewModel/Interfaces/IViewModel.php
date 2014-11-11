<?php namespace LaravelCommode\ViewModel\Interfaces;

    use Illuminate\Support\Contracts\ArrayableInterface;
    use Illuminate\Support\Contracts\JsonableInterface;

    /**
     * ViewModel approach interface.
     *
     * Class IViewModel
     * @package Application\Utils\ViewModel\Interfaces
     */
    interface IViewModel extends ArrayableInterface, JsonableInterface
    {
        const StateCreate = 1;
        const StateUpdate = 0;

        /**
         * Fills the model with passed attributes.
         * If the model does not contain and
         * attribute's key, it's value's gonna be omitted.
         *
         * @param array|mixed $attributes
         * @return IViewModel
         */
        public function fill($attributes = array());

        /**
         * Returns array of public attributes' names
         * @return array
         */
        public function getAttributeList();


        /**
         * Returns if the model is in a state of creation,
         * or it's being updated now.
         * @return bool
         */
        public function isCreating();

        /**
         * Sets model into state of creating
         * or updating - IViewModel::StateCreate and IViewModel::StateUpdate
         * are supposed to be used.
         * @param int $state
         * @return void
         */
        public function setState($state = IViewModel::StateCreate);

        /**
         * Returns view model state- IViewModel::StateCreate and IViewModel::StateUpdate
         * are supposed to be used.
         * @internal param int $state
         * @return void
         */
        public function getState();

        /**
         * Supposed to return mixed value that
         * would cast object to something different.
         * @return mixed
         */
        public function toModel();
    }