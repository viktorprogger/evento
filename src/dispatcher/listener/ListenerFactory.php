<?php

declare(strict_types=1);

namespace rssBot\models\action\dispatcher\listener;

use rssBot\models\action\action\ActionInterface;
use RuntimeException;
use Yiisoft\Factory\Factory;
use Yiisoft\Injector\Injector;
use Yiisoft\Validator\Result as ValidatorResult;
use Yiisoft\Validator\Rule;
use Yiisoft\Validator\Rules;

final class ListenerFactory
{
    /**
     * @var Factory
     */
    private Factory $factory;
    /**
     * @var Injector
     */
    private Injector $injector;

    public function __construct(Factory $factory, Injector $injector)
    {
        $this->factory = $factory;
        $this->injector = $injector;
    }

    public function create($listener): ListenerInterface
    {
        if ($listener instanceof ListenerInterface) {
            return $listener;
        }

        if (is_array($listener)) {
            $definition = $this->createArrayDefinition($listener);
        } else {
            $definition = $listener;
        }

        $result = $this->factory->create($definition);

        if ($result instanceof ActionInterface) {
            $definition = [
                '__class' => Listener::class,
                '__construct()' => [$result],
            ];
            $result = $this->factory->create($definition);
        }

        return $result;
    }

    /**
     * @param array $listener
     *
     * @return array
     *
     * @throws \Yiisoft\Factory\Exceptions\InvalidConfigException
     */
    private function createArrayDefinition(array $listener): array
    {
        if (!isset($listener['action'])) {
            throw new RuntimeException('Action listener array configuration must contain "action" key');
        }

        $definition = ['action' => $this->factory->create($listener['action'])];
        if (isset($listener['conditions'])) {
            $definition['validator'] = $this->createValidator($listener['conditions']);
        }
        if (isset($listener['synchronous'])) {
            $definition['synchronous'] = $listener['synchronous'];
        }

        return $definition;
    }

    private function createValidator($conditions): ?Rules
    {
        $exception = new RuntimeException('Action listener conditions must be either a ' . Rules::class . ' instance or an array of callables and/or ' . Rule::class . ' instances');

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
}
