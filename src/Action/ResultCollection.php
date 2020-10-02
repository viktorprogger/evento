<?php

declare(strict_types=1);

namespace Evento\Action;

use IteratorIterator;
use Traversable;

class ResultCollection implements ResultCollectionInterface
{
    private iterable $data;

    public function __construct(iterable $data)
    {
        if ($data instanceof Traversable) {
            $this->data = new IteratorIterator($data);
        } else {
            $this->data = $data;
        }
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return is_array($this->data) ? current($this->data) : $this->data->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        is_array($this->data) ? next($this->data) : $this->data->next();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return is_array($this->data) ? key($this->data) : $this->data->key();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->key() !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        is_array($this->data) ? reset($this->data) : $this->data->rewind();
    }
}
