<?php

namespace DigitalUnityCa\Translatable\App\Console\Commands;

use DigitalUnityCa\Translatable\App\Models\BaseTranslation;
use DigitalUnityCa\Translatable\App\Traits\Translatable;
use Illuminate\Console\Command;

/**
 * Class MakeTranslatable
 * @package DigitalUnityCa\Translatable\App\Console\Commands
 * @todo check className existence for Translation and Migration
 */
class MakeTranslatable extends Command
{
    /**
     * Trait full qualified class name
     *
     * @var string
     */
    private $trait;

    /**
     * Model name
     *
     * @var string
     */
    private $modelClass;

    /**
     * Model name
     *
     * @var string
     */
    private $modelName;

    /**
     * Models path
     *
     * @var string
     */
    private $modelsPath;

    /**
     * Migrations path
     *
     * @var string
     */
    private $migrationsPath;

    /**
     * Need migrate option
     *
     * @var boolean
     */
    private $needMigrate;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:translatable:make {model} 
                                                        {--models-path=}
                                                        {--migrations-path=}
                                                        {--migrate}
                                                        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a translation model and a migration for existing Model.';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->trait = Translatable::class;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->modelClass   = $this->argument('model');
        $this->needMigrate  = (bool) $this->option('migrate');

        // Get paths and remove traling slashes
        $this->modelsPath     = rtrim($this->option('models-path')??base_path('app/Models'),'/');
        $this->migrationsPath = rtrim($this->option('migrations-path')??database_path('migrations'),'/');


        // try to instantiate the class
        // no check need, will throw a FatalErrorException
        try{
            $model = new $this->modelClass;
            $reflect = new \ReflectionClass($model);
            $this->modelName = $reflect->getShortName();
        }catch(\Exception $exception){
            $this->error($exception->getMessage());
            die;
        }


        // Creating the Translation Model
        try{
            $this->info('Creating model Translation class');

            $tranlationModelPath = $this->modelsPath.'/'.$this->modelName.'Translation.php';
            file_put_contents($tranlationModelPath,
                                    $this->getModelTemplate()
            );

            $this->comment('Done.');
        }catch(\Exception $exception){
            $this->error($exception->getMessage());
            die;
        }

        // Creating the migration
        try{
            $this->info('Creating model Translation migration');

            $migrationFileName = $this->migrationsPath.'/'.date('Y_m_d_His').'_create_'.strtolower($this->modelName).'_translations_table.php';
            file_put_contents($migrationFileName, $this->getMigrationTemplate());
            $this->comment('Done.');

        }catch(\Exception $exception){
            $this->error($exception->getMessage());
            die;
        }

        // Migrate
        if ($this->needMigrate) {
            if ($this->confirm('Are you sure to run the migrations?')) {
                $this->call('migrate',[
                    '--step' => true
                ]);
            }
        }

        // if the Trait is not present
        if (!in_array($this->trait, class_uses($model))) {
            $this->info('Please add the '.$this->trait.' use in your model.');
            die;
        }

    }

    /**
     * Get model
     * @return string
     */
    private function getTemplate(string $filename)
    {
        $fileContent = file_get_contents(__DIR__.'/templates/'.$filename);

        $fileContent = str_replace('###', strtolower($this->modelName), $fileContent);
        $fileContent = str_replace('##', $this->modelName, $fileContent);
        $fileContent = str_replace('#class#', BaseTranslation::class, $fileContent);

        return $fileContent;
    }

    /**
     * Get model
     * @return string
     */
    private function getModelTemplate()
    {
        return $this->getTemplate('translationModelTemplate.txt');
    }

    /**
     * Get migration
     * @return string
     */
    private function getMigrationTemplate()
    {
        return $this->getTemplate('migrationModelTemplate.txt');
    }
}
