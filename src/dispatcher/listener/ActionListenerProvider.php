<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher\listener;

use rssBot\models\action\action\ActionInterface;

final class ActionListenerProvider implements ActionListenerProviderInterface
{
    private array $resolved = [];
    private array $listeners;
    private ListenerFactory $factory;

    public function __construct(array $listeners, ListenerFactory $factory)
    {
        $this->listeners = $listeners;
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function getListenersForAction(ActionInterface $action): iterable
    {
        yield from $this->getForEvents(get_class($action));
        yield from $this->getForEvents(...array_values(class_parents($action)));
        yield from $this->getForEvents(...array_values(class_implements($action)));
    }

    /**
     * @param string ...$eventClassNames
     *
     * @return \rssBot\models\action\action\ActionInterface[]
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

        foreach ($this->listeners[$eventClassName] ?? [] as $listener) {
            $result[] = $this->factory->create($listener['action']);
        }

        return $result;
    }
}
