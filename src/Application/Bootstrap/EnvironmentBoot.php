<?php

declare(strict_types=1);

namespace Gooyer\Application\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;

class EnvironmentBoot implements Bootable
{
    public function boot(Application $application): void
    {
        $container = $application->getContainer();

        try {
            $env = Dotenv::createImmutable($application->getRootPath());
            $cfg = $env->load();
        } catch (InvalidPathException) {
            $cfg = [];
        }

        $container->bind("env", $cfg);
    }
}
