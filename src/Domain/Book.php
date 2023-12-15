<?php

declare(strict_types=1);

namespace NereaEnrique\Search\Domain;

final class Book
{
    public function __construct(
        public readonly string $title,
        public readonly string $author,
        public readonly string $description,
        public readonly string $kind,
    ) {}

    public function toDocument(): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'kind' => $this->kind,
        ];
    }
}
