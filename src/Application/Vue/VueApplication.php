<?php

namespace Fjord\Application\Vue;

use Exception;
use Fjord\Application\Application;
use Fjord\Vue\Component;
use Illuminate\View\View;

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
     * @var Fjord\Application\Application
     */
    protected $app;

    /**
     * Component instance.
     *
     * @var \Fjord\Application\Vue\Component
     */
    protected $component;

    /**
     * Determines if the application has been build.
     *
     * @var bool
     */
    protected $built = false;

    /**
     * Required props that need to be passed to fjord::app view.
     *
     * @var array
     */
    protected $required = [
        'component',
    ];

    /**
     * Create new VueApplication instance.
     *
     * @param \Fjord\Application\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Build Vue application.
     *
     * @param Illuminate\View\View $view
     *
     * @return void
     */
    public function build(View $view)
    {
        if ($view->getName() != 'fjord::app') {
            throw new Exception('Fjord application can only be build for view "fjord::app".');
        }

        $this->setDefaultProps();

        $this->setPropsFromViewData($view->getData());

        $this->initializeComponent($this->props['component']);

        $this->built = true;
    }

    /**
     * Default props for Fjord Vue application are defined here.
     *
     * @return void
     */
    protected function setDefaultProps()
    {
        $this->props = [
            'config'       => collect(config('fjord')),
            'auth'         => fjord_user(),
            'app-locale'   => $this->app->get('translator')->getLocale(),
            'translatable' => collect([
                'language'        => app()->getLocale(),
                'languages'       => collect(config('translatable.locales')),
                'fallback_locale' => config('translatable.fallback_locale'),
            ]),
        ];
    }

    /**
     * Get extensions for component name-.
     *
     * @param Component $component
     *
     * @return void
     */
    protected function getExtensions(Component $component)
    {
        $extensions = [];
        foreach ($this->app->getExtensions() as $extension) {
            if ($extension['component'] == $component->getName()) {
                $extensions[] = $extension;
            }
        }

        return $extensions;
    }

    /**
     * Execute extensions for the given components.
     *
     * @param \Fjord\Vue\Component $component
     *
     * @throws \Exception
     *
     * @return void
     */
    public function extend(Component $component)
    {
        if (!$this->hasBeenBuilt()) {
            throw new Exception('Fjord Vue application cannot be extended if it has not been built.');
        }

        foreach ($this->getExtensions($component) as $extension) {

            // Look for extensions for the current component.
            if ($component->getName() != $extension['component']) {
                continue;
            }

            // Resolve extension in component.
            if (method_exists($component, 'resolveExtension')) {
                if (
                    !$component->resolveExtension($extension['name'])
                    && $extension['name'] != ''
                ) {
                    continue;
                }
            }

            $this->executeExtension(
                $component,
                new $extension['extension']($extension['name'])
            );
        }
    }

    /**
     * Execute extension for component if user has permission.
     *
     * @param Component $component
     * @param $extension
     *
     * @return void
     */
    public function executeExtension(Component $component, $extension)
    {
        if (!$extension->authenticate(fjord_user())) {
            return;
        }

        $extension->handle(
            $component
        );
    }

    /**
     * Initialize component class for the given vue component.
     *
     * @var string|Component
     */
    protected function initializeComponent($component)
    {
        if ($component instanceof Component) {
            $this->component = $component;
        } else {
            $this->component = component($component);
        }

        $this->component->bind($this->props['props']);
    }

    /**
     * Merge view data into props.
     *
     * @param array $data
     *
     * @return void
     */
    protected function setPropsFromViewData(array $data)
    {
        $this->checkForRequiredProps($data);

        foreach ($data as $name => $value) {

            // Do not overwrite default props.
            if ($this->propExists($name)) {
                continue;
            }

            $this->props[$name] = $value;
        }

        // Default props.
        if (!array_key_exists('props', $this->props)) {
            $this->props['props'] = [];
        }
    }

    /**
     * Checks if prop exists.
     *
     * @param string $name
     *
     * @return bool
     */
    protected function propExists(string $name)
    {
        return array_key_exists($name, $this->props);
    }

    /**
     * Check if all required props are passed to view.
     *
     * @param array $data
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function checkForRequiredProps($data)
    {
        foreach ($this->required as $name) {
            if (!array_key_exists($name, $data)) {
                throw new Exception("Missing required variable \"{$name}\" for view fjord::app.");
            }
        }
    }

    /**
     * Get props for Fjord Vue application.
     *
     * @return array $props
     */
    public function props()
    {
        $component = $this->component->toArray();
        $component['component'] = $component['name'];
        unset($component['name']);

        $props = array_merge($this->props, $component);

        return $props;
    }

    /**
     * Set prop for Fjord Vue application.
     *
     * @param string $name
     * @param $value
     *
     * @return void
     */
    public function setProp(string $name, $value)
    {
        $this->props[$name] = $value;
    }

    /**
     * Checks if Fjord Vue application has been build.
     *
     * @return bool
     */
    public function hasBeenBuilt()
    {
        return $this->built;
    }
}
