<?php

declare(strict_types=1);

namespace Evento\Dispatcher;

use Evento\Action\ActionInterface;
use Evento\Action\ResultCollectionInterface;
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
        foreach ($this->provider->getHandlersForAction($action) as $handler) {
            if ($result instanceof ResultCollectionInterface) {
                $this->dispatchCollection($handler, $result);
            } else {
                $this->dispatchHandler($handler, $result);
            }
        }
    }

    protected function dispatchHandler(HandlerInterface $handler, $result): void
    {
        if ($handler->suites($result)) {
            $handler->handleAction($result);
        }
    }

    protected function dispatchCollection(HandlerInterface $handler, ResultCollectionInterface $result): void
    {
        foreach ($result as $item) {
            if ($item instanceof ResultCollectionInterface) {
                $this->dispatchCollection($handler, $item);
            } else {
                $this->dispatchHandler($handler, $item);
            }
        }
    }
}
