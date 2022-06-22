<?php

declare(strict_types=1);

namespace Gooyer\Application\Bootstrap;

use Dotenv\Dotenv;
use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;

class EnvironmentBoot implements Bootable
{
    public function boot(Application $application): void
    {
        $env = Dotenv::createImmutable($application->getRootPath());
        $container = $application->getContainer();
        $cfg = $env->load();
        $container->bind("env", $cfg);
    }
}
