<?php

declare(strict_types=1);

namespace App\Application\Http\V1\Example;

use App\Feature\Client\Example\Request;
use App\Infrastructure\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class Action extends AbstractController
{
    use HandleTrait;

    public function __construct(
        // trait use it (readonly = error)
        private MessageBusInterface $messageBus,
    ) {
    }

    #[Route(path: '/example', name: 'example', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] Request $request): JsonResponse
    {
        return new JsonResponse([
            'version' => '1.0',
            'result' => $this->handle($request),
            'env' => Env::get("APP_ENV", ''),
        ]);
    }
}
