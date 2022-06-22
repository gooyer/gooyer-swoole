<?php

declare(strict_types=1);

namespace Gooyer\Application\Bootstrap;

use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;
use Gooyer\Application\Facades\Env;

class HttpServerBoot implements Bootable
{
    public function boot(Application $application): void
    {
        $port = Env::get("HTTP_PORT", default: 80);


    }
}
