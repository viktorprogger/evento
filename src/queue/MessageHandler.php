<?php

declare(strict_types=1);

namespace rssBot\models\action\queue;


use rssBot\models\action\action\ActionFactoryInterface;
use rssBot\models\action\dispatcher\ActionDispatcher;
use Yiisoft\Yii\Queue\Message\MessageInterface;

class MessageHandler
{
    /**
     * @var \rssBot\models\action\action\ActionFactoryInterface
     */
    private ActionFactoryInterface $factory;
    /**
     * @var ActionDispatcher
     */
    private ActionDispatcher $dispatcher;

    public function __construct(ActionFactoryInterface $factory, ActionDispatcher $dispatcher)
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
