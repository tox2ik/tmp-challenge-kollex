<?php


/**
 * @throws \Doctrine\ORM\ORMException
 */
function initOrm(array $connection = []): \Doctrine\ORM\EntityManager
{
    $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        [__DIR__ . '/../../src'],
        $isDevMode = true,
        $proxyDir = null,
        $cache = null,
        $useSimpleAnnotationReader = false
    );
    $conn = [
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../../var/db.sqlite',
    ];

    $connection = $connection ?: $conn;

    if ($connection['path'] ?? null) {
        mkdir(dirname($connection['path']), 0755, true);
    }

    try {
        $manager = Doctrine\ORM\EntityManager::create($connection, $config);
    } catch (\Doctrine\ORM\ORMException $ex) {
    }


    return $manager;
}
