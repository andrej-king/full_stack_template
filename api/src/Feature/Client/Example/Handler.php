<?php

declare(strict_types=1);

namespace App\Feature\Client\Example;

use App\Infrastructure\PasswordHasher\PasswordHasher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class Handler
{
    public function __construct(
        private PasswordHasher $passwordHasher,
    ) {
    }

    #[AsMessageHandler]
    public function __invoke(Request $message): ?Response
    {
        $user = new Query()->getUserByEmail($message->email);

        if ($user === null) {
            return null;
        }

        if (!$this->passwordHasher->verify($message->password, $user->passwordHash)) {
            return null;
        }

        return new Response(
            login: $user->login,
            email: $user->email,
            firstName: $user->firstName,
            lastName: $user->lastName,
        );
    }
}
