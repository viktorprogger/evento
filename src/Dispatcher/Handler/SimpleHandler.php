<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\Action\ActionInterface;
use Evento\Dispatcher\ActionDispatcherInterface;
use Yiisoft\Validator\Rules;

class SimpleHandler extends AbstractHandler
{
    protected bool $synchronous;
    protected ActionInterface $action;
    /**
     * @var ActionDispatcherInterface
     */
    private ActionDispatcherInterface $dispatcher;

    public function __construct(ActionInterface $action, ActionDispatcherInterface $dispatcher, ?Rules $validator = null) {
        $this->action = $action;
        $this->dispatcher = $dispatcher;

        parent::__construct($validator);
    }

    public function handleAction($payload): void
    {
        $result = $this->action->run($payload);
        $this->dispatcher->dispatch($this->action, $result);
    }
}
