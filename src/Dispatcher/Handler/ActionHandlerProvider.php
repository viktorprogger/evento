<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\Action\ActionInterface;

final class ActionHandlerProvider implements ActionHandlerProviderInterface
{
    private array $resolved = [];
    private array $handlers;
    private HandlerFactory $factory;

    public function __construct(array $handlers, HandlerFactory $factory)
    {
        $this->handlers = $handlers;
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function getHandlersForAction(ActionInterface $action): iterable
    {
        yield from $this->getForEvents(get_class($action));
        yield from $this->getForEvents(...array_values(class_parents($action)));
        yield from $this->getForEvents(...array_values(class_implements($action)));
    }

    /**
     * @param string ...$eventClassNames
     *
     * @return ActionInterface[]
     */
    private function getForEvents(string ...$eventClassNames): iterable
    {
        foreach ($eventClassNames as $eventClassName) {
            if (!isset($this->resolved[$eventClassName])) {
                $this->resolved[$eventClassName] = $this->resolve($eventClassName);
            }

            yield from $this->resolved[$eventClassName];
        }
    }

    private function resolve(string $eventClassName): array
    {
        $result = [];

        foreach ($this->handlers[$eventClassName] ?? [] as $listener) {
            $result[] = $this->factory->create($listener);
        }

        return $result;
    }
}
