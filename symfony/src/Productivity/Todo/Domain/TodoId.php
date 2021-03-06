<?php

declare(strict_types=1);

namespace Productivity\Todo\Domain;

use Ramsey\Uuid\Uuid;

final class TodoId
{
    public function __construct(private string $id)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        if (false === Uuid::isValid($id)) {
            throw new \DomainException(\sprintf("TodoId '%s' is not valid", $id));
        }

        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
