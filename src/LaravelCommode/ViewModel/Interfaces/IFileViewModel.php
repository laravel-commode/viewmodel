<?php namespace LaravelCommode\ViewModel\Interfaces;

    use LaravelCommode\ViewModel\Interfaces\IViewModel;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    /**
     * ViewModel approach for files.
     *
     */
    interface IFileViewModel extends IViewModel
    {
        /**
         * Method is supposed to save file and return
         * bool value if operation failed of
         * @param $path
         * @param null $name
         * @return mixed
         */
        public function save($path, $name = null);

        /**
         * @return UploadedFile
         */
        public function getFile();
    }