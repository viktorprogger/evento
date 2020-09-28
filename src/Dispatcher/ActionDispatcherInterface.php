<?php

declare(strict_types=1);

namespace Evento\Dispatcher;

use Evento\action\ActionInterface;

interface ActionDispatcherInterface
{
    public function dispatch(ActionInterface $action, $result): void;
}
