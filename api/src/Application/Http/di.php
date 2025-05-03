<?php

declare(strict_types=1);

namespace App\Application\Http;

use App\Infrastructure\DependencyInjection\Module;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $di): void {
    Module::create($di, dir: __DIR__, namespace: __NAMESPACE__)
        ->load('**/Action.php')
        ->tag('controller.service_arguments');
};
