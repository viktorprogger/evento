<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher;

use rssBot\models\action\action\ActionInterface;

interface ActionDispatcherInterface
{
    public function dispatch(ActionInterface $action, $result): void;
}
