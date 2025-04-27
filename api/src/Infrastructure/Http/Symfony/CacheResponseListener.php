<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Symfony;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Remove default Cache-Control header (modify by proxy nginx)
 */
final readonly class CacheResponseListener
{
    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $response->headers->remove('Cache-Control');
    }
}
