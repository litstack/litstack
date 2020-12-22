<?php

namespace Ignite\Foundation;

use Illuminate\Foundation\Application;
use InvalidArgumentException;

class LightsOn
{
    /**
     * Laravel application instance.
     *
     * @var Application
     */
    protected $laravel;

    /**
     * The litstack model implementations.
     *
     * @var array
     */
    protected $models = [
        'repeatable' => \Ignite\Crud\Models\Repeatable::class,
        'list_item'  => \Ignite\Crud\Models\ListItem::class,
        'relation'   => \Ignite\Crud\Models\Relation::class,
        'form'       => \Ignite\Crud\Models\Form::class,
    ];

    /**
     * Litstack instance.
     *
     * @var Litstack
     */
    protected $liststack;

    /**
     * Create new LightsOn instance.
     *
     * @param  Application $app
     * @param  Litstack    $litstack
     * @return void
     */
    public function __construct(Application $laravel, Litstack $litstack)
    {
        $this->laravel = $laravel;
        $this->litstack = $litstack;
    }

    /**
     * Ignite the litstack.
     *
     * @return void
     */
    public function ignite()
    {
        if (! $this->litstack->installed()) {
            return;
        }

        $this->checkLitstackModels();

        $this->laravel->register(
            \Ignite\Application\ApplicationServiceProvider::class
        );

        $this->laravel->singleton('lit.app', function ($app) {
            $litstackApp = new \Ignite\Application\Application($app);

            // Bind litstack application.
            $this->litstack->bindApp($litstackApp);

            return $litstackApp;
        });

        $this->laravel->singleton(\Ignite\Application\Kernel::class, function ($app) {
            return new \Lit\Kernel($app->get('lit.app'));
        });

        // Initialize kernel singleton.
        $this->laravel[\Ignite\Application\Kernel::class];
    }

    /**
     * Check if the litstack models extend the correct classes.
     *
     * @return void
     */
    protected function checkLitstackModels()
    {
        foreach ($this->models as $key => $model) {
            $implemenation = config("lit.models.{$key}");

            if ($implemenation === $model) {
                continue;
            }

            if (is_subclass_of($implemenation, $model)) {
                continue;
            }

            throw new InvalidArgumentException("The model set in [lit.config.{$key}] must extend {$model}.");
        }
    }
}
