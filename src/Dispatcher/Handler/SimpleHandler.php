<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\Action\ActionInterface;
use Yiisoft\Validator\Rules;

class SimpleHandler extends AbstractHandler
{
    protected bool $synchronous;
    protected ActionInterface $action;

    public function __construct(ActionInterface $action, ?Rules $validator = null) {
        $this->action = $action;

        parent::__construct($validator);
    }

    public function handleAction($payload): void
    {
        $this->action->run($payload);
    }
}
