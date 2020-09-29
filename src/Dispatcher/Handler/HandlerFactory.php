<?php

declare(strict_types=1);

namespace Evento\Dispatcher\Handler;

use Evento\Action\ActionInterface;
use RuntimeException;
use Yiisoft\Factory\Exceptions\InvalidConfigException;
use Yiisoft\Factory\Factory;
use Yiisoft\Injector\Injector;
use Yiisoft\Validator\Result as ValidatorResult;
use Yiisoft\Validator\Rule;
use Yiisoft\Validator\Rules;

final class HandlerFactory
{
    private Factory $factory;
    private Injector $injector;
    private bool $deferredDefault = false;

    public function __construct(Factory $factory, Injector $injector)
    {
        $this->factory = $factory;
        $this->injector = $injector;
    }

    public function withDeferredDefault(bool $default = true): self
    {
        $that = clone $this;
        $that->deferredDefault = $default;

        return $that;
    }

    public function create($handler): HandlerInterface
    {
        if ($handler instanceof HandlerInterface) {
            return $handler;
        }

        if (is_array($handler)) {
            $definition = $this->createArrayDefinition($handler);
        } else {
            $definition = $handler;
        }

        $result = $this->factory->create($definition);

        if ($result instanceof ActionInterface) {
            $definition = [
                '__class' => $this->getDefaultHandler(),
                '__construct()' => [$result],
            ];
            $result = $this->factory->create($definition);
        }

        return $result;
    }

    /**
     * @param array $handler
     *
     * @return array
     *
     * @throws InvalidConfigException
     */
    private function createArrayDefinition(array $handler): array
    {
        if (!isset($handler['action'])) {
            throw new RuntimeException('Action listener array configuration must contain "action" key');
        }

        $definition = ['action' => $this->factory->create($handler['action'])];
        if (isset($handler['conditions'])) {
            $definition['validator'] = $this->createValidator($handler['conditions']);
        }
        if (isset($handler['__class'])) {
            $definition['__class'] = $handler['__class'];
        } else {
            $definition['__class'] = SimpleHandler::class;
        }

        return $definition;
    }

    private function createValidator($conditions): ?Rules
    {
        $exception = new RuntimeException(
            'Action listener conditions must be either a ' . Rules::class
                . ' instance or an array of callables and/or ' . Rule::class . ' instances'
        );

        if ($conditions instanceof Rules) {
            return $conditions;
        }

        if ($conditions === [] || $conditions === null) {
            return null;
        }

        if (!is_array($conditions)) {
            throw $exception;
        }

        $rules = [];
        foreach ($conditions as $condition) {
            if ($condition instanceof Rule) {
                $rules[] = $condition;
            } elseif (is_callable($condition)) {
                $rules[] = function ($actionResult) use ($condition) {
                    $result = $this->injector->invoke($condition, [$actionResult]);
                    if ($result instanceof ValidatorResult) {
                        return $result;
                    }

                    $validatorResult = new ValidatorResult();
                    if (!$result) {
                        $validatorResult->addError('Condition is not met');
                    }

                    return $validatorResult;
                };
            } else {
                throw $exception;
            }
        }

        return new Rules($rules);
    }

    private function getDefaultHandler(): string
    {
        return $this->deferredDefault ? DeferredHandler::class : SimpleHandler::class;
    }
}
