<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Faker\Factory;

$faker = Factory::create();
$max = 5000;

$pdo = new \PDO('mysql:host=127.0.0.1;dbname=phpbootcamp_crm;charset=utf8', 'phpbootcamp_crm', 'ZF4Fun&Profit');

for ($i = 0; $i < $max; $i++) {
    $contactStmt = $pdo->prepare('INSERT INTO `contact` (`first_name`, `last_name`, `created`, `modified`) VALUES (?, ?, ?, ?)');
    $contactStmt->execute([
        $faker->firstName,
        $faker->lastName,
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
    ]);
    $contactId = $pdo->lastInsertId();
    $addressStmt = $pdo->prepare('INSERT INTO `contact_address` (`contact_id`, `street_1`, `street_2`, `postcode`, `city`, `province`, `country_code`, `created`, `modified`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $addressStmt->execute([
        $contactId,
        $faker->streetAddress,
        '',
        $faker->postcode,
        $faker->city,
        '',
        $faker->countryCode,
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
    ]);
    $rand = rand(0, 3);
    $emailStmt = $pdo->prepare('INSERT INTO `contact_email` (`contact_id`, `email_address`, `primary`, `created`, `modified`) VALUES (?, ?, ?, ?, ?)');
    $emailStmt->execute([
        $contactId,
        $faker->email,
        1,
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
    ]);
    for ($j = 0; $j < $rand; $j++) {
        $emailPlusStmt = $pdo->prepare('INSERT INTO `contact_email` (`contact_id`, `email_address`, `primary`, `created`, `modified`) VALUES (?, ?, ?, ?, ?)');
        $emailPlusStmt->execute([
            $contactId,
            $faker->email,
            0,
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        ]);
    }
}
