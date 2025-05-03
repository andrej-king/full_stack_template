<?php

declare(strict_types=1);

namespace App\Application\Http;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Create alias for latest api versions
 */
return static function (RoutingConfigurator $routing): void {
    $lastVersionDir = '/V1';

    $routing
        ->import(__DIR__ . $lastVersionDir, 'attribute')
        ->prefix($prefix = '/latest', false)
        ->namePrefix('latest_')
        ->defaults(['prefix' => $prefix]);
//        ->format('json');
};
