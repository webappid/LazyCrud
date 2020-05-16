<?php

namespace WebAppId\LazyCrud;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use WebAppId\LazyCrud\Commands\ControllerDeleteMakeCommand;
use WebAppId\LazyCrud\Commands\ControllerDetailMakeCommand;
use WebAppId\LazyCrud\Commands\ControllerListMakeCommand;
use WebAppId\LazyCrud\Commands\ControllerStoreMakeCommand;
use WebAppId\LazyCrud\Commands\ControllerUpdateMakeCommand;
use WebAppId\LazyCrud\Commands\CrudMakeCommand;
use WebAppId\LazyCrud\Commands\ModelMakeCommand;
use WebAppId\LazyCrud\Commands\RepositoryContractMakeCommand;
use WebAppId\LazyCrud\Commands\RepositoryMakeCommand;
use WebAppId\LazyCrud\Commands\RepositoryRequestMakeCommand;
use WebAppId\LazyCrud\Commands\RequestMakeCommand;
use WebAppId\LazyCrud\Commands\RequestSearchMakeCommand;
use WebAppId\LazyCrud\Commands\RouteFormatMakeCommand;
use WebAppId\LazyCrud\Commands\ServiceContractMakeCommand;
use WebAppId\LazyCrud\Commands\ServiceMakeCommand;
use WebAppId\LazyCrud\Commands\ServiceRequestMakeCommand;
use WebAppId\LazyCrud\Commands\ServiceResponseListMakeCommand;
use WebAppId\LazyCrud\Commands\ServiceResponseMakeCommand;
use WebAppId\LazyCrud\Commands\TestRepositoryMakeCommand;
use WebAppId\LazyCrud\Commands\TestServiceMakeCommand;
use WebAppId\LazyCrud\Commands\TestSmartTestCaseMakeCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->commands(ControllerDeleteMakeCommand::class);
        $this->commands(ControllerDetailMakeCommand::class);
        $this->commands(ControllerListMakeCommand::class);
        $this->commands(ControllerStoreMakeCommand::class);
        $this->commands(ControllerUpdateMakeCommand::class);
        $this->commands(CrudMakeCommand::class);
        $this->commands(ModelMakeCommand::class);
        $this->commands(RepositoryContractMakeCommand::class);
        $this->commands(RepositoryMakeCommand::class);
        $this->commands(RepositoryRequestMakeCommand::class);
        $this->commands(RequestMakeCommand::class);
        $this->commands(ServiceContractMakeCommand::class);
        $this->commands(ServiceMakeCommand::class);
        $this->commands(ServiceRequestMakeCommand::class);
        $this->commands(ServiceResponseListMakeCommand::class);
        $this->commands(ServiceResponseMakeCommand::class);
        $this->commands(TestRepositoryMakeCommand::class);
        $this->commands(TestServiceMakeCommand::class);
        $this->commands(TestSmartTestCaseMakeCommand::class);
        $this->commands(RouteFormatMakeCommand::class);
        $this->commands(RequestSearchMakeCommand::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config' => base_path('config')
        ]);
        $this->mergeConfigFrom(__DIR__ . '/config/lazycrud.php', 'lazycrud');
    }
}
