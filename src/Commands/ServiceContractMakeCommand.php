<?php


namespace WebAppId\LazyCrud;

use Illuminate\Filesystem\Filesystem;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 12/04/20
 * Time: 01.06
 * Class ServiceContractMakeCommand
 * @package WebAppId\LazyCrud
 */
class ServiceContractMakeCommand extends SmartMakeCommand
{
    public function __construct(Filesystem $files)
    {
        $this->classNameSuffix = 'ServiceContract';
        $this->name = 'make:lazyservicecontract';
        $this->description = 'Create a new service contract interface';
        $this->stubFile = 'ServiceContract';
        $this->nameSpace = '\Services\Contracts';
        $this->hidden = true;
        parent::__construct($files);
    }

    private function createRepository()
    {
        $this->call('make:lazyrepository',
            [
                "name" => $this->inputName
            ]);
    }

    private function createServiceRequest()
    {
        $this->call('make:lazyservicerequest',
            [
                "name" => $this->inputName
            ]);
    }

    private function createServiceResponse()
    {
        $this->call('make:lazyserviceresponse',
            [
                "name" => $this->inputName
            ]);
    }

    private function createServiceResponseList()
    {
        $this->call('make:lazyserviceresponselist',
            [
                "name" => $this->inputName
            ]);
    }

    function prevHandle()
    {
        $this->createRepository();
        $this->createServiceRequest();
        $this->createServiceResponse();
        $this->createServiceResponseList();
    }

    function replaceClassCustom(string $stub)
    {
        return $stub;
    }

    function closeHandle()
    {
        // TODO: Implement closeHandle() method.
    }
}
