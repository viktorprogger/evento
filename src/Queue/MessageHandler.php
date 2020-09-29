<?php

declare(strict_types=1);

namespace Evento\Queue;

use Evento\Action\ActionFactoryInterface;
use Evento\Dispatcher\ActionDispatcherInterface;
use Yiisoft\Yii\Queue\Message\MessageInterface;

class MessageHandler
{
    private ActionFactoryInterface $factory;
    private ActionDispatcherInterface $dispatcher;

    public function __construct(ActionFactoryInterface $factory, ActionDispatcherInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    public function handle(MessageInterface $message): void
    {
        $action = $this->factory->createFromMessage($message);
        $result = $action->run($message->getPayloadData()['data'] ?? null);
        $this->dispatcher->dispatch($action, $result);
    }
}
