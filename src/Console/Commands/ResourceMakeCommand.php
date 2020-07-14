<?php

namespace Larafast\Fastapi\Console\Commands;

use Illuminate\Support\Str;
use Larafast\Fastapi\Console\Contracts\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'fastApi:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form resource class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('fastApi.stubs_dir') . '/resource.stub';
    }

    protected function buildClass($name)
    {
        $replace = [];
        if ($model = $this->option('model')) {
            $model = Str::studly(class_basename($this->option('model')));
            $slug = Str::slug(str_to_words($model), '_');
            $replace['$modelSlug$'] = $slug;
            $replace['$modelTable$'] = Str::plural($slug, 2);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
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
        return $rootNamespace . '\Http\Resources';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The given model.'],
            ['type', 't', InputOption::VALUE_OPTIONAL, 'Type of resource. Values can be store or update'],
        ];
    }
}
