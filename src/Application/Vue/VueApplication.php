<?php

namespace AwStudio\Fjord\Application\Vue;

use Exception;
use Illuminate\View\View;
use AwStudio\Fjord\Application\Application;

class VueApplication
{
    /**
     * Props that are passed to the vue application.
     * 
     * @var array
     */
    protected $props = [];

    /**
     * Fjord application instance.
     * 
     * @var AwStudio\Fjord\Application\Application
     */
    protected $app;

    /**
     * Component instance.
     * 
     * @var \AwStudio\Fjord\Application\Vue\Component
     */
    protected $component;

    /**
     * Determines if the application has been build.
     * 
     * @var bool
     */
    protected $hasBeenBuild = false;
    
    protected $required = [
        'component',
    ];

    protected $compiler = [
        'model' => Props\ModelProp::class
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Build Vue application.
     * 
     * @param Illuminate\View\View $view
     * @return void
     */
    public function build(View $view)
    {
        if($view->getName() != "fjord::app") {
            throw new Exception('Fjord application can only be build for view "fjord::app".');
        }

        $this->setDefaultProps();
    
        $this->setPropsFromViewData($view->getData());
        
        $this->compileRootProps();

        $this->initializeComponent($this->props['component']);

        $this->hasBeenBuild = true;
    }

    protected function setDefaultProps()
    {
        $this->props = [
            'config' => collect(config('fjord')),
            'auth' => fjord_user(),
            'translatable' => collect([
                'language' => app()->getLocale(),
                'languages' => collect(config('translatable.locales')),
                'fallback_locale' => config('translatable.fallback_locale'),
            ]),
        ];
    }

    /**
     * Execute extensions for the given components.
     * 
     * @param Illuminate\View\View $view
     * @param array $extensions
     * @return void
     * 
     * @throws \Exception
     */
    public function extend(View $view, array $extensions)
    {
        if(! $this->hasBeenBuild()) {
            throw new Exception('Fjord Vue application cannot be extended if it has not been build.');
        }

        if(! $this->component) {
            return;
        }

        foreach($extensions as $extension) {

            // Look for extensions for the current component.
            if($this->component->getName() != $extension['component']) {
                continue;
            }
            
            $this->executeExtension(
                new $extension['extension']()
            );
        }
    }

    protected function executeExtension($extension)
    {
        $originals = $this->props['props'] ?? [];
        $extended = $extension->handle($originals);

        $this->props['props'] = $this->component->handleExtension($originals, $extended);

        //$this->component->setProps($this->props['props']);
    }

    /**
     * Initialize component class for the given vue component.
     * 
     * @var string $component
     */
    protected function initializeComponent(string $component)
    {
        foreach($this->app->get('packages')->all() as $package) {
            $components = $package->getComponents();
            foreach($components as $name => $class) {
                if($name != $component) {
                    continue;
                }

                $this->component = new $class($component, $this->props['props'] ?? []);
                return;
            }
        }
    }

    protected function setPropsFromViewData(array $data)
    {
        $this->checkForRequiredProps($data);

        foreach($data as $name => $value) {

            // Do not overwrite default props.
            if($this->propExists($name)) {
                continue;
            }
            
            $this->props[$name] = $value;
        }
    }

    protected function propExists($name)
    {
        return array_key_exists($name, $this->props);
    }

    protected function compileRootProps()
    {
        foreach($this->compiler as $prop => $compiler) {
            if(! $this->propExists($prop)) {
                continue;
            }

            $instance = with(new $compiler(
                $this->props[$prop]
            ));

            $this->props[$prop] = $instance->getValue();
        }
    }

    protected function checkForRequiredProps($data)
    {
        foreach($this->required as $name) {
            if(! array_key_exists($name, $data)) {
                throw new Exception("Missing required variable \"{$name}\" for view fjord::app.");
            }
        }
    }

    public function props()
    {
        return $this->props;
    }

    protected function hasBeenBuild()
    {
        return $this->hasBeenBuild;
    }
}
