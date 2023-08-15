<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');


        $campusIds = [1, 2, 3];

        for ($i = 0; $i < 10; $i++) { // Générer 10 participants avec des campus aléatoires
            $participant = new Participant();

            $participant->setRoles(['ROLE_USER']);

            $participant->setNom($faker->lastName);
            $participant->setPrenom($faker->firstName);
            $participant->setPseudo($faker->lastName);
            $participant->setEmail($faker->email);
            //$participant->setTelephone($faker->phoneNumber);
            $participant->setAdministrateur($faker->boolean);
            $participant->setActif($faker->boolean);
            $participant->setPassword($this->passwordHasher->hashPassword($participant, 'password'));
            $randomCampusId = $faker->randomElement($campusIds);

            // Obtenez l'entité Campus à partir de l'ID
            $campus = $manager->getRepository(Campus::class)->find($randomCampusId);

            if ($campus) {
                $participant->setCampus($campus);
                $manager->persist($participant);
            }
        }

        $manager->flush();
    }
}
