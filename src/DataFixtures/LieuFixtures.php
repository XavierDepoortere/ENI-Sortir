<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {

            $lieu = new Lieu();

            $lieu->setNom($faker->sentence);
            $lieu->setRue($faker->address);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);

            $manager->persist($lieu);


        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
