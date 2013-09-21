<?php

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/Expenditure/app.php';
require __DIR__.'/../src/Expenditure/controllers.php';

$app->run();