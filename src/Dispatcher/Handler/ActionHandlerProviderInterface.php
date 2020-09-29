<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\Action\ActionInterface;

interface ActionHandlerProviderInterface
{
    /**
     * @param ActionInterface $action An executed action for which to return the relevant listeners
     *
     * @return HandlerInterface[]
     */
    public function getHandlersForAction(ActionInterface $action): iterable;
}
