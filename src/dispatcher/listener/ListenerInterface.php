<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher\listener;

use rssBot\models\action\action\ActionInterface;

interface ListenerInterface
{
    public function isSynchronous(): bool;

    public function getAction(): ActionInterface;

    public function suites($payload): bool;
}
