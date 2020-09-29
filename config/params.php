<?php

declare(strict_types=1);

use Evento\Action\ActionPayload;
use Evento\Queue\MessageHandler;

return [
    'yiisoft/yii-queue' => [
        'handlers' => [
            ActionPayload::NAME => [MessageHandler::class, 'handle'],
        ],
    ],
];
