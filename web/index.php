<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../app/config/dev.json"));
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => '../src/Expenditure/Resources/views'));
$app->register(new Psamatt\Silex\SpotServiceProvider($app['db']['dsn']));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->addExtension(new Expenditure\Twig\Extension\Carbon);
    $twig->addGlobal('CURRENCY', $app['currency']);
    $twig->addGlobal('NOW', new \DateTime);
    return $twig;
}));

$app['home.controller'] = $app->share(function() use ($app) {

    $defaultController = new Expenditure\Controller\DefaultController;
    $defaultController->setDB($app['spot']);
    $defaultController->setTwigRenderer($app['twig']);

    return $defaultController;
});

$app['import.controller'] = $app->share(function() use ($app) {

    $importController = new Expenditure\Controller\ImportController;
    $importController->setDB($app['spot']);
    $importController->setTwigRenderer($app['twig']);

    return $importController;
});

$app['month.expenditure.controller'] = $app->share(function() use ($app) {

    $expenditureController = new Expenditure\Controller\MonthExpenditureController;
    $expenditureController->setDB($app['spot']);
    $expenditureController->setTwigRenderer($app['twig']);

    return $expenditureController;
});

$app['month.historic.controller'] = $app->share(function() use ($app) {

    $historicController = new Expenditure\Controller\MonthHistoricController;
    $historicController->setDB($app['spot']);
    $historicController->setTwigRenderer($app['twig']);

    return $historicController;
});

$app['default.payments.controller'] = $app->share(function() use ($app) {

    $defaultPaymentController = new Expenditure\Controller\DefaultPaymentController;
    $defaultPaymentController->setDB($app['spot']);
    $defaultPaymentController->setTwigRenderer($app['twig']);

    return $defaultPaymentController;
});

$app->get('/', 'home.controller:indexAction');
$app->post('/import/{year}/{month}', 'import.controller:saveAction');

$app->post('/month/{headerID}/expenditure/{expenditureID}/paid', 'month.expenditure.controller:paidAction');
$app->post('/month/{headerID}/expenditure/{expenditureID}/delete', 'month.expenditure.controller:deleteAction');
$app->post('/month/{headerID}/expenditure/save', 'month.expenditure.controller:saveAction');

$app->get('/month/default/{defaultID}', 'default.payments.controller:viewAction')->value('defaultID', 0);
$app->get('/month/default/{defaultID}/delete', 'default.payments.controller:deleteAction');
$app->post('/month/default/save', 'default.payments.controller:saveAction');

$app->get('/month/historic', 'month.historic.controller:indexAction');
$app->get('/month/historic/{year}/{month}', 'month.historic.controller:viewAction');

$app->run();