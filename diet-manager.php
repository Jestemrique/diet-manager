<?php
/*
Plugin name: Diet planner
Plugin URI: https://jestemrique.net
Description: Plugin para dietas. Custom db tables.
Version: 1.0
Author: Enrique
Author URI: https:/www.jestemrique.net
License: GPLv2
*/

//Autoloader
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

use Epr\DietManager\AdminPages\AdminUsersPage;
use Epr\DietManager\AdminPages\AdminPlanDietPage;
use Epr\DietManager\Users\User;
use Epr\DietManager\PlanDieta\PlanAlimentacion;


$container = new ContainerBuilder();
$fileLocator = new FileLocator(__DIR__);
$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
$loader->load('services.yaml');

$db = $container->get('epr.db');
$db->createTables();

$users = new User($db);
$planAlimentacion = new PlanAlimentacion($db);
//add_action( 'user_register', array($users, 'registerUser'));


//Adding template engine. Twig.
$templatesFolder = new FilesystemLoader(__DIR__ . '/src/templates');
$template = new Environment($templatesFolder);



$usersAdminPage = new AdminUsersPage($users, $template);
$planAlimentacionAdminPage = new AdminPlanDietPage($planAlimentacion, $template);






