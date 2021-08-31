<?php

namespace Ignite\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudCommand extends Command
{
    use Concerns\ManagesPaths,
        Concerns\CreatesConfig,
        Concerns\CreatesController,
        Concerns\CreatesModels,
        Concerns\CreatesMigration;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'lit:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This wizard will generate all the files needed for a new crud module';

    /**
     * Create new CrudCommand instance.
     *
     * @param  Filesystem $files
     * @return void
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
        $this->info('    __     _  __     ______ ____   __  __ ____  ');
        $this->info('   / /    (_)/ /_   / ____// __ \ / / / // __ \ ');
        $this->info('  / /    / // __/  / /    / /_/ // / / // / / / ');
        $this->info(' / /___ / // /_   / /___ / _, _// /_/ // /_/ /  ');
        $this->info('/_____//_/ \__/   \____//_/ |_| \____//_____/   ');
        $this->info('                                                ');

        $this->setArguments();

        $this->createModel();
        $this->createMigration();
        $this->createController();
        $this->createConfig();

        $step = 1;
        $this->info("\n----- finished -----\n");
        $this->line("<info>{$step}) Migration:</info> Edit the generated migration in<info> ".$this->clickablePath($this->migrationPath()).' </info>and migrate after.');
        $step++;
        $this->line("<info>{$step}) Model:</info> Set the fillable fields in <info>".$this->clickablePath($this->modelPath()).'</info>');
        $step++;
        if ($this->translatable) {
            $this->line("<info>{$step}) Translation Model:</info> Set the fillable fields as well in <info>".$this->clickablePath($this->translationModelPath()).'</info>');
            $step++;
        }
        $this->line("<info>{$step}) Config:</info> Configure the crud-model in <info>".$this->clickablePath($this->configPath()).'</info>');
        $step++;
        $this->line("<info>{$step}) Controller:</info> configure the authorization in <info>".$this->clickablePath($this->controllerPath()).'</info>');
        $step++;
        $this->line("<info>{$step}) Navigation:</info> add a navigation entry in <info>".$this->clickablePath(lit()->path('Config/NavigationConfig.php')).'</info>');
    }

    /**
     * Get clickable path.
     *
     * @return string
     */
    protected function clickablePath($path)
    {
        return Str::after($path, base_path().DIRECTORY_SEPARATOR);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the model class'],
            ['config', InputArgument::OPTIONAL, 'The name of the config class'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['media', 'm', InputOption::VALUE_NONE, 'whether this model has media'],
            ['translatable', 't', InputOption::VALUE_NONE, 'whether this model should be translatable'],
            ['slug', 's', InputOption::VALUE_NONE, 'whether this model should have a slug'],
            ['factory', 'f', InputOption::VALUE_NONE, 'whether to create a model factory'],
        ];
    }

    /**
     * Get arguments.
     *
     * @return void
     */
    protected function setArguments()
    {
        $this->model = $this->argument('model');
        if (! $this->model) {
            $this->model = $this->ask('enter the model name (PascalCase, singular)');
        }

        $this->model = ucfirst(Str::singular($this->model));
        $this->config = $this->argument('config') ?: "{$this->model}Config";
        $this->controller = Str::replaceLast('Config', '', $this->config).'Controller';
        $this->table = Str::snake(Str::plural($this->model));
        $this->translationsTable = Str::singular($this->table).'_translations';

        $this->media = $this->option('media');
        $this->slug = $this->option('slug');
        $this->translatable = $this->option('translatable');
        $this->factory = $this->option('factory');
    }
}
