<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const EDITOR_REF = "user_";

    public static function getGroups(): array
    {
        return ['user'];
    }

    private $data = [
        [
            "email" => "admin@gmail.com",
            "roles" => ["ROLE_ADMIN"],
            "password" => "admin",
            "subscription_to_newsletter" => false,
        ],
        [
            "email" => "user@example.com",
            "roles" => ["ROLE_USER"],
            "password" => "user",
            "subscription_to_newsletter" => true,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach($this->data as $data) {
            $user = new User();
            $user->setEmail($data["email"]);
            $user->setRoles($data["roles"]);
            $user->setPassword(password_hash($data["password"], PASSWORD_BCRYPT));
            $user->setSubcriptionToNewsletter($data["subscription_to_newsletter"]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
