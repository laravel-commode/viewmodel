<?php

namespace LaravelCommode\ViewModel\TestSubjects;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileViewModel extends \LaravelCommode\ViewModel\ViewModels\FileViewModel
{
    public $file;

    /**
     * Method is supposed to save file and return
     * bool value if operation failed of
     * @param $path
     * @param null $name
     * @return mixed
     */
    public function save($path, $name = null)
    {
        // TODO: Implement save() method.
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        // TODO: Implement getFile() method.
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    protected function getBaseModel(array $attributes = [])
    {
        // TODO: Implement getBaseModel() method.
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @return \LaravelCommode\ValidationLocator\Validators\Validator
     */
    protected function getValidationObject(array $data = [], $isNew = true)
    {
        // TODO: Implement getValidationObject() method.
    }
}
