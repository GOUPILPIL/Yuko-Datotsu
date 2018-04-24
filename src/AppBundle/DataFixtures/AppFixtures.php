<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 4/24/18
 * Time: 5:03 PM
 */
namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = array(

            'Compétition Individuelle',

            'Compétition Equipe',

            'Stage',

            'Passage de grade'

        );

        foreach ($names as $name) {
            // On crée la catégorie
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }

        $manager->flush();
    }
}