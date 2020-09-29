<?php

declare(strict_types=1);

use Evento\Dispatcher\Handler\DeferredHandler;
use Evento\Dispatcher\Handler\HandlerInterface;
use Yiisoft\Validator\Rule\Url;
use Yiisoft\Validator\Rules;

return [
    'action' => [
        'action2',
        [
            'action' => 'action3',
            'conditions' => new Rules(/* some rules here */),
        ],
        [
            'action' => 'action4',
            'conditions' => static fn () => new Rules(/* some rules here */),
        ],
        [
            'action' => 'action5',
            'conditions' => [
                static fn ($payload, CustomDependency $dependency) => $dependency->suitesAction($payload),
                (new Url())->enableIDN(),
            ],
        ],
        [
            'action' => 'action6',
            '__class' => DeferredHandler::class,
        ],
        static fn (): HandlerInterface => new CustomHandler(),
    ],
];
