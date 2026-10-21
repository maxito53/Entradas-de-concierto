<?php

// bootstrap.php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__.'/../vendor/autoload.php' ;

// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/Entradas'], // ruta de entidades
    isDevMode: true,
); 

// or if you prefer XML
// $config = ORMSetup::createXMLMetadataConfiguration(
//    paths: [__DIR__ . '/config/xml'],
//    isDevMode: true,
//);

// configuring the database connection
$connection = DriverManager::getConnection([
    'driver' =>'pdo_mysql',
    'host' =>'127.0.0.1',
    'dbname' =>'TuraMax_Entradas',
    'user' =>'usr_generic',
    'password' =>'2025@Thos',
], $config);

// obtaining the entity manager
return $entityManager = new EntityManager($connection, $config);