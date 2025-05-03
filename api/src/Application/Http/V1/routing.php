<?php

declare(strict_types=1);

namespace App\Application\Http\V1;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routing): void {
    $routing
        ->import(__DIR__, 'attribute')
        ->prefix($prefix = '/v1', false)
        ->namePrefix('v1_')
        ->defaults(['prefix' => $prefix]);
//        ->format('json');
};
