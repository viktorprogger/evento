<?php

declare(strict_types=1);

use Evento\Dispatcher\Handler\DeferredHandler;
use Evento\Dispatcher\Handler\HandlerInterface;
use Yiisoft\Validator\Rule\Url;
use Yiisoft\Validator\Rules;

return [
    Action::class => [
        Action2::class,
        [
            'action' => Action3::class,
            'conditions' => new Rules(/* some rules here */),
        ],
        [
            'action' => Action4::class,
            'conditions' => static fn () => new Rules(/* some rules here */),
        ],
        [
            'action' => Action5::class,
            'conditions' => [
                static fn ($payload, CustomDependency $dependency): bool => $dependency->suitesAction($payload),
                (new Url())->enableIDN(),
            ],
        ],
        [
            'action' => Action6::class,
            '__class' => DeferredHandler::class,
        ],
        static fn (): HandlerInterface => new CustomHandler(new Action7()),
    ],
];
