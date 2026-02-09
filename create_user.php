<?php
// Quick script to create a regular user
require 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv('.env');

$kernel = new \App\Kernel($_ENV['APP_ENV'] ?? 'dev', (bool) ($_ENV['APP_DEBUG'] ?? false));
$kernel->boot();

$container = $kernel->getContainer();
$em = $container->get('doctrine.orm.entity_manager');

$user = $em->getRepository(\App\Entity\User::class)->findOneBy(['email' => 'user@sport.com']);

if (!$user) {
    $user = new \App\Entity\User();
    $user->setEmail('user@sport.com');
    $user->setNom('User');
    $user->setPrenom('Sport');
    $user->setRoles(['ROLE_USER']);
    // use bcrypt
    $user->setPassword(password_hash('userpass', PASSWORD_BCRYPT));

    $em->persist($user);
    $em->flush();

    echo "✓ User created: user@sport.com / userpass\n";
} else {
    echo "User already exists\n";
}

$kernel->shutdown();
