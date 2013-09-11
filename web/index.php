<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Expenditure\Provider\UserProvider;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => '../src/Expenditure/Resources/views'));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../app/config/dev.json"));
$app->register(new Psamatt\Silex\Provider\SpotServiceProvider($app['db']['dsn']));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
//            'pattern' => '^/admin',
            'pattern'   => '^/',
            'form' => array('login_path' => '/login', 'check_path' => '/login/check'),
            'logout' => array('logout_path' => '/logout'),
            'users' => $app->share(function () use ($app) {
                return new UserProvider($app['spot']);
            }),
            'anonymous' => true,
            'security' => true,
        ),
    )
));

$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
//    array('^/login', 'IS_AUTHENTICATED_ANONYMOUSLY'),
//    array('^/$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
//    array('^/profile/new', 'IS_AUTHENTICATED_ANONYMOUSLY'),
array('^/login','IS_AUTHENTICATED_ANONYMOUSLY'),
);

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {

    $twig->addExtension(new Expenditure\Twig\Extension\CarbonTwigExtension);
    $twig->addGlobal('CURRENCY', $app['currency']);
    $twig->addGlobal('NOW', new \DateTime);
    return $twig;
}));

$app['home.controller'] = $app->share(function() use ($app) {

    $defaultController = new Expenditure\Controller\DefaultController;
    $defaultController->setDB($app['spot']);
    $defaultController->setTwigRenderer($app['twig']);
    $defaultController->setSecurity($app['security']);
    $defaultController->setURLGenerator($app['url_generator']);

    return $defaultController;
});

$app['import.controller'] = $app->share(function() use ($app) {

    $importController = new Expenditure\Controller\ImportController;
    $importController->setDB($app['spot']);
    $importController->setTwigRenderer($app['twig']);
    $importController->setSecurity($app['security']);
    $importController->setURLGenerator($app['url_generator']);

    return $importController;
});

$app['month.expenditure.controller'] = $app->share(function() use ($app) {

    $expenditureController = new Expenditure\Controller\MonthExpenditureController;
    $expenditureController->setDB($app['spot']);
    $expenditureController->setTwigRenderer($app['twig']);
    $expenditureController->setSecurity($app['security']);
    $expenditureController->setURLGenerator($app['url_generator']);

    return $expenditureController;
});

$app['month.historic.controller'] = $app->share(function() use ($app) {

    $historicController = new Expenditure\Controller\MonthHistoricController;
    $historicController->setDB($app['spot']);
    $historicController->setTwigRenderer($app['twig']);
    $historicController->setSecurity($app['security']);
    $historicController->setURLGenerator($app['url_generator']);

    return $historicController;
});

$app['default.payments.controller'] = $app->share(function() use ($app) {

    $defaultPaymentController = new Expenditure\Controller\DefaultPaymentController;
    $defaultPaymentController->setDB($app['spot']);
    $defaultPaymentController->setTwigRenderer($app['twig']);
    $defaultPaymentController->setSecurity($app['security']);
    $defaultPaymentController->setURLGenerator($app['url_generator']);

    return $defaultPaymentController;
});

$app['savings.controller'] = $app->share(function() use ($app) {

    $savingsPaymentController = new Expenditure\Controller\SavingsController;
    $savingsPaymentController->setDB($app['spot']);
    $savingsPaymentController->setTwigRenderer($app['twig']);
    $savingsPaymentController->setSecurity($app['security']);
    $savingsPaymentController->setURLGenerator($app['url_generator']);

    return $savingsPaymentController;
});

$app['user.controller'] = $app->share(function() use ($app) {

    $userController = new Expenditure\Controller\UserController;
    $userController->setDB($app['spot']);
    $userController->setTwigRenderer($app['twig']);
    $userController->setSecurity($app['security']);
    $userController->setURLGenerator($app['url_generator']);
    $userController->setSession($app['session']);
    $userController->setEncoderFactory($app['security.encoder_factory']);
    $userController->setSecurityLastError($app['security.last_error']);

    return $userController;
});

$app->get('/', 'user.controller:loginAction');
$app->get('/login', 'user.controller:loginAction')->bind('login');
$app->match('/profile/new', 'user.controller:newAction')->bind('register')->method('GET|POST');
$app->match('/admin/profile/edit', 'user.controller:editAction')->bind('profile_edit')->method('GET|POST');
$app->post('/admin/profile/password/change', 'user.controller:changePasswordAction')->bind('profile_change_password');

$app->get('/admin/', 'home.controller:indexAction')->bind('admin_homepage');
$app->post('/admin/import/{year}/{month}', 'import.controller:saveAction')->bind('admin_import');

$app->post('/admin/month/{headerID}/expenditure/{expenditureID}/paid', 'month.expenditure.controller:paidAction')->bind('admin_expenditure_paid');
$app->post('/admin/month/{headerID}/expenditure/{expenditureID}/delete', 'month.expenditure.controller:deleteAction')->bind('admin_expenditure_delete');
$app->post('/admin/month/{headerID}/expenditure/save', 'month.expenditure.controller:saveAction')->bind('admin_expenditure_save');

$app->get('/admin/month/default/{defaultID}', 'default.payments.controller:viewAction')->bind('admin_payments')->value('defaultID', 0);
$app->get('/admin/month/default/{defaultID}/delete', 'default.payments.controller:deleteAction')->bind('admin_payments_delete');
$app->post('/admin/month/default/save', 'default.payments.controller:saveAction')->bind('admin_payments_save');

$app->get('/admin/month/historic', 'month.historic.controller:indexAction')->bind('admin_historic');
$app->get('/admin/month/historic/{year}/{month}', 'month.historic.controller:viewAction')->bind('admin_historic_view');

$app->get('/admin/savings', 'savings.controller:indexAction')->bind('admin_savings');
$app->post('/admin/savings/save', 'savings.controller:saveAction')->bind('admin_savings_save');
$app->post('/admin/savings/{savingID}/money/add', 'savings.controller:addMoneyAction')->bind('admin_savings_add');
$app->post('/admin/savings/{savingID}/delete', 'savings.controller:deleteAction')->bind('admin_savings_delete');

$app->run();