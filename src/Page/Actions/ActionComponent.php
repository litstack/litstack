<?php

namespace Ignite\Page\Actions;

use Ignite\Contracts\Page\FileDownloadAction;
use Ignite\Page\RunActionEvent;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;
use InvalidArgumentException;

class ActionComponent extends Component
{
    use StaticComponentName;

    /**
     * Vue component name.
     *
     * @var string
     */
    protected $name = 'lit-action';

    /**
     * Action modal instance.
     *
     * @var ActionModal|null
     */
    protected $modal;

    /**
     * Event handlers.
     *
     * @var array
     */
    protected $events = [
        'run' => RunActionEvent::class,
    ];

    /**
     * Action title.
     *
     * @var string
     */
    protected $title;

    /**
     * The namespace of the action.
     *
     * @var string
     */
    protected $action;

    /**
     * The action instance.
     *
     * @var mixed
     */
    protected $instance;

    /**
     * Create new ActionComponent instance.
     *
     * @param string    $action
     * @param Component $wrapper
     */
    public function __construct($action, $title, Component $wrapper = null)
    {
        $this->title = $title;
        $this->action = $action;
        $this->addEventData(['action' => $action]);

        if ($wrapper) {
            $this->wrapper($wrapper);
        }

        $this->setModal();
        $this->setAuthorization();
    }

    /**
     * Get action modal instance.
     *
     * @return ActionModal|null
     */
    public function getModal()
    {
        return $this->modal;
    }

    /**
     * Authorize the component.
     *
     * @param  Closure|bool $authorizer
     * @return $this
     */
    public function authorize($authorizer)
    {
        if ($wrapper = $this->getProp('wrapper')) {
            $wrapper->authorize($authorizer);
        }

        return parent::authorize($authorizer);
    }

    /**
     * Get the action instance.
     *
     * @return mixed
     */
    public function getInstance()
    {
        return $this->instance ?: $this->instance = app()->make($this->action);
    }

    /**
     * Get action namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->action;
    }

    /**
     * Set a wrapper component.
     *
     * @param  Component $wrapper
     * @return $this
     */
    public function wrapper(Component $wrapper)
    {
        return $this->prop('wrapper', $wrapper);
    }

    /**
     * Set action modal.
     *
     * @param  string $action
     * @return void
     */
    protected function setModal()
    {
        $instance = $this->getInstance();

        if (! method_exists($instance, 'modal')) {
            return;
        }

        $this->prop('modal', $this->modal = $this->newActionModal());

        $instance->modal($this->modal->title($this->title));
    }

    /**
     * Set action authorization.
     *
     * @param  string $action
     * @return void
     */
    protected function setAuthorization()
    {
        $instance = $this->getInstance();

        if (! method_exists($instance, 'authorize')) {
            return;
        }

        $this->authorize(fn ($user) => $instance->authorize($user));
    }

    /**
     * Get new action modal.
     *
     * @return ActionModal
     */
    protected function newActionModal()
    {
        return new ActionModal;
    }

    /**
     * Set the eventhandler.
     *
     * @param  string $handler
     * @return $this
     */
    public function setEventHandler($handler)
    {
        $this->throwIfHandlerIsNotValid($handler);

        $this
            ->on('run', $handler)
            ->isFileDownload(
                is_subclass_of($this->action, FileDownloadAction::class)
            );

        return $this;
    }

    /**
     * Add event data.
     *
     * @param  array $data
     * @return void
     */
    public function addEventData(array $data)
    {
        return $this->prop('eventData', array_merge(
            $this->props['eventData'] ?? [],
            $data
        ));
    }

    /**
     * Throw exception if the given handler cannot be used.
     *
     * @param  string $handler
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function throwIfHandlerIsNotValid($handler)
    {
        if (is_subclass_of($handler, RunActionEvent::class)) {
            return;
        }

        if ($handler == RunActionEvent::class) {
            return;
        }

        throw new InvalidArgumentException("Invalid action event handler [{$handler}].");
    }
}
