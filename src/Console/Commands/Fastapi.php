<?php

namespace Larafast\Fastapi\Console\Commands;

use Illuminate\Support\Str;
use Larafast\Fastapi\Console\Contracts\GeneratorCommand;

class Fastapi extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'fastApi';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastApi
    {name : Model name. Controller, factory, migration, views will be based on this name.}
    {--views-dir= : Place views in a sub-directory under the views directory. It can be any nested directory structure}
    {--controller-dir= : Place controller in a sub-directory under the Http/Controllers directory. It can be any nested directory structure}
    {--stubs-dir= : Specify a custom stubs directory}
    {--no-views : Do not create view files for the model}
    {--no-migration : Do not create a migration for the model}
    {--no-factory : Do not create a factory for the model}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a nearly complete boilerplate CRUD pages for the specified Model';

    /**
     * Create a new command instance.
     *
     */
    /*    public function __construct()
        {
            parent::__construct();
        }*/

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Steps
         * - Generate Model
         * - Generate Factory
         * - Generate Migration
         * - Generate Controller
         * - Generate Requests
         * - Generate Views
         *
         */
        $this->type = 'Model';

        if (!$this->option('no-factory')) {
            $this->createFactory();
        }

        if (!$this->option('no-migration')) {
            $this->createMigration();
        }
        $this->createController();

        $this->type = 'Request';

        $this->createRequest(true);

        $this->createResource();
        $this->createCollection();
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));

        $this->call('fastApi:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->argument('name'),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

        $this->call('fastApi:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {

        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $args = [
            'name' => "{$controller}\\{$controller}Controller",
            '--model' => $modelName,
        ];

        $viewsDir = $this->option('views-dir');
        if ($viewsDir) {
            $args['--views-dir'] = $viewsDir;
        }

        $controllerDir = $this->option('controller-dir');
        if ($controllerDir) {
            $args['--controller-dir'] = $controllerDir;
        }

        $this->call('fastApi:controller', $args);
    }


    /**
     * Create a controller for the model.
     *
     * @param string
     *
     * @return void
     */
    protected function createRequest(bool $createBoth=false)
    {
        $model = Str::studly(class_basename($this->argument('name')));
        $basename="{$model}\\{$model}";
        if ($createBoth) {

            // Create Request
            $create = "{$basename}StoreRequest";
            $this->callRequest($create,$model);

            // Update Request
            $update = "{$basename}UpdateRequest";
            $this->callRequest($update,$model);

        }else{
            $name = "{$basename}Request";
            $this->callRequest($name,$model);
        }
    }

    public function callRequest($name,$model)
    {
       return $this->call('fastApi:request', [
            'name' => $name,
            '--model' => $model,
        ]);
    }
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('fastApi.stubs_dir') . '/' . Str::slug($this->type) . '.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        switch ($this->type) {
            case 'Request':
                return $rootNamespace . '\Http\Requests';
        }

        return $rootNamespace;
    }


    /**
     * Create a model resource for the model.
     *
     * @return void
     */
    protected function createResource()
    {
        $resource = Str::studly(class_basename($this->argument('name')));

        $this->call('fastApi:resource', [
            'name' => "$resource\\{$resource}Resource",
            '--model' => $this->argument('name'),
        ]);
    }
    /**
     * Create a model resource for the model.
     *
     * @return void
     */
    protected function createCollection()
    {
        $collection = Str::studly(class_basename($this->argument('name')));
        $this->call('make:resource', [
            'name' => "{$collection}\\{$collection}Collection",
        ]);
    }
}
