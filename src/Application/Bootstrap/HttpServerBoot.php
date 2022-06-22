<?php

declare(strict_types=1);

namespace Gooyer\Application\Bootstrap;

use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;
use Gooyer\Application\Facades\Env;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServerBoot implements Bootable
{
    public function bootRoutes(Application $application, Server $server): void
    {
        // Load routes
        $server->on("onRequest", function(Request $request, Response $response) {

        });
    }

    public function boot(Application $application): void
    {
        $host = Env::get("HTTP_HOST", default: "127.0.0.1");
        $port = Env::get("HTTP_PORT", default: "80", type: "int");
        $workerNum = Env::get("HTTP_WORKER_NUM", default: 4);
        $taskWorkerNum = Env::get("HTTP_TASK_WORKER_NUM", default: 4);
        $backlog = Env::get("HTTP_BACKLOG", default: 128);

        $server = new \Swoole\HTTP\Server($host, $port);

        $this->bootRoutes($application, $server);

        $server->set([
            'worker_num' => $workerNum,      // The number of worker processes to start
            'task_worker_num' => $taskWorkerNum,  // The amount of task workers to start
            'backlog' => $backlog,       // TCP backlog connection number
        ]);

        $server->start();
    }
}
