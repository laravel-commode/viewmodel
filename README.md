#Commode: ViewModel

[![Build Status](https://travis-ci.org/laravel-commode/viewmodel.svg?branch=master)](https://travis-ci.org/laravel-commode/viewmodel)
[![Code Climate](https://codeclimate.com/github/laravel-commode/viewmodel/badges/gpa.svg)](https://codeclimate.com/github/laravel-commode/viewmodel)
[![Test Coverage](https://codeclimate.com/github/laravel-commode/viewmodel/badges/coverage.svg)](https://codeclimate.com/github/laravel-commode/viewmodel)

> *_laravel-commode/viewmodel_* is an implementation of ViewModel approach for laravel framework.

<br />
####Contents

+ <a href="#installing">Installing</a>
+ <a href="#bother">Why should I bother?</a>
+ <a href="#viewmodel">Create ViewModels</a>
+ <a href="#usage">Usage</a>


##<a name="service">Installing</a>

You can install ___laravel-commode/viewmodel___ using composer:

    "require": {
        "laravel-commode/viewmodel": "dev-master"
    }

To enable package you need to register ``LaravelCommode\ViewModel\ViewModelServiceProvider``
service provider in your application config.

      <?php
          // ./yourLaravelApplication/app/config/app.php
          return [
              // ... config code
              'providers' => [
                  // ... providers
                  'LaravelCommode\ViewModel\ViewModelServiceProvider'
              ]
          ];
<hr />
##<a name="bother">Why should I bother?</a>

I could bet that there many laravel users, that would ask this question:

>"Why should I bother about viewmodel approach, since I have ``Eloquent`` models and ``Input`` facade?".

As well, as I bet that this category of users have never used repository/service/strategy pattern approaches.
First of all I'd like to say that Eloquent is not a model. It's an awesome way to communicate with database as
an ActiveRecord pattern implementation. But still there are issues that every eloquent user has to face, like
properties encapsulated in `$attributes` array.
The other thing is that it's really bad aggregating your application input data in your ActiveRecord model,
since data aggregation inside ActiveRecord pattern can bring a lot of pain when your application gets bigger
and bigger especially if was is not abstracted - it becomes hardly readable, testable and not context reliable.
Imagine if once you decided to move to ``Doctrine`` usage what headache would it be to refactor all data
aggregating/pulling/storing.

So, as a conclusion, I could say that ViewModel brings another abstraction layer between user and database
interaction.

<hr />

##<a name="viewmodel">Create ViewModels</a>

This package provides two basic __ViewModel__ types: ``FileViewModel`` for using it only with files, and
``ViewModel`` for using as with files, as with common values.

So let's say you would have to implement context oriented model for Profile model, because it's the most
common example for context oriented models: it's used in you acl, it's used in your admin panels, it's used by
your users, e.t.c. ... And almost each time it required different validators, different data aggregation
processes before it's being stored or rejected to be stored in your database and so on.
To create your ViewModel you need to extend ``LaravelCommode\ViewModel\ViewModels\ViewModel`` and  implement
two protected methods:

>* ``getBaseModel($attributes = array())`` - will receive an associative array of attributes - current values
of ViewModel's public properties. Method must return model which your ViewModel can be converted to. That might
be very useful for casting to other object.
* ``getValidationObject($data = [], $isNew = true)`` - will receive an associative array of attributes - current values
of ViewModel's public properties and a boolean flag that indicates if model is being created or updated. Method must
return laravel validator instance.

For example:

    <?php namespace MyApp\Domain\Admin\ViewModels;

        use LaravelCommode\ViewModel\ViewModels\ViewModel;

        class Profile extends ViewModel
        {
            public $login;
            public $password;
            public $password_confirmation;
            public $email;
            public $firstname;
            public $lastname;

            protected function getBaseModel($attributes = array())
            {
                $model = new \MyApp\DAL\Eloquent\Models\Profile();

                $model->fill($attributes);

                return $model;
            }

            protected function getValidationObject($data = [], $isNew = true)
            {
                $rules = [
                    'login'     => 'required',
                    'email'     => 'required',
                    'firstname' => 'required',
                    'lastname'  => 'required'
                ];

                if ($isNew)
                {
                    $rules['password'] = 'required|confirmed';
                }

                return \Validator::make($data, $rules);
            }
        }

