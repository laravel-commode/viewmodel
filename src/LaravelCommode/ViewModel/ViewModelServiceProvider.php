<?php namespace LaravelCommode\ViewModel;

    use LaravelCommode\Common\GhostService\GhostService;
    use LaravelCommode\ViewModel\Interfaces\IFileViewModel;
    use LaravelCommode\ViewModel\Interfaces\IRequestBag;
    use LaravelCommode\ViewModel\Interfaces\IViewModel;

    use Input;
    use LaravelCommode\ViewModel\RequestBag\RequestBag;

    /**
     * Class ViewModelServiceProvider
     * @package LaravelCommode\ViewModel
     */
    class ViewModelServiceProvider extends GhostService
    {
        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return ['commode.viewmodel'];
        }

        public function boot()
        {
            $this->package('laravel-commode/viewmodel');
        }

        protected function launching() { }

        protected function registering()
        {
            $this->app->singleton(
                'LaravelCommode\ViewModel\Interfaces\IRequestBag',
                'LaravelCommode\ViewModel\RequestBag\RequestBag'
            );

            $this->app->resolvingAny(function($resolved, $application = null)
            {
                if ($resolved instanceof IFileViewModel) {
                    foreach($resolved->getAttributeList() as $attribute)
                    {
                        $resolved->{$attribute} = Input::file($attribute);
                    }
                } elseif ($resolved instanceof IRequestBag) {
                    $resolved->fill(Input::all());
                } elseif ($resolved instanceof IViewModel) {
                    $resolved->fill(Input::only($resolved->getAttributeList()));
                }
            });
        }
    }