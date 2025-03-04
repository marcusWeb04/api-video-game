<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public const CATEGORY_REF = "category_";

    public static function getGroups(): array
    {
        return ['categories'];
    }

    private $data = [
        ["name"=>"FPS"],
        ["name"=>"RogueLike"],
        ["name"=>"MMORPG"],
        ["name"=>"Bac à sable"],
        ["name"=>"Hero Shooter"],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach($this->data as $data){
        $category = new Category();
        $category->setName($data["name"]);
        $manager->persist($category);
        }

        $manager->flush();
    }
}
