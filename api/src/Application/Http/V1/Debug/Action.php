<?php

declare(strict_types=1);

namespace App\Application\Http\V1\Debug;

use App\Infrastructure\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller work only with local env
 */
final class Action extends AbstractController
{
    #[Route(path: '/debug', name: 'test', methods: ['GET'], env: 'local')]
    public function __invoke(): Response
    {
//        phpinfo();

        return new JsonResponse([
            'msg' => 'debug',
            'env' => Env::get('APP_ENV')
        ]);
    }
}
