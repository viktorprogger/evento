<?php

declare(strict_types=1);

use Evento\Action\ActionFactory;
use Evento\Action\ActionFactoryInterface;
use Evento\Dispatcher\ActionDispatcher;
use Evento\Dispatcher\ActionDispatcherInterface;
use Evento\Dispatcher\Handler\ActionHandlerProvider;
use Evento\Dispatcher\Handler\ActionHandlerProviderInterface;
use Yiisoft\Composer\Config\Builder;

return [
    ActionFactoryInterface::class => ActionFactory::class,
    ActionDispatcherInterface::class => ActionDispatcher::class,
    ActionHandlerProviderInterface::class => ActionHandlerProvider::class,
    ActionHandlerProvider::class => ['__construct()' => [Builder::require('actions')]],
];
