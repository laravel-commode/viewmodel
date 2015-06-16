<?php

namespace LaravelCommode\ViewModel\ViewModels;

use LaravelCommode\ViewModel\ViewModels\ViewModel;
use LaravelCommode\ViewModel\Interfaces\IFileViewModel;
use LaravelCommode\ViewModel\Interfaces\IViewModel;

use Exception,
    ReflectionClass,
    ReflectionProperty;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ViewModel
 * @package LaravelCommode\ViewModel
 */
abstract class FileViewModel extends ViewModel implements IFileViewModel
{
    /**
     * @param array $attributes
     * @return \LaravelCommode\ViewModel\Interfaces\IViewModel
     */
    public function fill(array $attributes = [])
    {
        foreach ($this->getAttributeList() as $key) {
            $this->{$key} = null;

            if (array_key_exists($key, $attributes) && $attributes[$key] instanceof UploadedFile) {
                $this->{$key} = $attributes[$key];
            }
        }

        return $this;
    }
}
