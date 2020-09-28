<?php

declare(strict_types=1);

namespace rssBot\models\action\action;

interface ActionInterface
{
    public function run($data);
}
