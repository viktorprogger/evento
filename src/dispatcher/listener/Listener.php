<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher\listener;

use rssBot\models\action\action\ActionInterface;
use Yiisoft\Validator\Rules;

class Listener implements ListenerInterface
{
    protected bool $synchronous;
    protected ?Rules $validator;
    /**
     * @var mixed
     */
    protected ActionInterface $action;
    protected array $definitionNormalized = [];

    public function __construct(
        ActionInterface $action,
        ?Rules $validator = null,
        bool $synchronous = true
    ) {
        $this->action = $action;
        $this->validator = $validator;
        $this->synchronous = $synchronous;
    }

    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    public function isSynchronous(): bool
    {
        return $this->synchronous;
    }

    public function suites($payload): bool
    {
        return $this->validator->validate($payload)->isValid();
    }
}
