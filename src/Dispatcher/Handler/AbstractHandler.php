<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;


use Yiisoft\Validator\Rules;

abstract class AbstractHandler implements HandlerInterface
{
    protected ?Rules $validator;

    public function __construct(?Rules $validator)
    {
        $this->validator = $validator;
    }

    public function suites($payload): bool
    {
        return $this->validator === null || $this->validator->validate($payload)->isValid();
    }
}
