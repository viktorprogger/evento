<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher\listener;

use rssBot\models\action\action\ActionInterface;

interface ActionListenerProviderInterface
{
    /**
     * @param \rssBot\models\action\action\ActionInterface $action An executed action for which to return the relevant listeners
     *
     * @return ListenerInterface[]
     */
    public function getListenersForAction(ActionInterface $action): iterable;
}
