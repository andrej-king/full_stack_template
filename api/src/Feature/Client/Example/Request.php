<?php

declare(strict_types=1);


namespace App\Feature\Client\Example;

use App\Infrastructure\MessageBus\Message;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-immutable
 */
final readonly class Request implements Message
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\Length(min: 6)]
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
