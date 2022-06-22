<?php

declare(strict_types=1);

namespace Gooyer\Application;

use Gooyer\Application\Bootstrap\HttpServerBoot;
use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;
use Gooyer\Application\Bootstrap\EnvironmentBoot;

class Bootstrap implements Bootable
{
    private array $bootables = [
        EnvironmentBoot::class,
        HttpServerBoot::class
    ];

    public function boot(Application $application): void
    {
        foreach ($this->bootables as $bootable) {
            $bootableRef = new $bootable;
            if ($bootableRef instanceof Bootable) {
                $bootableRef->boot($application);
            }
        }
    }

}
