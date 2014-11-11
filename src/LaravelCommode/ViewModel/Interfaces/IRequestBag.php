<?php
    namespace LaravelCommode\ViewModel\Interfaces;

    use LaravelCommode\ViewModel\Interfaces\IViewModel;

    interface IRequestBag extends IViewModel
    {
        /**
         * Returns all data
         * @return mixed
         */
        public function getAll();

        /**
         * Returns true if data-key exists
         * @param $key
         * @return mixed
         */
        public function has($key);
    }