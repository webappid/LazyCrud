# LazyCrud
Lazy Crud is a tool to help the developer to create CRUD API in one line artisan command.

### Requirement
* php >= 7.3.x
* mariadb / mysql latest version(support for mysql/mariadb only for now)
* laravel >= 6

### Install and use the tool
* setup Laravel database on the .env file to connect to mysql database. Makes sure that your application connect to mysql database successfully.
* Install package via composer as a dev package

```
composer require --dev webappid/lazycrud
```
* Publish the LazyCrud config to application config
```
php artisan vendor:publish --provider="WebAppId\LazyCrud\ServiceProvider"
```
* Add those config value on .env
```
    AUTHOR="Your Name<Email>"
    TZ="GMT+7"
```
default AUTHOR ""
default Timezone "UTC"

* By default crud will ignore for fields user_id, created_at, updated_at, and primary key field type.
* Create migration and run it first. For example, create categories table via migration
* Run the artisan command to create a CRUD

```
php artisan make:lazycrud ClassNameModel
```

ClassNameModel = Change ClassNameModel With Class Model in laravel

* Change destination route inject on artisan run with add --inject-route option
```
php artisan make:lazycrud ClassNameModel --inject-route=route_file_name
```
example: 
    php artisan lazy:crud Lazy --inject-route=web.php

The lazy tools will create route called lazy-{route_name}.php and all route CRUD will added in this file. 

* Custom inject route on the config/lazycrud.php

```
<?php
return [
    'inject' => [
        'controller' => [
            'user_id' => [
                'method' => 'Auth::user()->id',
                'use' => 'use Illuminate\Support\Facades\Auth;'
            ]
        ],
        'route' => env('INJECT_ROUTE', 'web.php')
    ]
];
```
* if you need to reformat the lazy route run this script:

```
php artisan lazy:format
```

* Format other route just add an option --route
```
php artisan lazy:format --route=api
``` 

If you have any problem to use this package, please don't hesitate to drop me and email at dyan.galih@gmail.com or chat me directly @DyanGalih on Telegram
