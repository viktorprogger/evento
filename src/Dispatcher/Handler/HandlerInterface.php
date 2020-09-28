<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

interface HandlerInterface
{
    public function handleAction($payload): void;

    public function suites($payload): bool;
}
