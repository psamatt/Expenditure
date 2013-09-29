<?php

use Silex\Application;

use Expenditure\Provider\UserProvider;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__ . '/Resources/views'));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__. '/../../app/config/dev.json'));

$app->register(new Silex\Provider\DoctrineServiceProvider, array(
    'db.options' => $app['db'],
));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    'orm.proxies_dir' => __DIR__ . '/../../app/cache/proxies',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'namespace' => 'Expenditure\Entity',
                'alias' => 'Expenditure',
                'path' => __DIR__ . '/Entity',
                'use_simple_annotation_reader' => false,
            ),
        ),
    ),
));

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app['validator.mapping.class_metadata_factory'] = new Symfony\Component\Validator\Mapping\ClassMetadataFactory(
    new Symfony\Component\Validator\Mapping\Loader\YamlFileLoader(__DIR__.'/../../app/config/validation.yml')
);  

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern'   => '^/',
            'form' => array('login_path' => '/login', 'check_path' => '/login/check'),
            'logout' => array('logout_path' => '/logout'),
            'users' => $app->share(function () use ($app) {
                return new UserProvider($app['orm.em']);
            }),
            'anonymous' => true,
            'security' => true,
        ),
    )
));

$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
    array('^/login','IS_AUTHENTICATED_ANONYMOUSLY'),
);

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Expenditure\Twig\Extension\CarbonTwigExtension);
    $twig->addGlobal('CURRENCY', $app['currency']);
    $twig->addGlobal('NOW', new \DateTime);
    return $twig;
}));

return $app;