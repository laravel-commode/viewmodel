<?php namespace LaravelCommode\ViewModel\ViewModels;

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
        protected $attributes = array();
        protected $state = IViewModel::StateCreate;
        protected $_validator = null;

        /**
         * @param array $attributes
         * @return \LaravelCommode\ViewModel\Interfaces\IViewModel
         */
        public function fill($attributes = array())
        {
            foreach($this->getAttributeList() as $key)
            {
                if (isset($attributes[$key]) && $attributes[$key] instanceof UploadedFile)
                {
                    $this->{$key} = $attributes[$key];
                }
            }

            return $this;
        }
    }