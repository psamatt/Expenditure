<?php

$loader = require __DIR__.'/../app/autoload.php';

$app = require __DIR__.'/../src/Expenditure/app.php';
require __DIR__.'/../src/Expenditure/controllers.php';

$app->run();