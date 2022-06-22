<?php

declare(strict_types=1);


namespace Gooyer\Application;

use Gooyer\Container\Container;
use Gooyer\Contracts\Module;

class Application implements \Gooyer\Contracts\Application
{
    private Container $container;
    private string $rootPath;

    public function __construct(string $rootAppPath)
    {
        $this->rootPath = $rootAppPath;
        $this->container = Container::instance();
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function register($module): void
    {
        // TODO: Implement register() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}
