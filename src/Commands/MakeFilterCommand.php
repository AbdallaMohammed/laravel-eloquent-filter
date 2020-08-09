<?php

namespace LaravelEloquentFilter\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class MakeFilterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model filter';

    /**
     * Class to create.
     *
     * @var array|string
     */
    protected $class;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * MakeFilterCommand constructor.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->makeClassName()->compileStub();
        $this->info(class_basename($this->getClassName()).' created successfully!');
    }

    /**
     * Compile the stub file to generate a filter
     * 
     * @return void
     */
    public function compileStub()
    {
        if ($this->files->exists($path = $this->getPath())) {
            $this->error("\n\n\t".$path.' already exists!'."\n");
            die;
        }

        $this->makeDirectory($path);

        $stubPath = Config::get('laravel-eloquent-filter.stub', __DIR__.'/../stubs/filter.stub');
        
        if (! $this->files->exists($stubPath) || ! is_readable($stubPath)) {
            $this->error(sprintf('File "%s" does not exist or is unreadable.', $stubPath));
            die;
        }

        $tmp = $this->applyValuesToStub($this->files->get($stubPath));

        $this->files->put($path, $tmp);
    }

    /**
     * @return string
     */
    public function applyValuesToStub($stub)
    {
        $className = $this->getClassBasename($this->getClassName());
        $search = ['{{class}}', '{{namespace}}'];
        $replace = [$className, str_replace('\\'.$className, '', $this->getClassName())];

        return str_replace($search, $replace, $stub);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->laravel->path.DIRECTORY_SEPARATOR.$this->getFileName();
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return str_replace([$this->getAppNamespace(), '\\'], ['', DIRECTORY_SEPARATOR], $this->getClassName().'.php');
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Create filter class name.
     *
     * @return $this
     */
    public function makeClassName()
    {
        $parts = array_map([Str::class, 'studly'], explode('\\', $this->argument('name')));
        $className = array_pop($parts);
        $ns = count($parts) > 0 ? implode('\\', $parts).'\\' : '';

        $fqClass = Config::get('laravel-eloquent-filter.namespace', 'App\\Filters\\').$ns.$className;

        if (substr($fqClass, -6, 6) !== 'Filter') {
            $fqClass .= 'Filter';
        }

        if (class_exists($fqClass)) {
            $this->error("\n\n\t$fqClass already exists!\n");
            die;
        }

        $this->setClassName($fqClass);

        return $this;
    }

    /**
     * Set the model name
     * 
     * @return $this
     */
    public function setClassName($name)
    {
        $this->class = $name;

        return $this;
    }
    
    /**
     * Get the model name
     * 
     * @return string
     */
    public function getClassName()
    {
        return $this->class;
    }
}