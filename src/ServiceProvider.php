<?php

namespace WebAppId\LazyCrud;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->commands(ControllerDeleteMakeCommand::class);
        $this->commands(ControllerDetailMakeCommand::class);
        $this->commands(ControllerIndexMakeCommand::class);
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
