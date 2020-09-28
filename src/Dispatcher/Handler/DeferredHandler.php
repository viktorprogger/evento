<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\action\ActionFactory;
use Evento\action\ActionFactoryInterface;
use Evento\action\ActionInterface;
use Yiisoft\Validator\Rules;
use Yiisoft\Yii\Queue\Queue;

class DeferredHandler extends AbstractHandler
{
    protected bool $synchronous;
    protected ActionInterface $action;
    private Queue $queue;
    private ActionFactoryInterface $factory;

    public function __construct(ActionInterface $action, Queue $queue, ActionFactoryInterface $factory, ?Rules $validator = null) {
        $this->action = $action;
        $this->queue = $queue;
        $this->factory = $factory;

        parent::__construct($validator);
    }

    public function handleAction($payload): void
    {
        $this->queue->push($this->factory->createPayload($this->action, $payload));
    }
}
