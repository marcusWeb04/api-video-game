<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class EditorFixtures extends Fixture implements FixtureGroupInterface
{
    public const EDITOR_REF = "editor_";

    public static function getGroups(): array
    {
        return ['editor'];
    }

    private $data = [
        ["name"=>"Nintendo","country"=>"Japon"],
        ["name"=>"ubisoft","country"=>"France"],
        ["name"=>"sony","country"=>"Japon"],
        ["name"=>"RockStar","country"=>"Etat Unis"],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach($this->data as $data){
            $editor = new Editor;
            $editor->setName($data["name"]);
            $editor->setCountry($data["country"]);
            $manager->persist($editor);
        }

        $manager->flush();
    }
}
