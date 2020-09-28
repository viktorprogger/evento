<?php

declare(strict_types=1);

namespace rssBot\models\action\action;

use Yiisoft\Yii\Queue\Message\MessageInterface;
use Yiisoft\Yii\Queue\Payload\PayloadInterface;

interface ActionFactoryInterface
{
    public function createFromMessage(MessageInterface $message): ActionInterface;
    public function createPayload(ActionInterface $action, $result): PayloadInterface;
}
