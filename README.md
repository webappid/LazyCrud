# LazyCrud
Lazy Crud is a tool to help the developer to create CRUD API in one line artisan command.

### Requirement
* php >= 7.3.x
* mariadb / mysql latest version(support for mysql/mariadb only for now)
* laravel >= 6

### Install and use the tool
* Install package via composer as a dev package

```
composer require --dev webappid/lazycrud
```
* Publish the LazyCrud config to application config
```
php artisan vendor:publish --provider="WebAppId\LazyCrud\ServiceProvider"
```
* By default crud will ignore for fields user_id, created_at, updated_at, and primary key field type.
* Create migration and run it first. For example, create categories table via migration
* Run the artisan command to create a CRUD

```
php artisan make:lazycrud Category
```
