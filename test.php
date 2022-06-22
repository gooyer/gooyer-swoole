<?php

require_once __DIR__ . "/vendor/autoload.php";

$boostrap = new \Gooyer\Application\Bootstrap();

$application = new Gooyer\Application\Application(__DIR__);

$boostrap->boot($application);
