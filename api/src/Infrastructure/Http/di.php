<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\DependencyInjection\Module;
use App\Infrastructure\Http\Symfony\CacheResponseListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    $module = Module::create($di, dir: __DIR__, namespace: __NAMESPACE__);

    // TODO subscribers, error handler

    // remove default Cache-Control header
    $module
        ->set(CacheResponseListener::class);
};
