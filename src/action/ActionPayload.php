<?php

declare(strict_types=1);

namespace rssBot\models\action\action;

use Yiisoft\Yii\Queue\Payload\PayloadInterface;

class ActionPayload implements PayloadInterface
{
    public const NAME = 'yii-resender'; // TODO fill after the package name invention
    private string $action;
    /**
     * @var mixed $data Data to be passed to the run method of the action
     */
    private $data;

    /**
     * ActionPayload constructor.
     *
     * @param string $action An action id (typically - class name) which will be used by {@see ActionFactory}
     *                       to create the action
     * @param mixed $data Data to be passed to the run method of the action
     */
    public function __construct(string $action, $data = null)
    {
        $this->action = $action;
        $this->data = $data;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getData(): array
    {
        return [
            'action' => $this->action,
            'data' => $this->data,
        ];
    }

    public function getMeta(): array
    {
        return [];
    }
}
