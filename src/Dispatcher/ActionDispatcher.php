<?php

declare(strict_types=1);

namespace Evento\Dispatcher;

use Evento\action\ActionInterface;
use Evento\Dispatcher\Handler\ActionHandlerProviderInterface;
use Evento\Dispatcher\Handler\HandlerInterface;

class ActionDispatcher implements ActionDispatcherInterface
{
    private ActionHandlerProviderInterface $provider;

    public function __construct(ActionHandlerProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function dispatch(ActionInterface $action, $result): void
    {
        foreach ($this->provider->getHandlersForAction($action) as $listener) {
            $this->dispatchHandler($listener, $result);
        }
    }

    protected function dispatchHandler(HandlerInterface $handler, $result): void
    {
        if ($handler->suites($result)) {
            $handler->handleAction($result);
        }
    }
}
