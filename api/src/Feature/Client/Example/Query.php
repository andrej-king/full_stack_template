<?php

declare(strict_types=1);

namespace App\Feature\Client\Example;

use stdClass;

final readonly class Query
{
    public function getUserByEmail(string $email): ?stdClass
    {
        $email = strtolower($email);

        $tempUserExample = new stdClass();
        $tempUserExample->login = 'test';
        $tempUserExample->email = 'test@example.com';
        $tempUserExample->firstName = 'John';
        $tempUserExample->lastName = 'Doe';

        // test123
        $tempUserExample->passwordHash = '$argon2i$v=19$m=65536,t=4,p=1$My41WG9sUTUzanNwMUdwNw$SV3W3a7rdd/jg2duYp/t5rB0270xwdp92EEiO/hyRdE';

        return $tempUserExample->email === $email ? $tempUserExample : null;
    }
}
