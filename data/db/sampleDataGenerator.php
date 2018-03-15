<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Faker\Factory;

$faker = Factory::create();
$max = 500;

$pdo = new \PDO('mysql:host=127.0.0.1;dbname=phpbootcamp_crm;charset=utf8', 'phpbootcamp_crm', 'ZF4Fun&Profit');


if (false === ($memberStmt = $pdo->prepare('INSERT INTO `member` (`linkedin_id`, `access_token`, `created`, `modified`) VALUES (?, ?, ?, ?)'))) {
    throw new \RuntimeException('Cannot prepare statement for member: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($contactStmt = $pdo->prepare('INSERT INTO `contact` (`member_id`, `first_name`, `last_name`, `created`, `modified`) VALUES (?, ?, ?, ?, ?)'))) {
    throw new \RuntimeException('Cannot prepare statement for contact: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($addressStmt = $pdo->prepare('INSERT INTO `contact_address` (`member_id`, `contact_id`, `street_1`, `street_2`, `postcode`, `city`, `province`, `country_code`, `created`, `modified`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'))) {
    throw new \RuntimeException('Cannot prepare statement for address: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($emailAddressListStmt = $pdo->prepare('SELECT `email_address` FROM `contact_email` WHERE `email_address` = ?'))) {
    throw new \RuntimeException('Cannot prepare statement for e-mail retrieval: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($emailAddressPlusListStmt = $pdo->prepare('SELECT `email_address` FROM `contact_email` WHERE `email_address` = ?'))) {
    throw new \RuntimeException('Cannot prepare statement for e-mail retrieval: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($emailStmt = $pdo->prepare('INSERT INTO `contact_email` (`member_id`, `contact_id`, `email_address`, `primary`, `created`, `modified`) VALUES (?, ?, ?, ?, ?, ?)'))) {
    throw new \RuntimeException('Cannot prepare statement for e-mail: ' . implode(', ', $pdo->errorInfo()));
}

if (false === ($emailPlusStmt = $pdo->prepare('INSERT INTO `contact_email` (`member_id`, `contact_id`, `email_address`, `primary`, `created`, `modified`) VALUES (?, ?, ?, ?, ?, ?)'))) {
    throw new \RuntimeException('Cannot prepare statement for e-mail plus: ' . implode(', ', $pdo->errorInfo()));
}

for ($i = 0; $i < $max; $i++) {

    if (false === $memberStmt->execute([
            $faker->numberBetween(),
            $faker->randomAscii,
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        ])) {
        throw new \RuntimeException('Cannot execute prepared statement for member: ' . implode(', ', $contactStmt->errorInfo()));
    }
    $memberId = $pdo->lastInsertId();

    if (false === $contactStmt->execute([
        $memberId,
        $faker->firstName,
        $faker->lastName,
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
    ])) {
        throw new \RuntimeException('Cannot execute prepared statement for contact: ' . implode(', ', $contactStmt->errorInfo()));
    }

    $contactId = $pdo->lastInsertId();
    if (false === $addressStmt->execute([
        $memberId,
        $contactId,
        $faker->streetAddress,
        '',
        $faker->postcode,
        $faker->city,
        '',
        $faker->countryCode,
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
    ])) {
        throw new \RuntimeException('Cannot execute prepared statement for address: ' . implode(', ', $addressStmt->errorInfo()));
    }

    $emailPrimary = $faker->email;
    $emailAddressListStmt->bindValue(1, $emailPrimary, PDO::PARAM_STR);
    if (false === $emailAddressListStmt->execute()) {
        throw new \RuntimeException('Cannot execute prepared statement for e-mail retrieval: ' . implode(', ', $emailAddressListStmt->errorInfo()));
    }

    if (false === ($emailFound = $emailAddressListStmt->fetchColumn(0))) {

        if (false === $emailStmt->execute([
            $memberId,
            $contactId,
            $emailPrimary,
            1,
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
            $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        ])) {
            throw new \RuntimeException('Cannot execute prepared statement for e-mail: ' . implode(', ', $emailStmt->errorInfo()));
        }

    }

    $rand = rand(0, 3);
    for ($j = 0; $j < $rand; $j++) {

        $emailPlus = $faker->safeEmail;

        $emailAddressPlusListStmt->bindValue(1, $emailPlus, PDO::PARAM_STR);
        if (false === $emailAddressPlusListStmt->execute()) {
            throw new \RuntimeException('Cannot execute prepared statement for e-mail retrieval: ' . implode(', ', $emailAddressListStmt->errorInfo()));
        }

        if (false === ($emailPlusFound = $emailAddressPlusListStmt->fetchColumn(0))) {

            if (false === $emailPlusStmt->execute([
                    $memberId,
                    $contactId,
                    $emailPlus,
                    0,
                    $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
                    $faker->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
                ])) {
                throw new \RuntimeException('Cannot execute prepared statement for e-mail plus: ' . implode(', ', $emailAddressPlusStmt->errorInfo()));
            }
        }
    }
}
