<?php

namespace LaravelCommode\ViewModel;

use Illuminate\Http\Request;
use LaravelCommode\SilentService\SilentService;
use LaravelCommode\ViewModel\Interfaces\IFileViewModel;
use LaravelCommode\ViewModel\Interfaces\IRequestBag;
use LaravelCommode\ViewModel\Interfaces\IViewModel;
use LaravelCommode\ViewModel\RequestBag\RequestBag;

class ViewModelServiceProvider extends SilentService
{
    public function launching()
    {
    }

    private function onResolving(Request $request)
    {
        return function ($resolved) use ($request) {
            if ($resolved instanceof IFileViewModel) {
                foreach ($resolved->getAttributeList() as $attribute) {
                    $resolved->{$attribute} = $request->file($attribute);
                }
            } elseif ($resolved instanceof IRequestBag) {
                $resolved->fill($request->all());
            } elseif ($resolved instanceof IViewModel) {
                $resolved->fill($request->only($resolved->getAttributeList()));
            }
        };
    }

    public function registering()
    {
        $this->app->singleton(IRequestBag::class, RequestBag::class);

        $this->with(['request'], function (Request $request) {
            $this->app->resolving($this->onResolving($request));
        });
    }
}
