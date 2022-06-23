<?php

declare(strict_types=1);

namespace Gooyer\Application\Bootstrap;

use FastRoute\RouteCollector;
use Gooyer\Contracts\Bootable;
use Gooyer\Contracts\Application;
use Gooyer\Application\Facades\Env;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServerBoot implements Bootable
{
    private function handleRequest($dispatcher, string $request_method, string $request_uri)
    {
        list($code, $handler, $vars) = $dispatcher->dispatch($request_method, $request_uri);

        switch ($code) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $result = [
                    'status' => 404,
                    'message' => 'Not Found',
                    'errors' => [
                        sprintf('The URI "%s" was not found', $request_uri)
                    ]
                ];
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $handler;
                $result = [
                    'status' => 405,
                    'message' => 'Method Not Allowed',
                    'errors' => [
                        sprintf('Method "%s" is not allowed', $request_method)
                    ]
                ];
                break;
            case \FastRoute\Dispatcher::FOUND:
                $result = call_user_func($handler, $vars);
                break;
        }

        return $result;
    }

    public function bootRoutes(Application $application, Server $server): void
    {
        // Load routes
        $server->on("request", function(Request $request, Response $response) {
            $request_method = $request->server['request_method'];
            $request_uri = $request->server['request_uri'];

            // populate the global state with the request info
            $_SERVER['REQUEST_URI'] = $request_uri;
            $_SERVER['REQUEST_METHOD'] = $request_method;
            $_SERVER['REMOTE_ADDR'] = $request->server['remote_addr'];

            $_GET = $request->get ?? [];
            $_FILES = $request->files ?? [];

            // form-data and x-www-form-urlencoded work out of the box so we handle JSON POST here
            if ($request_method === 'POST' && $request->header['content-type'] === 'application/json') {
                $body = $request->rawContent();
                $_POST = empty($body) ? [] : json_decode($body);
            } else {
                $_POST = $request->post ?? [];
            }

            // global content type for our responses
            $response->header('Content-Type', 'application/json');
            //Tutaj route
            $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) {
                $r->addRoute('GET', '/[{title}]', 'get_index_handler');
                $r->addRoute('POST', '/[{title}]', 'post_index_handler');
            });

            $result = $this->handleRequest($dispatcher, $request_method, $request_uri);

            // write the JSON string out
            $response->end(json_encode($result));
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
