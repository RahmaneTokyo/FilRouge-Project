<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $profil = $this->getReference("p2");
        if ( isset($profil) ) {
            for ($i=0; $i <4 ; $i++) {
                $admin = new Apprenant();
                $admin ->setEmail ($faker->email);
                $admin ->setFirstname($faker->firstName());
                $admin ->setLastname($faker->lastName);
                $admin ->setAddress($faker->address);
                $admin ->setProfil($profil);
                $admin ->setArchived(0);
                $password = $this->encoder->encodePassword ($admin,'pass_1234');
                $admin ->setPassword ($password );
                $manager->persist($admin);
            }
        }

        $manager->flush();
    }

    public function getDependencies() {
        return array(
            ProfilFixtures::class,
        );
    }
}
