<?php

namespace LaravelCommode\ViewModel\Interfaces;

use Illuminate\Validation\Validator;

/**
 * ViewModel approach interface.
 *
 * Class IViewModel
 * @package Application\Utils\ViewModel\Interfaces
 */
interface IValidatableViewModel extends IViewModel
{
    /**
     * Validates model and returns it's state.
     * True if it's valid, false if it's not.
     *
     * @param bool $isNew
     * @return bool
     */
    public function isValid($isNew = true);

    /**
     * Returns laravel validator instance
     * @return Validator
     */
    public function getValidator();
}
