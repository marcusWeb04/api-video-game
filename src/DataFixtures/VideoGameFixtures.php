<?php

namespace App\DataFixtures;

use App\Entity\VideoGame;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideoGameFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
   
    public static function getGroups(): array
    {
        return ['videoGames'];
    }

    public function getDependencies(): array
    {
        return[
            EditorFixtures::class,
            CategoryFixtures::class,
        ];
    }

    private $data = [
        ["name"=>"The Legend of Zelda: Breath of the Wild",
        "date"=>"2017-03-03",
        "description"=>"L’histoire se déroule dans le royaume d’Hyrule, 100 ans après une catastrophe causée par le Fléau Ganon, 
        une entité maléfique ayant plongé le royaume dans le chaos. Link, un héros amnésique qui se réveille dans un sanctuaire 
        mystérieux. Guidé par la voix de la princesse Zelda, Link doit parcourir Hyrule pour retrouver ses souvenirs, libérer les 
        quatre Créatures Divines et vaincre Ganon pour restaurer la paix.",],
        ["name"=>"Elden Ring",
        "date"=>"2022-02-25",
        "description"=>" Dans le royaume de L’Entre-Terre, le Cercle d'Elden, source de l'ordre du monde, a été brisé. Cet événement a 
        entraîné le chaos et une guerre sanglante connue sous le nom de la Fracture. Les fragments du Cercle, appelés Grandes Runes, ont 
        été récupérés par des demi-dieux, les descendants de la reine Marika l'Éternelle, qui sont devenus fous de pouvoir et plongés 
        dans des conflits destructeurs. 
        Un Sans-éclat, un être banni depuis longtemps, rappelé pour restaurer le Cercle d'Elden. Guidé par des forces mystérieuses, 
        le Sans-éclat doit explorer un monde immense et dangereux, affronter les puissants détenteurs des Grandes Runes et décider du 
        destin de l'Entre-Terre."],
        ["name"=>"Apex Legend",
        "date"=>"2019-02-04",
        "description"=>"
        Dans l'univers de Titanfall, 18 ans après la guerre entre l’IMC et la Milice, les Terres Sauvages, une région reculée de 
        l’espace, sont devenues un terrain de chaos et de survie. Alors que les grandes factions ont abandonné la zone, des 
        mercenaires, chasseurs de primes et exilés se rassemblent pour participer aux Jeux Apex, un tournoi brutal où seule 
        l'élite survit.

        Chaque participant, connu sous le nom de Légende, possède une histoire et des motivations uniques pour entrer dans l’arène :
         richesse, gloire, vengeance ou rédemption. Ces champions combattent en escouades dans des batailles à mort, utilisant des 
         compétences uniques et des armes futuristes, tout en luttant pour devenir le dernier survivant.
        "],
        ["name"=>"Call of Duty: Black Ops II",
        "date"=>"2012-11-12",
        "description"=> "Alex Mason, un ancien opérateur de la CIA, alors qu’il combat contre Viktor Reznov et l'organisation 
        Nicaraguan. Les événements des années 1980 sont liés à des missions visant à stopper l'influence de l'URSS et leurs actions 
        dans le monde, en particulier les efforts de Raul Menendez, un leader révolutionnaire dangereux qui est à la tête d’un cartel 
        et d’une organisation terroriste."],
        ["name"=>"Valorant",
        "date"=>"2020-06-02",
        "description"=>"
        L'histoire se déroule dans un futur proche, où un mystérieux événement connu sous le nom de l'Ère de la Révélation a modifié 
        le monde, donnant à certains individus des pouvoirs surnaturels. Ces personnes sont appelées les Radiants et possèdent des 
        capacités extraordinaires qui peuvent altérer la réalité.

        Une organisation secrète appelée Vanguard, un groupe d'élite de Radiants et de non-Radiants qui luttent contre des menaces 
        mondiales et des forces rivales. Les agents de Vanguard sont envoyés dans des missions pour protéger le monde de ces menaces
        et exploiter le potentiel des Radiants tout en luttant contre une organisation malveillante, Kingdom Corporation, qui cherche 
        à manipuler les pouvoirs des Radiants pour ses propres fins.
        "],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach($this->data as $data){
            $videoGame = new VideoGame();
            $videoGame->setTitle($data["name"]);
            $videoGame->setReleaseDate($data["date"]);
            $videoGame->setDescription($data["description"]);
            
            $manager->persist($videoGame);
        }

        $manager->flush();
    }
}
