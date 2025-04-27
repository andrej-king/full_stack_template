<?php

declare(strict_types=1);

namespace App\Feature\Client\Example;

final readonly class Response
{
    public function __construct(
        public string $login,
        public string $email,
        public string $firstName,
        public string $lastName,
    ) {
    }
}
